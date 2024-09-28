@extends('layouts.layout')
@section('title', 'HRIS - View Leave Application')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">View Leave Application</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Leave Applications</li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice-title">
                                <h4 class="float-end font-size-16">Application # {{$application->lv_req_id}}</h4>
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
                                        {{$application->lt->name}}<br>
                                    </address>
                                </div>
                                <div class="col-sm-6 mt-3 text-sm-end">
                                    <address>
                                        <strong>Starting from:</strong><br>
                                        {{\Carbon\Carbon::parse($application->start_date)->format('F j, Y')}}<br> <strong>To:</strong><br>
                                        {{\Carbon\Carbon::parse($application->end_date)->format('F j, Y')}}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mt-3">
                                    <address>
                                        <strong>Message:</strong><br>
                                        {{$application->message}}
                                    </address>
                                </div>
                            </div>
                            <div class="py-2 mt-3">
                                <h3 class="font-size-15 fw-bold">Submitted Requirements</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px;">No.</th>
                                            <th>Document</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($application->leaveDocuments() as $doc)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$doc[0]->details->dt_name}}</td>
                                            <td class="text-end">
                                                <a class='view-btn' data-id='{{$doc[0]->pd_id}}' style="cursor: pointer;">
                                                    <i class='mdi mdi-eye font-size-20 text-primary me-2'></i>
                                                </a>
                                                <a class='download-btn' data-id='{{$doc[0]->pd_id}}' style="cursor: pointer;">
                                                    <i class='bx bx-download font-size-20 text-danger'></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-print-none">
                                <div class="float-end">
                                    @if($application->status == 'Approved')
                                    <h4 class='text-success'>Approved!</h4>
                                    @elseif($application->status == 'Rejected')
                                    <h4 class='text-danger'>Dropped</h4>
                                    @elseif(Auth()->user()->id === $application->approver_id || Auth()->user()->id === $application->s_approver_id)
                                    <a id='approve-btn' data-id='{{$application->lv_req_id}}' class="btn btn-primary w-md waves-effect waves-light me-2">Approve</a>
                                    <a id='reject-btn' data-id='{{$application->lv_req_id}}' class="btn btn-danger w-md waves-effect waves-light">Drop</a>
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
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('#approve-btn').click(function() {
        let dataId = $(this).attr('data-id');
        let button = $(this); // Save reference to the button

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
                        'id': dataId
                    },
                    success: (response) => {
                        location.reload();
                    },
                    error: (error) => {
                        if (error.responseJSON.no_credit) {
                            Toast.fire({
                                title: `This employee does not have enough credit for this leave`,
                                icon: 'warning'
                            })
                        }
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
                        'id': dataId
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

    $('.view-btn').click(function() {
        let id = $(this).data('id');
        $.ajax({
            url: '{{ route("attendance.applications.view.document") }}',
            type: 'POST',
            data: {
                'id': id,
                '_token': '{{ csrf_token() }}'
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var url = window.URL.createObjectURL(response);
                window.open(url, '_blank');
            },
            error: function(error) {
                console.error('There was a problem with viewing the document:', error);
            }
        });
    });

    $('.download-btn').click(function() {
        let id = $(this).data('id');
        $.ajax({
            url: '{{ route("attendance.applications.view.download") }}',
            data: {
                'id': id
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: (response, status, xhr) => {
                var url = window.URL.createObjectURL(response);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;

                var contentDisposition = xhr.getResponseHeader('Content-Disposition');
                var fileName = 'downloaded_file.pdf';
                if (contentDisposition) {
                    var matches = /filename="([^"]+)"/.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        fileName = matches[1];
                    }
                }

                a.download = fileName;
                document.body.appendChild(a);
                a.click();

                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            },
            error: (error) => {
                console.error('There was a problem with downloading the document:', error);
            }
        });
    });
</script>
@endsection