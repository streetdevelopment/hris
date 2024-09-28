@extends('layouts.layout')
@section('title', 'HRIS - Job Types')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Job Types</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Job Types</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <card class="card-body">
                    <div class="d-flex justify-content-start align-items-center mb-2">
                        <div class='d-flex align-items-end'>
                            <input type="text" class='form-control bg-light' value='{{Auth()->user()->company->co_name}}' name="co_id" id="co_id" readonly>
                        </div>
                        <a href='{{route("company.jobtypes.create")}}' class='btn btn-md ms-1 btn-primary'>Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Fixed Schedule</th>
                                    <th scope="col">Work on Saturday</th>
                                    <th scope="col">Work on Sunday</th>
                                    <th scope="col">Time In Schedule</th>
                                    <th scope="col">Time Out Schedule</th>
                                    <th scope="col">Pay Rate</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jts as $jt)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>{{$jt->jt_name}}</td>
                                    <td><span class="badge {{$jt->getStatus('fs')->badge}}">{{$jt->getStatus('fs')->fs}}</span></td>
                                    <td><span class="badge {{$jt->getStatus('sat')->badge}}">{{$jt->getStatus('sat')->fs}}</span></td>
                                    <td><span class="badge {{$jt->getStatus('sun')->badge}}">{{$jt->getStatus('sun')->fs}}</span></td>
                                    <td>{{$jt->start_time ? \Carbon\Carbon::parse($jt->start_time)->format('g:i A') : 'N/A'}}</td>
                                    <td>{{$jt->end_time ? \Carbon\Carbon::parse($jt->end_time)->format('g:i A') : 'N/A'}}</td>
                                    <td>{{$jt->pay_rate}}</td>
                                    <td><span class="badge {{$jt->getStatus('status')->badge}}">{{$jt->status}}</span></td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <a href="{{route('company.jobtypes.edit', ['id' => $jt->jt_id])}}" class="btn btn-sm btn-soft-info"><i class="mdi mdi-pencil-outline"></i></a>
                                            </li>
                                            <!-- <li data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <a class="delete-btn btn btn-sm btn-soft-danger"><i class="mdi mdi-delete-outline"></i></a>
                                            </li> -->
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">No Records Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </card>
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
    $('.delete-btn').click(function() {
        Swal.fire({
            icon: 'warning',
            title: 'Do you intend to delete this job type?',
            showDenyButton: true,
            showConfirmButton: true,
            denyButtonText: "No",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                @if(isset($jt))
                var deleteUrl = "{{ route('company.jobtypes.delete', ['id' => $jt->jt_id]) }}";
                @else
                var deleteUrl = ""; // or handle the case when $jt is not set
                @endif

                // Use the prepared URL in the AJAX call
                $.ajax({
                    url: deleteUrl,
                    data: '',
                    success: (response) => {
                        window.location.href = "{{ route('company.jobtypes.index') }}"
                    },
                    error: (error) => {
                        Toast.fire({
                            title: `Something went wrong.`,
                            icon: 'warning'
                        })
                    }
                });
            }
        })
    })
</script>
@endsection