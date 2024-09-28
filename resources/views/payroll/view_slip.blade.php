@extends('layouts.layout')
@section('title', 'HRIS - View Employee Payslip')
@section('content')
@php
if(Auth()->user()->id !== $employee->id) {
if(Auth()->user()->role == 'employee') {
abort(401);
}
}
@endphp
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice-title">
                                <h4 class="float-end font-size-16">Payroll Run # {{$run->payroll_run_id}}</h4>
                                <div class="auth-logo mb-4">
                                    <img src="{{asset('assets/images/logos/HRIS.png')}}" alt="logo" class="auth-logo-dark" height="20">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <address>
                                        <strong>Employee Name:</strong><br>
                                        {{$employee->fullname()}}<br>
                                    </address>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <address class="mt-2 mt-sm-0">
                                        <strong>Payoll Scope:</strong><br>
                                        {{\Carbon\Carbon::parse($run->pay_period_start)->format('F j, Y')}}<br>
                                        to <br>
                                        {{\Carbon\Carbon::parse($run->pay_period_end)->format('F j, Y')}}
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mt-3">
                                    <address>
                                        <strong>Department:</strong><br>
                                        {{$employee->userPSI->position->department->dep_name}} Department<br>
                                    </address>
                                </div>
                            </div>
                            <div class="py-2 mt-3">
                                <h3 class="font-size-15 fw-bold">Attendance Records</h3>
                            </div>
                            @php
                            $totalLateDeductions = 0;
                            @endphp
                            @foreach ($recordsByDate as $date => $records)
                            <div class="table-responsive table-bordered">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class='bg-light' style='width: 25%;'>Date: <span class='text-muted'>{{\Carbon\Carbon::parse($date)->format('F j, Y')}}</span></th>
                                            <th colspan='2' class='bg-light text-end' style='width: 25%;'>Type: <span class='text-success'>{{$records['type']}}</span></th>
                                        </tr>
                                        <tr>
                                            <th style='width: 25%;'>Time In</th>
                                            <th style='width: 25%;'>Time Out</th>
                                            <th style='width: 25%;'>Status</th>
                                            <th class="text-end" style='width: 25%;'>Hours of Work</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $work_count = 0;
                                        @endphp
                                        @foreach ($records['records'] as $record)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($record->time_in)->format('g:i A')}}</td>
                                            <td>{{\Carbon\Carbon::parse($record->time_out)->format('g:i A')}}</td>
                                            <td>{{$record->status}}</td>
                                            <td class="text-end">{{$record->workedHours($slip->jt_id)}} Hours</td>
                                            @php $work_count += $record->workedHours($slip->jt_id); @endphp
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Total:</b> {{$work_count}} Hours</td>
                                            <td class="text-end"><b>{{$jt->pay_rate !== 'Hourly' ? ' Late Deductions' : 'Earnings'}}: </b>
                                                <span class="{{$jt->pay_rate !== 'Hourly' ? 'text-danger' : 'text-success'}}"> </b><span class="badge {{$jt->pay_rate !== 'Hourly' ? 'bg-danger' : 'bg-success'}}">
                                                        @if($jt->pay_rate !== 'Hourly')
                                                        @php
                                                        if($record->status == 'late') {
                                                        $start_time = $jt->start_time;
                                                        list($start_hour, $start_minute, $start_second) = explode(':', $start_time);
                                                        $time_in = \Carbon\Carbon::parse($record->time_in);
                                                        $workStartTime = \Carbon\Carbon::create($time_in->year, $time_in->month, $time_in->day, $start_hour, $start_minute, $start_second);

                                                        $morning_deductions = $workStartTime->diffInMinutes($time_in);
                                                        $totalLateDeductions += $morning_deductions;
                                                        }
                                                        @endphp
                                                        @if($record->status == 'late')
                                                        @php
                                                        $morning_deductions = $record->status == 'late' ? $morning_deductions : 0;

                                                        // Extract minutes and seconds
                                                        $minutes = floor($morning_deductions); // Get the whole number for minutes
                                                        $seconds = ($morning_deductions - $minutes) * 60; // Convert the decimal part to seconds

                                                        echo $minutes . ' minutes and ' . number_format($seconds, 0) . ' seconds';
                                                        @endphp
                                                        @else
                                                        0
                                                        @endif
                                                        @else
                                                        {{ '₱ ' . number_format($work_count * $slip->basic_salary, 2)}}
                                                        @endif
                                                    </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                            @php $overtime_count = 0; @endphp
                            @php $finalLateDeductions = ($totalLateDeductions / 60) * $employee->getHourlyRate(); @endphp
                            @if(count($overtimes) > 0)
                            <div class="table-responsive table-bordered">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class='bg-light' style='width: 25%;'>Date: <span class='text-muted'>{{\Carbon\Carbon::parse($date)->format('F j, Y')}}</span></th>
                                            <th colspan='2' class='bg-light text-end' style='width: 25%;'>Type: <span class='text-warning'>Overtime</span></th>
                                        </tr>
                                        <tr>
                                            <th style='width: 25%;'>Time In</th>
                                            <th style='width: 25%;'>Time Out</th>
                                            <th style='width: 25%;'>Status</th>
                                            <th class="text-end" style='width: 25%;'>Hours of Work</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overtimes as $ot)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($ot->start)->format('g:i A')}}</td>
                                            <td>{{\Carbon\Carbon::parse($ot->end)->format('g:i A')}}</td>
                                            <td>{{$ot->status}}</td>
                                            <td class="text-end">{{number_format($ot->numberOfHours(),2 )}} Hours</td>
                                            @php $overtime_count += $ot->numberOfHours() @endphp
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Total:</b> {{number_format($overtime_count, 2)}} Hours</td>
                                            <td class="text-end"><b>Earnings: </b><span class="text-success"> </b><span class="badge bg-success">{{'₱ ' . number_format($overtime_count * ($jt->pay_rate !== 'Hourly' ? $employee->getHourlyRate() : $slip->basic_salary), 2)}}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @php $overtime_count = number_format($overtime_count, 2); @endphp
                            @if($jt->pay_rate == 'Hourly')
                            @if($hoursOfPaidLeave > 0)
                            <div class="table-responsive table-bordered">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th colspan='3' class='bg-light text-end'>Type: <span class='text-info'>Paid Leave</span></th>
                                        </tr>
                                        <tr>
                                            <th style='width: 33%;'>Number of Days</th>
                                            <th style='width: 33%;'>Work Hours a Day</th>
                                            <th class="text-end" style='width: 33%;'>Total Hours of Paid Leave</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$totalDays}} {{$totalDays > 1 ? 'Days' : 'Day'}}</td>
                                            <td>{{$hoursADay}} Hours</td>
                                            <td class="text-end">{{$hoursOfPaidLeave}} Hours</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">
                                                <b>Earnings: </b>
                                                <span class="badge bg-success">
                                                    {{
                                                        '₱ ' . number_format(
                                                            $hoursOfPaidLeave *
                                                            (
                                                                $jt->pay_rate !== 'Hourly' ? Auth()->user()->getHourlyRate() : (Auth()->user()->userPSI->position->salary)
                                                            ),
                                                            2
                                                        ) 
                                                    }}
                                                </span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @else
                            @endif
                            @endif
                            @if($jt->pay_rate !== 'Hourly')
                            <div class="table-responsive table-bordered">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th colspan='4' class='bg-light text-end'>Type: <span class='text-danger'>Absences</span></th>
                                        </tr>
                                        <tr>
                                            <th style='width: 33%;'>Total Absences</th>
                                            <th style='width: 33%;'>Absences with Approved Leave</th>
                                            <th style='width: 33%;'>Absences without Approved Leave</th>
                                            <th style='width: 33%;'>Leave Days With Attendance Record</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach($absenceReport as $key => $report)
                                            <td>{{$report}} {{$report > 1 ? 'Days' : 'Day'}}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">
                                                <b>Total Deductions from Absences: </b>
                                                <span class="badge bg-danger">
                                                    {{'₱ ' . (number_format(
                                                            $absenceReport['working_days_absent_without_leave'] * $jt->totalWorkHours() *
                                                            (
                                                                $employee->getHourlyRate()
                                                            ),
                                                            2
                                                        ) > $slip->basic_salary ? number_format($slip->basic_salary, 2) : number_format(
                                                            $absenceReport['working_days_absent_without_leave'] * $jt->totalWorkHours() *
                                                            (
                                                                $employee->getHourlyRate()
                                                            ),
                                                            2
                                                        ) )}}
                                                </span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @php $total_company_deductions = 0; @endphp
                            @if($deductions->count() > 0)
                            <div class="table-responsive table-bordered">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th colspan='3' class='bg-light text-end'>Type: <span class='text-primary'>Company Deductions</span></th>
                                        </tr>
                                        <tr>
                                            <th style='width: 33%;'>Deduction Type</th>
                                            <th style='width: 33%;'>Description</th>
                                            <th class="text-end" style='width: 33%;'>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($deductions as $deduction)
                                        <tr>
                                            <td>{{$deduction->deduction->deduction_type}}</td>
                                            <td>{{$deduction->deduction->description}}</td>
                                            <td class="text-end">{{'₱ ' . number_format($deduction->deduction->value, 2)}}</td>
                                        </tr>
                                        @php $total_company_deductions += $deduction->deduction->value; @endphp
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">
                                                <b>Total Company Deductions: </b>
                                                <span class="badge bg-info">
                                                    {{'₱ ' . number_format($run->totalDeductions(), 2)}}
                                                </span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="table-responsive table-bordered">
                                <table class="table table-nowrap">
                                    <tbody>
                                        @if($jt->pay_rate !== 'Hourly')
                                        <tr>
                                            <th class='bg-light text-end'>Basic Salary: <span class='badge bg-success'>{{'₱ ' . number_format($slip->basic_salary, 2)}}</span></th>
                                        </tr>
                                        @if(count($overtimesByDate) > 0)
                                        <tr>
                                            <th class='bg-light text-end'>Total Overtime Compensation: <span class='badge bg-success'>{{'₱ ' . number_format($overtime_count * $employee->getHourlyRate($slip->basic_salary), 2)}}</span></th>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class='bg-light text-end'>Total Company Deductions: <span class='badge bg-warning'>{{'₱ ' . number_format($total_company_deductions, 2)}}</span></th>
                                        </tr>
                                        <tr>
                                            <th class='bg-light text-end'>Total Late Deductions: <span class='badge bg-danger'>{{'₱ ' . number_format($finalLateDeductions, 2)}}</span></th>
                                        </tr>
                                        <tr>
                                            <th class='bg-light text-end'>Total Absences Deductions: <span class='badge bg-danger'>{{'₱ ' . (number_format(
                                                            $absenceReport['working_days_absent_without_leave'] * $jt->totalWorkHours() *
                                                            (
                                                                $employee->getHourlyRate()
                                                            ),
                                                            2
                                                        ) > $slip->basic_salary ? number_format($slip->basic_salary, 2) : number_format(
                                                            $absenceReport['working_days_absent_without_leave'] * $jt->totalWorkHours() *
                                                            (
                                                                $employee->getHourlyRate()
                                                            ),
                                                            2
                                                        ) )}}</span></th>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class='bg-light text-end'>NET Pay: <span class='badge bg-success'>{{'₱ ' . number_format($slip->net_pay, 2)}}</span></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class=" d-print-none">
                                <div class="float-end">
                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection