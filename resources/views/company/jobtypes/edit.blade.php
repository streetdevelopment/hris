@extends('layouts.layout')
@section('title', 'HRIS - Edit Job Type')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Edit Job Types</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Job Types</a></li>
                                <li class="breadcrumb-item active">Edit Job Types</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="" id="jobtype-edit">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <card class="card-body">
                                <div class="row mb-2">
                                    <label for="co_id" class="col-form-label col-lg-2">Company</label>
                                    <div class="col-lg-10">
                                        <input id="co_id" name="co_id" type="text" class="form-control bg-light" data-id='{{Auth()->user()->co_id}}' value='{{Auth()->user()->company->co_name}}' readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="jt_id" value="{{$jt->jt_id}}">
                                <div class="row mb-2">
                                    <label for="co_id" class="col-form-label col-lg-2">Job Type Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input id="jt_name" name="jt_name" type="text" class="form-control" value="{{$jt->jt_name}}">
                                        <small class=" text-danger validations" id='jt_name_error'></small>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name='' id="" disabled>
                                    <label class="" for="">
                                        Work on Saturday<span class='text-muted'> <em>Work on saturday in future release</em></span>
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name='' id="" disabled>
                                    <label class="" for="">
                                        Work on Sunday<span class='text-muted'> <em>Work on sunday in future release</em></span>
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="" id="" checked disabled>
                                    <input type="hidden" name="fixed_schedule" value="on">
                                    <label class="" for="">
                                        Fixed Schedule<span class='text-muted'> <em>Flexible schedule in future release</em></span>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="start_time" class="col-form-label col-lg-2">Time In <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input id="start_time" name="start_time" type="time" class="form-control" value="{{$jt->start_time}}">
                                        <small class="text-danger validations" id='start_time_error'></small>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="end_time" class="col-form-label col-lg-2">Time Out <span class="text-danger fs">*</span></label>
                                    <div class="col-lg-10">
                                        <input id="end_time" name="end_time" type="time" class="form-control" value="{{$jt->end_time}}">
                                        <small class="text-danger validations" id='end_time_error'></small>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="pay_rate" class="col-form-label col-lg-2">Pay Rate <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <select name="pay_rate" id="pay_rate" class="form-control">
                                            <option value="">None Selected</option>
                                            <option value="Monthly" {{$jt->pay_rate == 'Monthly' ? 'selected' : ''}}>Monthly</option>
                                            <option value="Semi-Monthly" {{$jt->pay_rate == 'Semi-Monthly' ? 'selected' : ''}}>Semi-Monthly</option>
                                            <option value="Hourly" {{$jt->pay_rate == 'Hourly' ? 'selected' : ''}}>Hourly</option>
                                        </select>
                                        <small class="text-danger validations" id='pay_rate_error'></small>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="input-status" class="col-form-label col-lg-2">Status</label>
                                    <div class="col-lg-10">
                                        <select name="status" id="input-status" class="form-control">
                                            <option value="">None Selected</option>
                                            <option value="Active" {{$jt->status == 'Active' ? 'selected' : ''}}>Active</option>
                                            <option value="Inactive" {{$jt->status == 'Inactive' ? 'selected' : ''}}>Inactive</option>
                                            <option value="Pending" {{$jt->status == 'Pending' ? 'selected' : ''}}>Pending</option>
                                        </select>
                                        <small class="text-danger validations" id='status_error'></small>
                                    </div>
                                </div>
                                <div class='mt-2 d-flex justify-content-end'>
                                    <button type='submit' class="btn btn-sm btn-primary px-4 ms-1">Save</button>
                                </div>
                            </card>
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

    function removeValidations() {
        $('.validations').text('')
    }
    $('#fixed_schedule').change(function() {
        if ($(this).prop('checked')) {
            $('#start_time').removeClass('bg-light').attr('disabled', false)
            $('#end_time').removeClass('bg-light').attr('disabled', false)
            $('.fs').show()
        } else {
            $('#start_time').addClass('bg-light').attr('disabled', true)
            $('#end_time').addClass('bg-light').attr('disabled', true)
            $('.fs').hide()
        }
    });
    $('#jobtype-edit').submit(function(e) {
        e.preventDefault()
        var formData = $(this).serialize()
        $.ajax({
            url: '{{route("company.jobtypes.edit.submit")}}',
            data: formData,
            success: (response) => {
                removeValidations()
                if (response.msgs) {
                    for (let key in response.msgs) {
                        $(`#${key}_error`).text('This field is required')
                    }
                } else {
                    Toast.fire({
                        title: `${response.jt_name} job type was added successfully`,
                        icon: 'success'
                    })
                    $('#jobtype-create')[0].reset()
                }
            },
            error: (error) => {
                Toast.fire({
                    title: 'Something went wrong.',
                    icon: 'warning'
                })
            }
        })
    })
</script>
@endsection