@extends('layouts.layout')
@section('title' ,'Departments - Create')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="containter-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Create Department</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('company.departments.index') }}">Departments</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="" id="department-create">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label for="co_id" class="col-form-label col-lg-2">Company</label>
                                    <div class="col-lg-10">
                                        <input id="co_id" name="co_id" type="text" class="form-control bg-light" data-id='{{Auth()->user()->company->co_id}}' value='{{Auth()->user()->company->co_name}}' readonly>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="dep_name" class="col-form-label col-lg-2">Department Name <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input id="dep_name" name="dep_name" type="text" class="form-control" placeholder="Enter Department Name...">
                                        <small class="text-danger validationError" id='dep_name_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="location" class="col-form-label col-lg-2">Location</label>
                                    <div class="col-lg-10">
                                        <input id="location" name="location" type="text" class="form-control" placeholder="Enter Location...">
                                        <small class="text-danger validationError" id='location_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="phone_number" class="col-form-label col-lg-2">Phone Number <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter Phone Number...">
                                        <small class="text-danger validationError" id='phone_number_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="email_address" class="col-form-label col-lg-2">Email Address <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="email" id="email_address" name="email_address" class="form-control" placeholder="Enter Email Address...">
                                        <small class="text-danger validationError" id='email_address_error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="status" class="col-form-label col-lg-2">Status</label>
                                    <div class="col-lg-10">
                                        <select name="status" id="dep_status" class='form-select'>
                                            <option value="Active" selected>Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                        <small class="text-danger validationError" id='status_error'></small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mb-4">
                                <button type="submit" class='btn btn-sm btn-primary px-5 py-1'>Submit</button>
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
        $('#department-create').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            formData += `&co_id_val=${$('#co_id').attr('data-id')}`
            $.ajax({
                url: '{{route("company.departments.new")}}',
                data: formData,
                contentType: false,
                processData: false,
                success: (response) => {
                    removeValidations()
                    Toast.fire({
                        title: `${response.name} was created successfully`,
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
                                $('#dep_name').val() === '' ? $(`#${key}_error`).text('This field is required') : $(`#${key}_error`).text('This field cannot contain the word "department"')
                            } else {
                                $(`#${key}_error`).text('This field is required')
                            }
                        }
                    } else {
                        Swal.fire({
                            title: 'Something went wrong.',
                            icon: 'warning'
                        })
                    }
                }
            })
        })
    })
</script>
@endsection