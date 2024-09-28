@extends('layouts.layout')
@section('title', 'HRIS - Employees')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Employees</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Employees</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card job-filter">
                        <div class="card-body">
                            <form action="{{ route('profiling.employees.index') }}" id='dep-search-form' method="GET">
                                <div class="row g-3">
                                    <div class="col-xxl-4 col-lg-4">
                                        <div class="position-relative">
                                            <input value="{{$employee_search}}" type="text" class="form-control" id="employee_search" autocomplete="off" name="employee_search" placeholder="Search employee name or id">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-2 col-lg-4">
                                        <div class="position-relative">
                                            <select class="form-control" name="department_filter" id="department_filter">
                                                <option value="">Filter by Department</option>
                                                @foreach($departments as $department)
                                                <option value="{{$department->dep_id}}" {{$department_filter == $department->dep_id ? 'selected' : ''}}>{{$department->dep_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-2 col-lg-4">
                                        <div class="position-relative">
                                            <select class="form-control" name="sex_filter" id="sex_filter">
                                                <option value="">Filter by Sex</option>
                                                <option value="Male" {{$sex_filter == "Male" ? 'selected' : ''}}>Male</option>
                                                <option value="Female" {{$sex_filter == "Female" ? 'selected' : ''}}>Female</option>
                                                <option value="Non-Binary" {{$sex_filter == "Non-Binary" ? 'selected' : ''}}>Non-Binary</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-xxl-2 col-lg-4">
                                        <div class="position-relative">
                                            <select class="form-control" name="status_filter" id="status_filter">
                                                <option value="">Filter by Status</option>
                                                <option value="Active" {{$status_filter == "Active" ? 'selected' : ''}}>Active</option>
                                                <option value="Inactive" {{$status_filter == "Inactive" ? 'selected' : ''}}>Inactive</option>
                                                <option value="On Leave" {{$status_filter == "On Leave" ? 'selected' : ''}}>On Leave</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-xxl-2 col-lg-6">
                                        <div class="position-relative h-100 hstack gap-3">
                                            <button type="submit" class="btn btn-primary h-100"><i class="bx bx-search-alt align-middle"></i> Search</button>
                                            <a href="{{route('profiling.employees.create')}}" class="btn btn-primary h-100"><i class="bx bx-plus align-middle"></i> Add</a>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Email Address</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">Date Hired</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $emp)
                                        <tr>
                                            <td>
                                                @if($emp->userPI->photo)
                                                <div>
                                                    <img class="rounded-circle avatar-xs" src="{{asset('storage/' . $emp->userPI->photo)}}" alt="">
                                                </div>
                                                @else
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle">
                                                        {{$emp->fullname()[0]}}
                                                    </span>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <h5 class="font-size-14 mb-1"><a href="{{route('company.departments.employee.view', ['id' => $emp->id])}}" class="text-dark">{{$emp->fullname()}}</a></h5>
                                                <p class="text-muted mb-0">{{$emp->userPSI->position->title}}</p>
                                            </td>
                                            <td>{{$emp->userPSI->position->department->dep_name}}</td>
                                            <td>{{$emp->userCI->email_address}}</td>
                                            <td>{{$emp->userCI->contact_number}}</td>
                                            <td>{{\Carbon\Carbon::parse($emp->userPSI->date_hired)->format('F j, Y')}}</td>
                                            <td><span class="badge {{$emp->userPSI->badge()}}">{{$emp->userPSI->status}}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="pagination pagination-rounded justify-content-center mt-4">
                                        <li class="page-item disabled">
                                            <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">1</a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="javascript: void(0);" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">4</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link">5</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                        </li>
                                    </ul>
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