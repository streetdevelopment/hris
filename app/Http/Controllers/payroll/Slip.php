<?php

namespace App\Http\Controllers\payroll;

use DateTime;
use App\Models\payroll\Run;
use Illuminate\Http\Request;
use App\Models\company\JobTypes;
use App\Models\payroll\Slip as Sp;
use App\Http\Controllers\Controller;

class Slip extends Controller
{
    public function index()
    {
        $runs = Run::where('co_id', Auth()->user()->company->co_id)->orderBy('created_at', 'desc')->get();
        return view('payroll.slips', compact('runs'));
    }
    public function employee_payslips($id)
    {
        $slips = Sp::where('user_id', $id)->get();
        return view('payroll.employee_slips', compact('slips'));
    }
    public function view($id)
    {
        $slip = Sp::find($id);
        $run = $slip->run;
        $user = [$slip->user->id => 'on'];
        $employee = $slip->user;
        $records = $employee->getARbetween($run->pay_period_start, $run->pay_period_end);
        $recordsByDate = [];
        $jt = JobTypes::find($slip->jt_id);

        foreach ($records as $record) {
            $date = $record->date;

            if (!isset($recordsByDate[$date])) {
                $recordsByDate[$date] = [];
            }

            $recordsByDate[$date]['records'][] = $record;
            $recordsByDate[$date]['type'] = 'Attendance';
        }
        $overtimes = $employee->overtimeRequestsBetween($run->pay_period_start, $run->pay_period_end);
        $overtimesByDate = [];
        foreach ($overtimes as $overtime) {
            $startDateTime = new DateTime($overtime->start);
            $endDateTime = new DateTime($overtime->end);
            $startDate = $startDateTime->format('Y-m-d');
            $endDate = $endDateTime->format('Y-m-d');

            if ($startDate == $endDate) {
                // Same day overtime
                if (!isset($overtimesByDate[$startDate])) {
                    $overtimesByDate[$startDate] = [];
                }

                $overtimesByDate[$startDate]['records'][] = $overtime;
                $overtimesByDate[$startDate]['type'] = 'Overtime';
            } else {
                // Overtime spans multiple days
                // 1. Handle the first day (until 11:59 PM)
                $endOfFirstDay = (clone $startDateTime)->setTime(23, 59, 59);

                if (!isset($overtimesByDate[$startDate])) {
                    $overtimesByDate[$startDate] = [];
                }

                // Add overtime for the first day (from start to 11:59 PM)
                $firstDayOvertime = clone $overtime;
                $firstDayOvertime->end = $endOfFirstDay->format('Y-m-d H:i:s'); // Set the end time to 11:59 PM

                $overtimesByDate[$startDate]['records'][] = $firstDayOvertime;
                $overtimesByDate[$startDate]['type'] = 'Overtime';

                // 2. Handle the second day (from 12:00 AM)
                $startOfSecondDay = (clone $endOfFirstDay)->modify('+1 second');
                $secondDate = $startOfSecondDay->format('Y-m-d');

                if (!isset($overtimesByDate[$secondDate])) {
                    $overtimesByDate[$secondDate] = [];
                }

                // Add overtime for the second day (from 12:00 AM to end)
                $secondDayOvertime = clone $overtime;
                $secondDayOvertime->start = $startOfSecondDay->format('Y-m-d H:i:s'); // Set the start time to 12:00 AM

                $overtimesByDate[$secondDate]['records'][] = $secondDayOvertime;
                $overtimesByDate[$secondDate]['type'] = 'Overtime';
            }
        }

        $leaves = $employee->leaveRequestsBetween($run->pay_period_start, $run->pay_period_end);
        $absenceReport = $employee->absenceReport($run->pay_period_start, $run->pay_period_end, $records, $leaves);
        $hoursOfPaidLeave = 0;
        $totalDays = 0;
        $hoursADay = $employee->userPSI->position->jt->totalWorkHours();
        foreach ($leaves as $leave) {
            $totalDays += $leave->numberOfDays();
            $hoursOfPaidLeave += $hoursADay * $leave->numberOfDays();
        }

        $deductions = $run->deductions;

        return view('payroll.view_slip', compact('jt', 'deductions', 'slip', 'run', 'recordsByDate', 'employee', 'overtimesByDate', 'overtimes', 'hoursOfPaidLeave', 'totalDays', 'hoursADay', 'absenceReport'));
    }
}
