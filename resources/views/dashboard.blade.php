@extends('layouts.layout')
@section('title', 'HRIS - Dashboard')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @if (session('msg'))
            <div class='container d-flex justify-content-end position-absolute' style="z-index: 2;">
                <div aria-live="polite" aria-atomic="true">
                    <div class="toast fade show align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                You have already setup your admin account profile.
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{auth()->user()->company->co_name}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">{{auth()->user()->company->co_name}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            @if(Auth()->user()->company->setup('company')->status === 1)
            @php $employee = Auth()->user(); @endphp
            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-3">
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p>{{$employee->fullname()}}</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        @if($employee->userPI->photo)
                                        <img src="{{asset('storage/' . $employee->userPI->photo)}}" class="avatar-md me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none rounded-circle" alt="img">
                                        @else
                                        <div class="avatar-sm me-3 mx-lg-auto mb-3 mt-1 float-start float-lg-none">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-16">
                                                {{$employee->fullname()[0]}}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    <h5 class="font-size-15 text-truncate">{{$employee->userPSI->position->title}}</h5>
                                    <p class="text-muted mb-0 text-truncate">{{$employee->userPSI->position->department->dep_name}} Department</p>
                                </div>

                                <div class="col-sm-5">
                                    <div class="pt-3">

                                        <div class="row">
                                            <div class="col-12">
                                                <p class="text-muted font-size-10 mb-2"><i class='mdi mdi-email text-primary'></i><br> {{$employee->userCI->email_address}}</p>
                                                <p class="text-muted font-size-10 mb-0"><i class='bx bx-phone text-primary'></i><br> {{$employee->userCI->contact_number}}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{route('profiling.profile.index')}}" class="btn btn-primary waves-effect waves-light btn-sm">View
                                                Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Company Setup</h4>
                    <p class='mb-5'>To access the dashboard and other functionalities, finish setting up your company by completing the tasks below.<br>It is recommended that you complete them from top to bottom.</p>
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td style="width: 40px;">
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="jobtype-setup" style='pointer-events:none;' {{ Auth()->user()->company->setup('jobtype')->checked }}>
                                            <label class="form-check-label" for="jobtype-setup"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0"><a href="{{route('company.jobtypes.index')}}" class="text-dark setup">Add job types that applies for your company</a></h5>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge rounded-pill font-size-11 {{Auth()->user()->company->setup('jobtype')->badge}}">{{Auth()->user()->company->setup('jobtype')->status}}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="document-setup" style='pointer-events:none;' {{ Auth()->user()->company->setup('documents')->checked }}>
                                            <label class="form-check-label" for="document-setup"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0"><a href="{{route('company.documents.index')}}" class="text-dark">Add document types required for employers to upload</a></h5>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge rounded-pill badge-soft-secondary font-size-11 {{ Auth()->user()->company->setup('documents')->badge }}">{{ Auth()->user()->company->setup('documents')->status }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 40px;">
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="jobtype-setup" style='pointer-events:none;' {{ Auth()->user()->company->setup('leavetypes')->checked }}>
                                            <label class="form-check-label" for="jobtype-setup"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0"><a href="{{route('company.leavetypes.index')}}" class="text-dark setup">Add the type of leave applicable for your company</a></h5>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge rounded-pill font-size-11 {{Auth()->user()->company->setup('leavetypes')->badge}}">{{Auth()->user()->company->setup('leavetypes')->status}}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="policy-setup" style='pointer-events:none;' {{ Auth()->user()->company->setup('policies')->checked }}>
                                            <label class="form-check-label" for="policy-setup"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0"><a href="{{route('company.policies.index')}}" class="text-dark">Setup your company's attendance policies</a></h5>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge rounded-pill badge-soft-secondary font-size-11 {{Auth()->user()->company->setup('policies')->badge}}">{{Auth()->user()->company->setup('policies')->status}}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="department-setup" style='pointer-events:none;' {{ Auth()->user()->company->setup('departments')->checked }}>
                                            <label class="form-check-label" for="department-setup"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0"><a href="{{route('company.departments.index')}}" class="text-dark setup">Add your company's departments</a></h5>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge rounded-pill badge-soft-secondary font-size-11 {{Auth()->user()->company->setup('departments')->badge}}">{{Auth()->user()->company->setup('departments')->status}}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="department-setup" style='pointer-events:none;' {{ Auth()->user()->company->setup('profile')->checked }}>
                                            <label class="form-check-label" for="department-setup"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0"><a href="{{route('registration.admin.initial_profiling', ['user_id' => Auth()->user()->id])}}" class="text-dark setup">Complete your admin account profile</a></h5>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge rounded-pill badge-soft-secondary font-size-11 {{Auth()->user()->company->setup('profile')->badge}}">{{Auth()->user()->company->setup('profile')->status}}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection