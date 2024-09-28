@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">My Payslips</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">My Paylips</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <input id="" name="" type="text" class="form-control bg-light" value='{{Auth()->user()->company->co_name}}' readonly>
                                    </div>
                                    <div>
                                        <a href="{{route('payroll.slips.employee', ['id' => Auth()->user()->id])}}" class="btn btn-light"><i class="mdi mdi-refresh"></i></a>
                                    </div>
                                </div>
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Basic Salary</th>
                                            <th scope="col">Overtime Hours</th>
                                            <th scope="col">Overtime Pay</th>
                                            <th scope="col">Deductions</th>
                                            <th scope="col">NET Pay</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Run ID</th>
                                            <th scope="col">Run Date</th>
                                            <th scope='col'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($slips as $slip)
                                        <tr>
                                            <td>
                                                @if($slip->user->userPI->photo)
                                                <img src="{{asset('storage/' . $slip->user->userPI->photo)}}" class="avatar-xs me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none rounded-circle" alt="img">
                                                @else
                                                <div class="avatar-xs me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-16">
                                                        {{$slip->user->fullname()[0]}}
                                                    </span>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                {{$slip->user->fullname()}}
                                            </td>
                                            <td>
                                                ₱ {{$slip->basic_salary}}
                                            </td>
                                            <td>
                                                {{$slip->overtime_hours}}
                                            </td>
                                            <td>
                                                {{$slip->overtime_pay}}
                                            </td>
                                            <td>
                                                ₱ {{$slip->deductions}}
                                            </td>
                                            <td>
                                                ₱ {{$slip->net_pay}}
                                            </td>
                                            <td>
                                                <span class='badge {{$slip->badge()}}'>{{$slip->status}}</span>
                                            </td>
                                            <td>
                                                # {{$slip->payroll_run_id}}
                                            </td>
                                            <td>
                                                {{\Carbon\Carbon::parse($slip->run->run_date)->format('F j, Y')}}
                                            </td>
                                            <td>
                                                <a href="{{route('payroll.slips.view', ['id' => $slip->payslip_id])}}"><i class='mdi mdi-eye font-size-22 text-primary' style='cursor: pointer;'></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan='8' class='text-center'>No Records Found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection