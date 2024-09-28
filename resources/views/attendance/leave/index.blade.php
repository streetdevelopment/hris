@extends('layouts.layout')
@section('title', 'HRIS - Apply for Leave')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Apply for Leave</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Leave Application</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="" id='leave-form' enctype="multipart/form-data">
                @csrf
                @if(Auth()->user()->leave_credit > 1)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label for="user_id" class="col-form-label col-lg-2">Employee</label>
                                    <div class="col-lg-10">
                                        <input id="" name="" type="text" class="form-control bg-light" value='{{Auth()->user()->fullname()}}' readonly>
                                        <input id="user_id" name="user_id" type="text" value='{{Auth()->user()->id}}' hidden>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="start_date" class="col-form-label col-lg-2">Start Date</label>
                                    <div class="col-lg-10">
                                        <input id="start_date" name="start_date" type="date" class="form-control">
                                        <small class="validations text-danger" id="start_date-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="end_date" class="col-form-label col-lg-2">End Date</label>
                                    <div class="col-lg-10">
                                        <input id="end_date" name="end_date" type="date" class="form-control">
                                        <small class="validations text-danger" id="end_date-error"></small>
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
                                <div class="row mb-4">
                                    <label for="message" class="col-form-label col-lg-2">Message</label>
                                    <div class="col-lg-10">
                                        <textarea name="message" id="message" class="form-control" placeholder="Write a message" rows='4'></textarea>
                                        <small class="validations text-danger" id="message-error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <label for="lt_id" class="col-form-label col-lg-2">Leave Type</label>
                                    <div class="col-lg-10">
                                        <select name="lt_id" id="lt_id" class='form-control'>
                                            <option value="">None Selected</option>
                                            @forelse($lts as $lt)
                                            <option value="{{$lt->lt_id}}">{{$lt->name}}</option>
                                            @empty
                                            <option value="">No Leave Types Found</option>
                                            @endforelse
                                        </select>
                                        <small class="validations text-danger" id="lt_id-error"></small>
                                    </div>
                                </div>
                                <div id="req-list">

                                </div>
                                <div class='text-center'><button type='submit' class="btn btn-primary btn-sm">Submit Application</button></div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body bg-light text-center text-warning">
                                <h4>You don't have enough leave credits to apply for a leave.</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>
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

    function clearValidations() {
        $('.validations').text('')
    }
    $('#lt_id').change(function() {
        $.ajax({
            url: '{{route("attendance.leave.requirements")}}',
            data: {
                'id': $(this).val()
            },
            success: (response) => {
                let html = ''
                response.requirements.forEach(function(el) {
                    html += `
                       <div class="row mb-4">
                            <label for="${el.dt.dt_name}_input" class="col-form-label col-lg-2">${el.dt.dt_name}</label>
                            <div class="col-lg-10">
                                <input type="file" class='form-control' id='${el.dt.dt_name}_input' name='${el.dt.dt_id}'>
                                <small class="validations text-danger" id="${el.dt.dt_id}-error"></small>
                            </div>
                        </div>
                    `
                })
                $('#req-list').html(html)
            },
            error: (error) => {

            }
        })
    })
    $('#leave-form').submit(function(e) {
        e.preventDefault()
        var formData = new FormData(this);
        $.ajax({
            url: '{{route("attendance.leave.submit")}}',
            data: formData,
            contentType: false,
            processData: false,
            type: 'post',
            success: (response) => {
                clearValidations()
                Toast.fire({
                    title: 'Your application was successfully submitted',
                    icon: 'success'
                })
                $(this)[0].reset()
            },
            error: (error) => {
                if (error.responseJSON.notEnoughCredit) {
                    Toast.fire({
                        title: 'Not enough leave credit points',
                        text: 'Adjust leave duration or directly request for credit increase.',
                        showCloseButton: true,
                        closeButtonAriaLabel: 'Close this alert',
                        timer: null
                    })
                } else {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}-error`).text('This field is required')
                    }
                }
                clearValidations()
            }
        })
    })
</script>
@endsection