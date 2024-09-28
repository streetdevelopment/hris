<?php

namespace App\Models\payroll;

use Carbon\Carbon;
use App\Models\User;
use App\Models\company\Company;
use App\Models\company\Deductions;
use App\Models\PayrollDeduction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Run extends Model
{
    use HasFactory;
    protected $table = 'payroll_run';
    protected $primaryKey = 'payroll_run_id';
    public $incrementing = true;
    protected $fillable = [
        'co_id',
        'run_date',
        'pay_period_start',
        'pay_period_end',
        'total_gross_pay',
        'total_deductions',
        'total_net_pay',
        'status'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'co_id');
    }
    public function badge()
    {
        return $this->status == 'Draft' ? 'bg-primary' : ($this->status == 'Pending' ? 'bg-warning' : ($this->status == 'Processed' ? 'bg-success' : 'bg-danger'));
    }
    public function payslips()
    {
        return $this->hasMany(Slip::class, 'payroll_run_id');
    }
    public function process($id, $data, $start, $end)
    {
        $workedHours = [];
        $workedHours['total_deductions'] = 0;
        $workedHours['total_gross_pay'] = 0;
        $workedHours['total_net_pay'] = 0;
        foreach ($data as $key => $value) {
            if ($value === 'on') {
                $emp = User::find($key);
                $deductions = $emp->company->deductions;
                $workedHours[$emp->fullname()] = [];
                $workedHours[$emp->fullname()]['basic salary'] = $emp->userPSI->position->salary;
                $workedHours[$emp->fullname()]['total hours'] = 0;
                $leaves = $emp->leaveRequests;

                $overtimes = $emp->overtimeRequestsBetween($start, $end);
                $workedHours[$emp->fullname()]['overtime_hours'] = 0;
                $workedHours[$emp->fullname()]['overtime_pay'] = 0;

                $workedHours[$emp->fullname()]['deductions'] = 0;

                $workedHours[$emp->fullname()]['gross_pay'] = 0;
                $workedHours[$emp->fullname()]['net_pay'] = 0;

                $workedHours['deductions'] = [];

                $recs = $emp->getARbetween($start, $end);
                if ($emp->userPSI->position->jt->pay_rate == 'Hourly') {
                    foreach ($recs as $rec) {
                        $workedHours[$emp->fullname()]['total hours'] += $rec->workedHours();
                    }
                    foreach ($leaves as $leave) {
                        if ($leave->status == 'Approved') {
                            // Calculate the difference in hours between start_time and end_time
                            $hours = Carbon::parse($emp->userPSI->position->jt->start_time)->diffInHours(Carbon::parse($emp->userPSI->position->jt->end_time));

                            // Subtract 1 hour
                            $hours -= 1;
                            $workedHours[$emp->fullname()]['total hours'] += $hours * $leave->numberOfDays();
                        }
                    }
                    foreach ($overtimes as $ot) {
                        if ($ot->status == 'Approved') {
                            $workedHours[$emp->fullname()]['overtime_hours'] += $ot->numberOfHours();
                        }
                    }

                    $workedHours[$emp->fullname()]['overtime_pay'] = $workedHours[$emp->fullname()]['overtime_hours'] * $workedHours[$emp->fullname()]['basic salary'];
                    $workedHours[$emp->fullname()]['gross_pay'] = $workedHours[$emp->fullname()]['total hours'] * $workedHours[$emp->fullname()]['basic salary'];
                    $workedHours[$emp->fullname()]['gross_pay'] += $workedHours[$emp->fullname()]['overtime_pay'];
                    $workedHours[$emp->fullname()]['net_pay'] = $workedHours[$emp->fullname()]['gross_pay'];
                    foreach ($deductions as $tax) {
                        if ($tax->status !== 'inactive') {
                            $workedHours[$emp->fullname()]['net_pay'] -= $tax->value;
                            $workedHours[$emp->fullname()]['deductions'] += $tax->value;
                            $workedHours['deductions'][] = $tax->ded_id;
                        }
                    }

                    $payslip = Slip::create([
                        'payroll_run_id' => $id,
                        'user_id' => $emp->id,
                        'basic_salary' => $workedHours[$emp->fullname()]['basic salary'],
                        'overtime_hours' => $workedHours[$emp->fullname()]['overtime_hours'],
                        'deductions' => $workedHours[$emp->fullname()]['deductions'],
                        'overtime_pay' => $workedHours[$emp->fullname()]['overtime_pay'],
                        'bonuses' => 0,
                        'net_pay' => $workedHours[$emp->fullname()]['net_pay'],
                        'jt_id' => $emp->userPSI->position->jt->jt_id,
                        'status' => 'Issued',
                    ]);
                    $workedHours['total_deductions'] += $workedHours[$emp->fullname()]['deductions'];
                    $workedHours['total_gross_pay'] += $workedHours[$emp->fullname()]['gross_pay'];
                    $workedHours['total_net_pay'] += $workedHours[$emp->fullname()]['net_pay'];
                } else {
                    $hourlyRate = number_format($emp->getHourlyRate(), 2);
                    $late_deductions = 0;

                    foreach ($recs as $rec) {
                        if ($rec->status == 'late') {
                            $start_time = $emp->userPSI->position->jt->start_time;
                            list($start_hour, $start_minute, $start_second) = explode(':', $start_time);
                            $time_in = Carbon::parse($rec->time_in);
                            $workStartTime = Carbon::create($time_in->year, $time_in->month, $time_in->day, $start_hour, $start_minute, $start_second); // 09:00

                            // Calculate the difference in minutes
                            $morning_deductions = $workStartTime->diffInMinutes($time_in);
                            $late_deductions += $morning_deductions;
                        }
                    }
                    $final_late_deductions = ($late_deductions / 60) * $emp->getHourlyRate();

                    $workedHours[$emp->fullname()]['total_late_deductions'] = $late_deductions;
                    // foreach ($leaves as $leave) {
                    //     if ($leave->status == 'Approved') {
                    //         // Calculate the difference in hours between start_time and end_time
                    //         $hours = Carbon::parse($emp->userPSI->position->jt->start_time)->diffInHours(Carbon::parse($emp->userPSI->position->jt->end_time));

                    //         // Subtract 1 hour
                    //         $hours -= 1;
                    //         $workedHours[$emp->fullname()]['total hours'] += $hours * $leave->numberOfDays();
                    //     }
                    // }
                    foreach ($overtimes as $ot) {
                        if ($ot->status == 'Approved') {
                            $workedHours[$emp->fullname()]['overtime_hours'] += $ot->numberOfHours();
                        }
                    }
                    $absenceReport = $emp->absenceReport($start, $end, $recs, $leaves);
                    // Absence report returns three array keys: 
                    // total_working_days_absent
                    // working_days_absent_with_leave
                    // working_days_absent_without_leave
                    // total_leave_days_with_record
                    $absentDeductions = $absenceReport['working_days_absent_without_leave'] * $emp->userPSI->position->jt->totalWorkHours();
                    $final_absent_deductions = $absentDeductions * $emp->getHourlyRate();
                    $approvedLeaveCompensations = $absenceReport['working_days_absent_with_leave'];
                    $final_leave_compensation = $approvedLeaveCompensations * $emp->userPSI->position->jt->totalWorkHours() * $emp->getHourlyRate();
                    $leaveOverlaps = $absenceReport['total_leave_days_with_record'] * $emp->userPSI->position->jt->totalWorkHours();
                    $finalLeaveOverlaps = $leaveOverlaps * $emp->getHourlyRate();
                    $workedHours[$emp->fullname()]['overtime_pay'] = $workedHours[$emp->fullname()]['overtime_hours'] * $emp->getHourlyRate();
                    $workedHours[$emp->fullname()]['gross_pay'] = $workedHours[$emp->fullname()]['basic salary'] - $final_late_deductions - $final_absent_deductions;
                    $workedHours[$emp->fullname()]['gross_pay'] -= $finalLeaveOverlaps;
                    if ($workedHours[$emp->fullname()]['gross_pay'] < 0) {
                        $workedHours[$emp->fullname()]['gross_pay'] = 0;
                    }
                    $workedHours[$emp->fullname()]['gross_pay'] += $workedHours[$emp->fullname()]['overtime_pay'];
                    $workedHours[$emp->fullname()]['net_pay'] = $workedHours[$emp->fullname()]['gross_pay'];
                    foreach ($deductions as $tax) {
                        if ($tax->status !== 'inactive') {
                            $workedHours[$emp->fullname()]['net_pay'] -= $tax->value;
                            $workedHours[$emp->fullname()]['deductions'] += $tax->value;
                            $workedHours['deductions'][] = $tax->ded_id;
                        }
                    }
                    $payslip = Slip::create([
                        'payroll_run_id' => $id,
                        'user_id' => $emp->id,
                        'basic_salary' => $workedHours[$emp->fullname()]['basic salary'],
                        'overtime_hours' => $workedHours[$emp->fullname()]['overtime_hours'],
                        'deductions' => $workedHours[$emp->fullname()]['deductions'],
                        'overtime_pay' => $workedHours[$emp->fullname()]['overtime_pay'],
                        'bonuses' => 0,
                        'net_pay' => $workedHours[$emp->fullname()]['net_pay'],
                        'jt_id' => $emp->userPSI->position->jt->jt_id,
                        'status' => 'Issued',
                    ]);
                    $workedHours['total_deductions'] += $workedHours[$emp->fullname()]['deductions'];
                    $workedHours['total_gross_pay'] += $workedHours[$emp->fullname()]['gross_pay'];
                    $workedHours['total_net_pay'] += $workedHours[$emp->fullname()]['net_pay'];
                }
            }
        }
        $workedHours['run_date'] = Carbon::today();
        $workedHours['status'] = 'Processed';
        return $workedHours;
    }
    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class, 'payroll_run_id');
    }
    public function totalDeductions()
    {
        $total = 0;
        foreach ($this->deductions as $ded) {
            $total += $ded->deduction->value;
        }
        return $total;
    }
}
