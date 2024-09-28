@extends('layouts.layout')
@section('title' ,'Departments - Edit')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="containter-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Edit Department</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('company.departments.index') }}">Departments</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('company.departments.index') }}">{{$target->dep_name}}</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="" id="department-edit">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="dep_id" value='{{$target->dep_id}}'>
                                <div class="row mb-4">
                                    <label for="co_id" class="col-form-label col-lg-2">Company</label>
                                    <div class="col-lg-10">
                                        <input id="co_id" name="co_id" type="text" class="form-control bg-light" data-id='{{$target->co_id}}' value='{{$target->company->co_name}}' readonly>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="dep_name" class="col-form-label col-lg-2">Department Name <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input id="dep_name" name="dep_name" type="text" class="form-control" value='{{$target->dep_name}}' placeholder="Enter Department Name...">
                                        <small class="text-danger validationError" id='dep_name_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="location" class="col-form-label col-lg-2">Location</label>
                                    <div class="col-lg-10">
                                        <input id="location" name="location" type="text" class="form-control" value='{{$target->location}}' placeholder="Enter Location...">
                                        <small class="text-danger validationError" id='location_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="phone_number" class="col-form-label col-lg-2">Phone Number <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter Phone Number..." value='{{$target->phone_number}}'>
                                        <small class="text-danger validationError" id='phone_number_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="email_address" class="col-form-label col-lg-2">Email Address <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="email" id="email_address" name="email_address" class="form-control" value="{{$target->email_address}}" placeholder="Enter Email Address...">
                                        <small class="text-danger validationError" id='email_address_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="status" class="col-form-label col-lg-2">Status</label>
                                    <div class="col-lg-10">
                                        <select name="status" id="dep_status" class='form-select'>
                                            <option value="Active" {{$target->status == 'Active' ? 'selected' : ''}}>Active</option>
                                            <option value="Inactive" {{$target->status == 'Inactive' ? 'selected' : ''}}>Inactive</option>
                                            <option value="Pending" {{$target->status == 'Pending' ? 'selected' : ''}}>Pending</option>
                                        </select>
                                        <small class="text-danger validationError" id='status_error'></small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mb-4">
                                <button type="submit" class='btn btn-sm btn-primary px-5 py-1'>Save</button>
                            </div>
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
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    $(document).ready(function() {
        function removeValidations() {
            $('.validationError').each(function() {
                $(this).text('')
            })
        }
        $('#department-edit').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            formData += `&co_id_val=${$('#co_id').attr('data-id')}`
            $.ajax({
                url: '{{route("profiling.employees.edit.submit")}}',
                data: formData,
                contentType: false,
                processData: false,
                type: 'post',
                success: (response) => {
                    removeValidations()
                    Toast.fire({
                        title: `${response.name} was updated successfully`,
                        icon: 'success'
                    })
                    $('#department-create')[0].reset();
                },
                error: (error) => {
                    removeValidations()
                    if (error.responseJSON.validation === true) {
                        for (let key in error.responseJSON.messages) {
                            if (key == 'phone_number') {
                                $(`#${key}_error`).text(error.responseJSON.messages[key])
                            } else if (key == 'dep_name') {
                                $('#dep_name').val() == '' ? $(`#${key}_error`).text(`This field is required`) : $(`#${key}_error`).text(`${$('#dep_name').val()} already exist`)
                            } else {
                                $(`#${key}_error`).text('This field is required')
                            }
                        }
                    } else {
                        Swal.fire({
                            title: error.responseJSON.msg,
                            icon: 'warning'
                        })
                    }
                }
            })
        })
    })
</script>
@endsection