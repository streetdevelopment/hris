@extends('layouts.layout')
@section('title', 'HRIS - View Overtime Request')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">View Overtime Request</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Overtime Requests</li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice-title">
                                <h4 class="float-end font-size-16">Request # {{$application->ot_req_id}}</h4>
                                <div class="auth-logo mb-4">
                                    <img src="{{asset('assets/images/logos/HRIS.png')}}" alt="logo" class="auth-logo-dark" height="20">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <address>
                                        <strong>From:</strong><br>
                                        {{$application->user->fullname()}}<br>
                                    </address>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <address class="mt-2 mt-sm-0">
                                        <strong>To:</strong><br>
                                        {{$application->approver->fullname()}} (Approver)<br>
                                        {{$application->sApprover->fullname()}} (Sub-approver)<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mt-3">
                                    <address>
                                        <strong>Applying for:</strong><br>
                                        Overtime<br>
                                    </address>
                                </div>
                                <div class="col-sm-6 mt-3 text-sm-end">
                                    <address>
                                        <strong>Starting from:</strong><br>
                                        {{\Carbon\Carbon::parse($application->start)->format('F j, Y | g:i A')}}<br> <strong>To:</strong><br>
                                        {{\Carbon\Carbon::parse($application->end)->format('F j, Y | g:i A')}}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mt-3">
                                    <address>
                                        <strong>Message:</strong><br>
                                        {{$application->reason}}
                                    </address>
                                </div>
                            </div>
                            <div class="d-print-none">
                                <div class="float-end">
                                    @if($application->status == 'Approved')
                                    <h4 class='text-success'>Approved!</h4>
                                    @elseif($application->status == 'Rejected')
                                    <h4 class='text-danger'>Dropped</h4>
                                    @elseif(Auth()->user()->id === $application->approver_id || Auth()->user()->id === $application->s_approver_id)
                                    <a id='approve-btn' data-id='{{$application->ot_req_id}}' class="btn btn-primary w-md waves-effect waves-light me-2">Approve</a>
                                    <a id='reject-btn' data-id='{{$application->ot_req_id}}' class="btn btn-danger w-md waves-effect waves-light">Drop</a>
                                    @else
                                    <h4 class='text-danger'>You are not permitted to approve this application.</h4>
                                    @endif
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
@section('more_scripts')
<script>
    $('#approve-btn').click(function() {
        let dataId = $(this).attr('data-id');

        Swal.fire({
            title: 'Do you intend to approve this leave application?',
            icon: 'question',
            showConfirmButton: true,
            showDenyButton: true,
            denyButtonText: 'No',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('attendance.applications.approve') }}",
                    data: {
                        'id': dataId,
                        'ot': true
                    },
                    success: (response) => {
                        location.reload();
                    },
                    error: (error) => {
                        console.error('There was an error approving the application:', error);
                    }
                });
            }
        });
    });
    $('#reject-btn').click(function() {
        let dataId = $(this).attr('data-id');

        Swal.fire({
            title: 'Do you intend to approve drop this application?',
            icon: 'question',
            showConfirmButton: true,
            showDenyButton: true,
            denyButtonText: 'No',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('attendance.applications.reject') }}",
                    data: {
                        'id': dataId,
                        'ot': true
                    },
                    success: (response) => {
                        location.reload();
                    },
                    error: (error) => {
                        console.error('There was an error rejecting the application:', error);
                    }
                });
            }
        });
    });
</script>
@endsection