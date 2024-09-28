@extends('layouts.layout')
@section('title', 'HRIS - Document Types')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Document Types</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{Auth()->user()->company->co_name}}</a></li>
                            <li class="breadcrumb-item active">Document Types</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="row mb-3">
                                <div class="col-xl-5 col-sm-6">
                                    <div class="mt-2">
                                        <h6>Company Document Types</h6>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-sm-6">
                                    <form class="mt-4 mt-sm-0 float-sm-end d-flex align-items-center">
                                        <div class="search-box mb-2 me-2">
                                            <div class="position-relative">
                                                <input type="text" class="form-control bg-light border-light rounded" placeholder="Search...">
                                                <i class="bx bx-search-alt search-icon"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div>
                                    <div class="row" id='dt-list'>
                                        @include('company.documents.list')
                                    </div>
                                    <!-- end row -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <form action="" id='dt-create'>
                        @csrf
                        <div class="card-body">
                            <label for="co_id" class="col-form-label">Company</label>
                            <input type="text" class="form-control bg-light" value='{{Auth()->user()->company->co_name}}' disabled>
                            <input id="co_id" name="co_id" type="text" class="form-control bg-light" value='{{Auth()->user()->company->co_id}}' hidden>
                            <label for="dt_name" class="col-form-label">Document Name</label>
                            <div class="mb-3">
                                <input id="dt_name" name="dt_name" type="text" class="form-control" placeholder="Document name">
                                <small class="text-danger validations" id='dt_name_error'></small>
                            </div>
                            <button class="btn btn-light w-100" type="submit">
                                <i class="mdi mdi-plus me-1"></i> Create New
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('more_scripts')
<script>
    $(document).ready(function() {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function renderList() {
            $.ajax({
                url: '{{route("company.documents.reload")}}',
                data: '',
                success: (html) => {
                    $('#dt-list').html(html)
                    attachListeners()
                },
                error: (error) => {
                    Toast.fire({
                        title: "Something went wrong",
                        icon: 'danger'
                    })
                }
            })
        }

        function removeValidations() {
            $('.validations').text('')
        }

        function attachListeners() {
            $('.remove-btn').each(function() {
                $(this).click(function() {
                    Swal.fire({
                        title: `Do you intend to delete this document type?`,
                        icon: 'warning',
                        showDenyButton: 'true',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{route("company.documents.delete")}}',
                                data: {
                                    'id': $(this).attr('data-id')
                                },
                                success: (response) => {
                                    Toast.fire({
                                        title: `${response.name} was deleted successfully`,
                                        icon: 'success'
                                    })
                                    renderList()
                                },
                                error: (error) => {
                                    Toast.fire({
                                        title: 'Something went wrong',
                                        icon: 'danger'
                                    })
                                }
                            })
                        }
                    });

                });
            });
            $('.rename-btn').click(function() {
                $.ajax({
                    url: "{{route('company.documents.find')}}",
                    data: {
                        'id': $(this).attr('data-id')
                    },
                    success: (response) => {
                        $('#modal_dt_name').val(response.target.dt_name);
                        $('#modal_dt_id').val(response.target.dt_id);
                    },
                    error: (error) => {
                        Toast.fire({
                            title: 'Something went wrong',
                            icon: 'danger'
                        })
                    }
                })
            })
        }
        attachListeners()
        $('#dt-create').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize()
            $.ajax({
                url: '{{route("company.documents.submit")}}',
                data: formData,
                type: 'post',
                success: (response) => {
                    removeValidations()
                    Toast.fire({
                        title: `${response.name} was added successfully`,
                        icon: 'success'
                    })
                    $('#dt-create')[0].reset()
                    renderList()
                },
                error: (error) => {
                    removeValidations()
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}_error`).text('This field is required')
                    }
                }
            })
        });
        $('#dt-edit').submit(function(e) {
            e.preventDefault()
            var formData = $(this).serialize()
            $.ajax({
                url: "{{route('company.documents.edit')}}",
                data: formData,
                success: (response) => {
                    removeValidations()
                    Toast.fire({
                        title: `${response.name} was added successfully`,
                        icon: 'success'
                    })
                    renderList()
                    $('#edit-modal').modal('toggle');
                },
                error: (error) => {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}_error`).text('This field is required')
                    }
                }
            })
        })
    });
</script>
@endsection