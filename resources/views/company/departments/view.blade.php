@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{$target->dep_name}} Department</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Departments</a></li>
                            <li class="breadcrumb-item active">{{$target->dep_name}}</li>
                        </ol>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fw-semibold">Overview</h5>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="col">Company</th>
                                                <td scope="col">{{$target->company->co_name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Department Name</th>
                                                <td>{{$target->dep_name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Location</th>
                                                <td>{{$target->location}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phone Number</th>
                                                <td>{{$target->phone_number}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email Address</th>
                                                <td>{{$target->email_address}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td><span class='badge {{$target->badge()}}'>{{$target->status}}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="hstack gap-2">
                                    @if(Auth()->user()->role == 'admin')
                                    <a href='{{route("company.departments.edit", ["id" => $target->dep_id])}}' class="btn btn-soft-primary w-100">Edit</a>
                                    @endif
                                    @if($target->status == 'Active')
                                    <button type='button' class="btn btn-soft-success w-100">Email</button>
                                    @elseif($target->status == 'Inactive')
                                    <button type='button' id='del-btn' data-id='{{$target->dep_id}}' class="btn btn-soft-danger w-100">Delete</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-semibold">Employees</h5>
                                        <ul class="list-unstyled hstack gap-2 mb-0">
                                            <li>
                                                <i class="bx bx-building-house"></i> <span class="text-muted">{{$target->positions->count() > 1 ? $target->positions->count() . ' are working here' : $target->positions->count() . ' is working here'}}</span>
                                            </li>
                                            <li>
                                                <i class="bx bx-map"></i> <span class="text-muted">{{$target->location}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div>
                                        @if(Auth()->user()->role == 'admin')
                                        <a href='{{route("profiling.employees.create")}}' class="btn btn-md btn-primary">
                                            Add Employee
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($target->positions as $pos)
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="card text-center bg-light">
                                            <div class="card-body">
                                                <div class="avatar-sm mx-auto mb-4">
                                                    @if($pos->PSI->user->userPI->photo)
                                                    <img src="{{ asset('storage/' . $pos->PSI->user->userPI->photo) }}" class="rounded-circle avatar-sm" alt="Test">
                                                    @else
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-16">
                                                        {{$pos->PSI->user->fullname()[0]}}
                                                    </span>
                                                    @endif
                                                </div>
                                                <h5 class="font-size-15 mb-1"><a href="javascript: void(0);" class="text-dark">{{$pos->PSI->user->userPI->first_name}} {{$pos->PSI->user->userPI->last_name}}</a></h5>
                                                <p class="text-muted">{{$pos->title}}</p>

                                                <div>
                                                    <a href="javascript: void(0);" class="badge bg-primary font-size-11 m-1">{{$pos->PSI->user->userCI->contact_number}}</a>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-top">
                                                <div class="contact-links d-flex font-size-20">
                                                    <div class="flex-fill">
                                                        <a href="javascript: void(0);"><i class="bx bx-message-square-dots"></i></a>
                                                    </div>
                                                    <div class="flex-fill">
                                                        <a class="{{Auth()->user()->role == 'employee' ? 'pe-none' : ''}}" href="{{route('company.departments.employee.attendance', ['id' => $pos->PSI->user->id])}}"><i class="bx bx-pie-chart-alt {{Auth()->user()->role == 'employee' ? 'text-secondary' : ''}}"></i></a>
                                                    </div>
                                                    <div class="flex-fill">
                                                        <a class="{{Auth()->user()->role == 'employee' ? 'pe-none' : ''}}" href="{{route('company.departments.employee.view', ['id' => $pos->PSI->user->id])}}"><i class="bx bx-user-circle {{Auth()->user()->role == 'employee' ? 'text-secondary' : ''}}"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div>
        </div>
    </div>
    @endsection
    @section('more_scripts')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $('#del-btn').click(function() {
            Swal.fire({
                title: 'Do you intent to delete {{$target->dep_name}}',
                icon: 'warning',
                showConfirmButton: true,
                showDenyButton: true,
                denyButtonText: 'No',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route("company.departments.delete")}}',
                        data: {
                            'id': $(this).attr('data-id')
                        },
                        success: (response) => {
                            Swal.fire({
                                title: 'Successfully deleted {{$target->dep_name}}',
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonText: 'Yes'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{route("company.departments.index")}}'
                                }
                            })
                        },
                        error: (error) => {
                            if (error.responseJSON.msgs) {
                                Toast.fire({
                                    title: error.responseJSON.msgs,
                                    icon: 'warning'
                                })
                            }
                        }
                    })
                }
            });
        })
    </script>
    @endsection