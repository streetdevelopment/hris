@if(isset($accumulatedRecords))
@foreach($accumulatedRecords as $empName => $dates)

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="invoice-title">
                    <div style='display: flex; justify-content: center; gap: 20px; align-items: center; position: absolute; top: 2.5%; left: 50%; transform: translate(-50%, 0);'>
                        <i class='mdi mdi-arrow-left font-size-22'></i>
                        <i class='mdi mdi-arrow-right font-size-22'></i>
                    </div>
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
                            {{$empName}}<br>
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
                            {{$accumulatedRecords[$empName]['user']['psi']->position->department->dep_name}} Department<br>
                        </address>
                    </div>
                </div>
                <div class="py-2 mt-3">
                    <h3 class="font-size-15 fw-bold">Attendance Records</h3>
                </div>
                @foreach($dates as $date => $records)
                @if($date == 'user')
                @else
                <div class="table-responsive table-bordered">
                    <table class="table table-nowrap">
                        <thead>
                            <tr>
                                <th class='bg-light' style='width: 25%;'>Date</th>
                                <th colspan="3" class='bg-light' style='width: 75%;'> {{\Carbon\Carbon::parse($date)->format('F j, Y')}}</th>
                            </tr>
                            <tr>
                                <th style='width: 25%;'>Time In</th>
                                <th style='width: 25%;'>Time Out</th>
                                <th style='width: 25%;'>Status</th>
                                <th class="text-end" style='width: 25%;'>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $totalcount = 0; ?>
                            @foreach($records as $record)
                            @if($record->time_out || Auth()->user()->company->policies->enable_auto_time_out)
                            <tr>
                                <td>{{\Carbon\Carbon::parse($record->time_in)->format('g:i A')}}</td>
                                <td>{{$record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('g:i A') : \Carbon\Carbon::parse($accumulatedRecords[$empName]['user']['psi']->position->JT->end_time)->format('g:i A')}}</td>
                                <td>{{$record->status}}</td>
                                <td class="text-end">{{$record->workedHours()}} Hours</td>
                                @php
                                $totalcount += $record->workedHours()
                                @endphp
                            </tr>
                            @else
                            <tr>
                                <td>{{\Carbon\Carbon::parse($record->time_in)->format('g:i A')}}</td>
                                <td>N/A</td>
                                <td>{{$record->status}}</td>
                                <td class="text-end">{{$record->workedHours()}} Hours</td>
                                @php
                                $totalcount += $record->workedHours()
                                @endphp
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td><b>Total Hours:</b> {{$totalcount}} Hours</td>
                                <td><b>{{$accumulatedRecords[$empName]['user']['psi']->position->jt->pay_rate !== 'Hourly' ? 'Total Deductions' : 'Total Earnings'}} </b><span class="{{$accumulatedRecords[$empName]['user']['psi']->position->jt->pay_rate !== 'Hourly' ? 'text-danger' : 'text-success'}}">{{ceil(7 - $totalcount)}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                @endforeach
                <div class=" d-print-none">
                    <div class="float-end">
                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                        <a href="javascript: void(0);" class="btn btn-primary w-md waves-effect waves-light">Send</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- <p>Record ID: {{ $record->att_rec_id }}</p>
<p>Hours: {{ $record->hours }}</p> -->


@endforeach
@endif