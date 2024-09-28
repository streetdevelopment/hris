@extends('layouts.layout')
@section('title', 'HRIS - Payroll Run')
@section('content')
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
                                        <strong>Company:</strong><br>
                                        {{$run->company->co_name}}
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
                                        <strong>Number of Payslips:</strong><br>
                                        <a href="#payslips-section">{{$run->payslips->count()}}</a><br>
                                    </address>
                                </div>
                                <div class="col-sm-6 mt-3 text-sm-end">
                                    <address>
                                        <strong>Run Date:</strong><br>
                                        {{\Carbon\Carbon::parse($run->run_date)->format('F j, Y')}}<br><br>
                                    </address>
                                </div>
                            </div>
                            <div class="py-2 mt-3">
                                <h3 class="font-size-15 fw-bold">Payroll summary</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Total Gross Pay</th>
                                            <th>Total Deductions</th>
                                            <th>Status</th>
                                            <th class="text-end">Total NET Pay</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>₱ {{number_format($run->total_gross_pay, 2)}}</td>
                                            <td>₱ {{number_format($run->total_deductions, 2)}}</td>
                                            <td>{{$run->status}}</td>
                                            <td class="text-end">₱ {{number_format($run->total_net_pay, 2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-print-none">
                                <div class="float-end">
                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h4 class='text-center' id='payslips-section'>Employee Payslips</h1>
                </div>
                <div class="col-lg-12 mt-3">
                    <div class="row">
                        @foreach($slips as $slip)
                        <div class="col-xl-4 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="text-lg-center">
                                                @if($slip->user->userPI->photo)
                                                <img src="{{asset('storage/' . $slip->user->userPI->photo)}}" class="avatar-sm me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none rounded-circle" alt="img">
                                                @else
                                                <div class="avatar-sm me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-16">
                                                        {{$slip->user->fullname()[0]}}
                                                    </span>
                                                </div>
                                                @endif
                                                <h5 class="mb-1 font-size-15 text-truncate">{{$slip->user->userPSI->position->department->dep_name}}</h5>
                                                <a href="{{route('company.departments.view', ['id' => $slip->user->userPSI->position->department->dep_id])}}" class="text-muted">Department</a>
                                            </div>
                                        </div>

                                        <div class="col-lg-8">
                                            <div>
                                                <a href="{{route('payroll.slips.view', ['id' => $slip->payslip_id])}}" class="d-block text-info mb-2">Payslip #{{$slip->payslip_id}}</a>
                                                <a href="{{route('payroll.slips.view', ['id' => $slip->payslip_id])}}" class="d-block text-dark mb-2">
                                                    <h5 class="text-truncate mb-4 mb-lg-5">{{$slip->user->fullname()}}</h5>
                                                </a>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item me-3">
                                                        <h5 class="font-size-14" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Amount"><i class="bx bx-money me-1 text-muted"></i> ₱ {{number_format($slip->net_pay, 2)}}</h5>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <h5 class="font-size-14" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Due Date"><i class="bx bx-calendar me-1 text-muted"></i> {{\Carbon\Carbon::parse($run->run_date)->format('d M, y')}}</h5>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection