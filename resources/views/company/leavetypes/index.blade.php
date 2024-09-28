@extends('layouts.layout')
@section('title', 'HRIS - Types of Leave')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Leave Types</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Type of Leaves</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 justify-content-between">
                        <div>
                            <input type="text" class="form-control bg-light" value=' {{Auth()->user()->company->co_name}}' readonly>
                        </div>
                        <div>
                            <a href="{{route('company.leavetypes.create')}}" class="btn btn-primary">Add New</a>
                            <a href="{{route('company.leavetypes.index')}}" class="btn btn-light ms-1"><i class="mdi mdi-refresh"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Requirements</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $rec)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>{{$rec->name}}</td>
                                    <td>{{$rec->description}}</td>
                                    <td>
                                        @foreach($rec->ltr as $ltr)
                                        <span class="badge bg-info">{{$ltr->dt->dt_name}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <a href="{{route('company.leavetypes.edit', ['id' => $rec->lt_id])}}" class="btn btn-sm btn-soft-info"><i class="mdi mdi-pencil-outline"></i></a>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <a class="del-btn btn btn-sm btn-soft-danger"><i class="mdi mdi-delete-outline"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan='5' class='text-center'>
                                        No Records Found
                                    </td>
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
@endsection
@section('more_scripts')
@if(count($records) > 0)
<script>
    $('.del-btn').click(function() {
        Swal.fire({
            icon: 'warning',
            title: 'Do you intend to delete this leave type?',
            showDenyButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                var deleteUrl = "{{route('company.leavetypes.delete', ['id' => $rec->lt_id])}}";
                window.location.href = deleteUrl
            }
        })
    })
</script>
@endif
@endsection