@extends('layouts.layout')
@section('title', 'HRIS - Request for Overtime')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Request for Overtime</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Overtime Request</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="" id='overtime-form'>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label for="user_id" class="col-form-label col-lg-2">Employee</label>
                                    <div class="col-lg-10">
                                        <input id="" name="" type="text" class="form-control bg-light" value='{{$user->fullname()}}' readonly>
                                        <input id="user_id" name="user_id" type="text" value='{{$user->id}}' hidden>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="start" class="col-form-label col-lg-2">Start</label>
                                    <div class="col-lg-10">
                                        <input id="start" name="start" type="datetime-local" class="form-control">
                                        <small class="validations text-danger" id="start-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="end" class="col-form-label col-lg-2">End</label>
                                    <div class="col-lg-10">
                                        <input id="end" name="end" type="datetime-local" class="form-control">
                                        <small class="validations text-danger" id="end-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="end" class="col-form-label col-lg-2">Reason</label>
                                    <div class="col-lg-10">
                                        <textarea name="reason" id="reason" class='form-control' placeholder="Provide a brief reason for your overtime" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="approver_id" class="col-form-label col-lg-2">Approver</label>
                                    <div class="col-lg-10">
                                        <select name="approver_id" id="approver_id" class='form-control'>
                                            <option value="">None Selected</option>
                                            @forelse($approvers as $emp)
                                            <option value="{{$emp->id}}">{{$emp->fullname()}} | {{$emp->userPSI->position->department->dep_name}} Department</option>
                                            @empty
                                            <option value="">No Employees Found</option>
                                            @endforelse
                                        </select>
                                        <small class="validations text-danger" id="approver_id-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="s_approver_id" class="col-form-label col-lg-2">Alternative Approver</label>
                                    <div class="col-lg-10">
                                        <select name="s_approver_id" id="s_approver_id" class='form-control'>
                                            <option value="">None Selected</option>
                                            @forelse($approvers as $emp)
                                            <option value="{{$emp->id}}">{{$emp->fullname()}} | {{$emp->userPSI->position->department->dep_name}} Department</option>
                                            @empty
                                            <option value="">No Employees Found</option>
                                            @endforelse
                                        </select>
                                        <small class="validations text-danger" id="s_approver_id-error"></small>
                                    </div>
                                </div>
                                <div class='text-end'><button type='submit' class="btn btn-primary btn-sm">Submit Request</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('more_scripts')
<script>
    function removeValidations() {
        $('.validations').text('')
    }
    $('#overtime-form').submit(function(e) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        e.preventDefault()
        var data = $(this).serialize()
        $.ajax({
            url: '{{route("attendance.request.overtime.submit")}}',
            data: data,
            success: (response) => {
                removeValidations()
                Toast.fire({
                    title: `Your request was sent successfully`,
                    icon: 'success'
                })
                $(this)[0].reset()
            },
            error: (error) => {
                removeValidations()
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}-error`).text('This field is required')
                    }
                } else if (error.responseJSON.inv_time) {
                    Toast.fire({
                        title: `End date and time should not be later than start date and time`,
                        icon: 'warning'
                    })
                } else if (error.responseJSON.inv_apps) {
                    Toast.fire({
                        title: `Primary and secondary approvers should be different`,
                        icon: 'warning'
                    })
                } else {
                    Toast.fire({
                        title: `Something went wrong.`,
                        icon: 'warning'
                    })
                }
            }
        })
    })
</script>
@endsection