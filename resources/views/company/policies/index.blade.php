@extends('layouts.layout')
@section('title', 'HRIS - Attendance Policies')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid position-relative">
            @if($setup === 0)
            <div aria-live="polite" aria-atomic="true" class="position-absolute z-2" style='top:0%; right: 0%;'>
                <div class="toast fade show border-danger" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body">
                        <span class='font-size-11'>You have yet to setup your company's attendance policy. <br>
                            Please fill in the fields below.</span>
                        <div class="mt-2 pt-2 border-top">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="toast">Okay</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Attendance Policies</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Attendance Policies</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row {{Auth()->user()->role == 'employee' ? 'pe-none' : ''}}">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <label for="co_id" class="col-form-label col-lg-2">Company</label>
                                <div class="col-lg-10 mb-4">
                                    <input id="co_id" name="co_id" type="text" class="form-control bg-light" data-id=' {{Auth()->user()->co_id}}' value='{{Auth()->user()->company->co_name}}' readonly>
                                </div>
                                @if(Auth()->user()->role == 'employee')
                                <h4 class='text-center'>Read Only</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <form action="" id='policy'>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="mb-sm-0 font-size-18">Time in/out Policies</h4>
                                        <div class="row mt-2 mb-1 d-flex flex-column">
                                            <label for="grace_period" class="col-form-label">Grace Period (in minutes) for Late Time In (for fix pay only)</label>
                                            <div class="col-lg-12">
                                                <input id="grace_period" name="grace_period" type="number" class="form-control" value="{{$setup === 1 ? $policies->grace_period : ''}}" prev="{{$setup === 1 ? $policies->grace_period : ''}}">
                                                <small class="text-danger validations" id='grace_period_error'></small>
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-3">
                                            <div class="col-7">
                                                <label for="grace_period" class="col-form-label">Late Deduction (for fix pay only)</label>
                                                <div class="col-lg-12">
                                                    <input id="grace_period" name="grace_period" type="time" class="form-control" value="{{$setup === 1 ? $policies->grace_period : ''}}" prev="{{$setup === 1 ? $policies->grace_period : ''}}">
                                                    <small class="text-danger validations" id='grace_period_error'></small>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <label for="late_deduction" class="col-form-label">% Deduction (per hour)</label>
                                                <div class="col-lg-12">
                                                    <input id="late_deduction" name="late_deduction" type="number" step='0' class="form-control" value="{{$setup === 1 ? $policies->late_deduction : ''}}" prev="{{$setup === 1 ? $policies->late_deduction : ''}}" placeholder="Deduction">
                                                    <small class="text-danger validations" id='late_deduction_error'></small>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="form-check mt-3 mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_camera" id="enable_camera" prev="{{$setup === 1 ? ($policies->enable_camera === 1 ? 'true' : 'false') : 'false'}}" {{$setup === 1 ? ($policies->enable_camera === 1 ? 'checked' : '') : ''}}>
                                            <label class="form-check-label" for="enable_camera">
                                                Enable camera for time in/out
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_gps" id="enable_gps" disabled>
                                            <label class="form-check-label" for="enable_gps">
                                                Enable GPS for current location during time in/out
                                            </label>
                                            <span class='text-dark'> <em>Available in future releases</em></span>
                                        </div>
                                        <!-- <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_auto_time_out" id="enable_auto_time_out" >
                                            <label class="form-check-label" for="enable_auto_time_out">
                                                Enable auto time out after work shift
                                            </label>
                                        </div> -->
                                    </div>
                                    <div class="col-6">
                                        <h4 class="mb-sm-0 font-size-18">Leave, overtime, and other policies</h4>
                                        <div class="row mt-2 mb-3 d-flex flex-column">
                                            <label for="yearly_leave_credit" class="col-form-label">Yearly Leave Credit</label>
                                            <div class="col-lg-12">
                                                <input id="yearly_leave_credit" name="yearly_leave_credit" type="number" step='0' class="form-control" placeholder="Enter the company's yearly leave credit" value="{{$setup === 1 ? $policies->yearly_leave_credit : ''}}" prev="{{$setup === 1 ? $policies->yearly_leave_credit : ''}}">
                                                <small class="text-danger validations" id='yearly_leave_credit_error'></small>
                                            </div>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_overtime" id="enable_overtime" prev="{{$setup === 1 ? ($policies->enable_overtime === 1 ? 'true' : 'false') : 'false'}}" {{$setup === 1 ? ($policies->enable_overtime === 1 ? 'checked' : '') : ''}}>
                                            <label class="form-check-label" for="enable_overtime">
                                                Enable overtime requests
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="enable_time_correction_request" disabled>
                                            <label class="form-check-label" for="enable_time_correction_request">
                                                Enable time correction requests
                                            </label>
                                            <span class='text-dark'> <em>Available in future releases</em></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" id='submit-btn' class="btn btn-sm btn-secondary" disabled>Save</button>
                                        </div>
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
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            function btn(mode) {
                if (mode === 1) {
                    $('#submit-btn').removeClass('btn-secondary').addClass('btn-primary').attr('disabled', false);
                } else {
                    $('#submit-btn').addClass('btn-secondary').removeClass('btn-primary').attr('disabled', true);
                }
            }

            function form() {
                let changes = 0;
                $(`#policy input`).each(function() {
                    if ($(this).attr('type') === 'checkbox') {
                        if ($(this).prop('checked') !== ($(this).attr('prev') === 'true')) {
                            changes += 1;
                        }
                    } else {
                        if ($(this).val() !== $(this).attr('prev')) {
                            changes += 1;
                        }
                    }
                });
                return changes;
            }

            function removeValidations() {
                $('.validations').text('')
            }

            function updatePrev() {
                $(`#policy input`).each(function() {
                    if ($(this).attr('type') === 'checkbox') {
                        $(this).attr('prev', $(this).prop('checked'));
                    } else {
                        $(this).attr('prev', $(this).val());
                    }
                });
                btn(0)
            }

            $(document).ready(function() {
                $('#policy input').change(function() {
                    form() > 0 ? btn(1) : btn(0);
                });
            });

            $('#policy').submit(function(e) {
                e.preventDefault()
                var formData = $(this).serialize()
                $.ajax({
                    url: '{{route("company.policies.submit")}}',
                    data: formData,
                    success: (response) => {
                        Toast.fire({
                            title: `Your attendance policies has been ${response.verb}!`,
                            icon: 'success'
                        })
                        removeValidations()
                        updatePrev()
                    },
                    error: (error) => {
                        removeValidations()
                        if (error.responseJSON.msgs) {
                            for (let key in error.responseJSON.msgs) {
                                $(`#${key}_error`).text('This field is required.')
                            }
                        } else {
                            Toast.fire({
                                title: 'Something went wrong',
                                icon: 'warning'
                            })
                        }
                    }
                })
            })
        </script>
        @endsection