@extends('layouts.layout')
@section('title', 'HRIS - Process Payroll')
@section('content')
<div class="main-content">
    <div class="page-content" style='position: relative;'>
        <div class="fluid-container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Process Payroll Run</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Process Payroll</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div id='pre-submission' class="row">
                <div class="col-12">
                    <form action='' id='run-form'>
                        @csrf
                        <!-- SELECT INPUTS -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <label for="" class="col-form-label col-lg-2">Company</label>
                                    <div class="col-lg-10">
                                        <input id="" name="" type="text" class="form-control bg-light" value='{{Auth()->user()->company->co_name}}' readonly>
                                        <input type="text" name='co_id' value='{{Auth()->user()->company->co_id}}' hidden>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="" class="col-form-label col-lg-2">Target Job Type</label>
                                    <div class="col-lg-10">
                                        <select name="target_job_types" id="target-job-types" class="form-control">
                                            <option value="">None Selected</option>
                                            @foreach($jts as $jt)
                                            <option value="{{$jt->pay_rate}}">{{$jt->jt_name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger validations" id="target_job_types-error"></small>
                                    </div>
                                </div>
                                <input type="hidden" id="hourly_pay" name="hourly_pay">
                                <div class="row mb-2" id="pay_period_start-group" style="display: none;">
                                    <label for='pay_period_start' id="pay_period_start-label" class="col-form-label col-lg-2">Pay Period Start</label>
                                    <div class="col-lg-10">
                                        <input type="date" name="pay_period_start" id="pay_period_start" class="form-control">
                                        <small class="text-danger validations" id="pay_period_start-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-2" id="pay_period_end-group" style="display: none;">
                                    <label for='pay_period_end' id="pay_period_end-label" class="col-form-label col-lg-2">Pay Period End</label>
                                    <div class="col-lg-10">
                                        <input type="date" name="pay_period_end" id="pay_period_end" class="form-control">
                                        <small class="text-danger validations" id="pay_period_end-error"></small>
                                    </div>
                                </div>
                                <input type="hidden" id="semi_monthly_pay" name="semi_monthly_pay">
                                <div class="row mb-2" id="sm_year-group" style="display: none;">
                                    <label for='sm_year' id="sm_year-label" class="col-form-label col-lg-2">Year</label>
                                    <div class="col-lg-10">
                                        <input type="number" min="2024" max="4000" name="sm_year" id="sm_year" class="form-control" value="{{\Carbon\Carbon::now()->year}}">
                                        <small class="text-danger validations" id="sm_year-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-2" id="sm_month-group" style="display: none;">
                                    <label for='sm_month' id="sm_month-label" class="col-form-label col-lg-2">Month</label>
                                    <div class="col-lg-10">
                                        <select name="sm_month" id="sm_month" class="form-control">
                                            <option value="January" {{ \Carbon\Carbon::now()->format('F') == 'January' ? 'selected' : '' }}>January</option>
                                            <option value="February" {{ \Carbon\Carbon::now()->format('F') == 'February' ? 'selected' : '' }}>February</option>
                                            <option value="March" {{ \Carbon\Carbon::now()->format('F') == 'March' ? 'selected' : '' }}>March</option>
                                            <option value="April" {{ \Carbon\Carbon::now()->format('F') == 'April' ? 'selected' : '' }}>April</option>
                                            <option value="May" {{ \Carbon\Carbon::now()->format('F') == 'May' ? 'selected' : '' }}>May</option>
                                            <option value="June" {{ \Carbon\Carbon::now()->format('F') == 'June' ? 'selected' : '' }}>June</option>
                                            <option value="July" {{ \Carbon\Carbon::now()->format('F') == 'July' ? 'selected' : '' }}>July</option>
                                            <option value="August" {{ \Carbon\Carbon::now()->format('F') == 'August' ? 'selected' : '' }}>August</option>
                                            <option value="September" {{ \Carbon\Carbon::now()->format('F') == 'September' ? 'selected' : '' }}>September</option>
                                            <option value="October" {{ \Carbon\Carbon::now()->format('F') == 'October' ? 'selected' : '' }}>October</option>
                                            <option value="November" {{ \Carbon\Carbon::now()->format('F') == 'November' ? 'selected' : '' }}>November</option>
                                            <option value="December" {{ \Carbon\Carbon::now()->format('F') == 'December' ? 'selected' : '' }}>December</option>
                                        </select>
                                        <small class="text-danger validations" id="sm_month-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-2" id="sm_period-group" style="display: none;">
                                    <label for='sm_period' id="sm_period-label" class="col-form-label col-lg-2">Period</label>
                                    <div class="col-lg-10">
                                        <select name="sm_period" id="sm_period" class="form-control">
                                            <option value="">None Selected</option>
                                            <option value="first_half">First Half</option>
                                            <option value="second_half">Second Half</option>
                                        </select>
                                        <small class="text-danger validations" id="sm_period-error"></small>
                                    </div>
                                </div>
                                <input type="hidden" id="monthly_pay" name="monthly_pay">
                                <div class="row mb-2" id="m_year-group" style="display: none;">
                                    <label for='m_year' id="m_year-label" class="col-form-label col-lg-2">Year</label>
                                    <div class="col-lg-10">
                                        <input type="number" min="2024" max="4000" name="m_year" id="m_year" class="form-control" value="{{\Carbon\Carbon::now()->year}}">
                                        <small class="text-danger validations" id="m_year-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-2" id="m_month-group" style="display: none;">
                                    <label for='m_month' id="m_month-label" class="col-form-label col-lg-2">Month</label>
                                    <div class="col-lg-10">
                                        <select name="m_month" id="m_month" class="form-control">
                                            <option value="January" {{ \Carbon\Carbon::now()->format('F') == 'January' ? 'selected' : '' }}>January</option>
                                            <option value="February" {{ \Carbon\Carbon::now()->format('F') == 'February' ? 'selected' : '' }}>February</option>
                                            <option value="March" {{ \Carbon\Carbon::now()->format('F') == 'March' ? 'selected' : '' }}>March</option>
                                            <option value="April" {{ \Carbon\Carbon::now()->format('F') == 'April' ? 'selected' : '' }}>April</option>
                                            <option value="May" {{ \Carbon\Carbon::now()->format('F') == 'May' ? 'selected' : '' }}>May</option>
                                            <option value="June" {{ \Carbon\Carbon::now()->format('F') == 'June' ? 'selected' : '' }}>June</option>
                                            <option value="July" {{ \Carbon\Carbon::now()->format('F') == 'July' ? 'selected' : '' }}>July</option>
                                            <option value="August" {{ \Carbon\Carbon::now()->format('F') == 'August' ? 'selected' : '' }}>August</option>
                                            <option value="September" {{ \Carbon\Carbon::now()->format('F') == 'September' ? 'selected' : '' }}>September</option>
                                            <option value="October" {{ \Carbon\Carbon::now()->format('F') == 'October' ? 'selected' : '' }}>October</option>
                                            <option value="November" {{ \Carbon\Carbon::now()->format('F') == 'November' ? 'selected' : '' }}>November</option>
                                            <option value="December" {{ \Carbon\Carbon::now()->format('F') == 'December' ? 'selected' : '' }}>December</option>
                                        </select>
                                        <small class="text-danger validations" id="m_month-error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-7">
                    <form action="" id='employee-form'>
                        <div class="card">
                            <div class="card-body">
                                @foreach ($departments as $dept)
                                @if($dept->positions->count() > 0)
                                <div class="accordion department-group mb-4" id="{{ Str::slug($dept->dep_name) }}-group" id="accordionExample">
                                    <div class="accordion-item">
                                        <h5 class="accordion-header" id="heading-{{ Str::slug($dept->dep_name) }}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ Str::slug($dept->dep_name) }}" aria-expanded="true" aria-controls="{{ Str::slug($dept->dep_name) }}">
                                                {{$dept->dep_name}} Department
                                            </button>
                                        </h5>
                                        <div id="{{ Str::slug($dept->dep_name) }}" class="accordion-collapse collapse show" aria-labelledby="heading-{{ Str::slug($dept->dep_name) }}" data-bs-parent="#accordionExample">
                                            <div class="table-responsive accordion-body">
                                                <table class="table table-nowrap align-middle mb-0">
                                                    <tbody>
                                                        @foreach($dept->positions as $pos)
                                                        <tr>
                                                            <td style="width: 200px;">
                                                                <div class="form-check font-size-16">
                                                                    <input department="{{$pos->department->dep_name}}" class="form-check-input employee-checkbox {{$pos->jt->pay_rate == 'Hourly' ? 'checkbox-hourly' : ($pos->jt->pay_rate == 'Semi-Monthly' ? 'checkbox-semi-monthly' : 'checkbox-monthly')}}" type="checkbox" id="employee-{{$pos->PSI->user->id}}" name='{{$pos->PSI->user->id}}' disabled>
                                                                    <label class="form-check-label font-size-14 text-truncate" style="max-width: 150px;" for="employee-{{$pos->PSI->user->id}}">{{$pos->PSI->user->fullname()}}</label>
                                                                </div>
                                                            </td>
                                                            <td style='width: 100px;'>
                                                                <div class="avatar-group">
                                                                    <div class="avatar-group-item">
                                                                        <img src="{{asset('storage/' . $pos->PSI->user->userPI->photo)}}" alt="" class="rounded-circle avatar-xs">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style='width: 150px;'>
                                                                <span style='font-weight: bold;'>Pay Rate:</span> {{$pos->JT->pay_rate}}
                                                            </td>
                                                            <td>
                                                                <div class="text-start">
                                                                    <span class="badge rounded-pill {{$pos->PSI->badge()}} font-size-11">{{$pos->PSI->status}}</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-check font-size-16 mb-2">
                                <input class="form-check-input" type="checkbox" id="select-all">
                                <label class="form-check-label font-size-14 text-truncate" style="max-width: 150px;" for="select-all">Select All</label>
                            </div>
                            <!-- <h5 class='my-3'>Filter by</h5>
                            <div class="row">
                                <div class="col-12">
                                    <label for="dep-filter">Department</label>
                                    <select name="dep-filter" id="dep-filter" class="form-control">
                                        <option value="">None Selected</option>
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->dep_name}}">{{$dep->dep_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-end mt-4">
                        <button type="button" id='draft-btn' class="btn btn-md btn-secondary">Draft</button>
                        <button type="button" id='run-btn' class="btn btn-md btn-primary">Run Payroll</button>
                    </div>
                </div>
            </div>
            <div id="post-submission" style='display: none;'>
                @include('payroll.run')
            </div>
        </div>
    </div>
    <div id='spinner' class='d-flex gap-3 bg-warning p-4 shadow' style='position: absolute; top: 50%; left: 50%; border-radius: 10px; transform: translate(-50%, -50%); visibility: hidden;'>
        <div class="spinner-border text-white" role="status">
        </div>
        <div class='d-flex align-items-center text-white' style='font-weight: bold;'>
            Processing Payroll Run
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

    function validations(json) {
        console.log(json)
        for (let key in json) {
            $(`#${key}-error`).text('This field is required')
        }
    }

    function spinner(state) {
        state ? $('#spinner').css('visibility', 'visible') : $('#spinner').css('visibility', 'hidden')
    }

    function runPayroll(id) {
        var data = $('#employee-form').serialize();
        data += `&id=${id}`
        $.ajax({
            url: '{{route("payroll.run.process")}}',
            data: data,
            success: (html) => {
                Toast.fire({
                    title: `Payroll was processed successfully!`,
                    icon: 'success'
                });
                spinner(false);
                Swal.fire({
                    title: 'Do you want to view the payroll run?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'No, thanks',
                    confirmButtonText: 'Yes, please'
                }).then((result) => {
                    if (result.isConfirmed) {
                        setTimeout(() => {
                            let timerInterval;
                            Swal.fire({
                                title: "Generating payroll run preview...",
                                html: "Preview will be available in <b></b> seconds.",
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const timer = Swal.getPopup().querySelector("b");
                                    timerInterval = setInterval(() => {
                                        timer.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                                    }, 100);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location.href = "{{ route('payroll.run.view', ['id' => 'value']) }}".replace('value', html.location);
                                }
                            });
                        }, 1000); // 1 second delay
                    } else {
                        $('#employee-form')[0].reset()
                        $('#run-form')[0].reset()
                        Toast.fire({
                            title: `Payroll was processed successfully!`,
                            icon: 'success'
                        });
                    }
                })
            },
            error: (error) => {
                Toast.fire({
                    title: `Something went wrong`,
                    icon: 'warning'
                });
                spinner(false);
            }
        });
    }

    $('#draft-btn').click(function() {
        var data = $('#run-form').serialize()
        data += '&mode=draft'
        $.ajax({
            url: '{{route("payroll.submit")}}',
            data: data,
            type: 'post',
            success: (response) => {
                clearValidations()
                Toast.fire({
                    title: `Payroll run was drafted`,
                    icon: 'success'
                })
                $('#run-form')[0].reset()
            },
            error: (error) => {
                clearValidations()
                if (error.responseJSON.msgs) {
                    validations(error.responseJSON.msgs)
                } else {
                    Toast.fire({
                        title: `Something went wrong`,
                        icon: 'warning'
                    })
                }
            }
        })
    })
    $('#run-btn').click(function() {
        var data = $('#run-form').serialize()
        data += '&mode=run'
        $.ajax({
            url: '{{route("payroll.submit")}}',
            data: data,
            type: 'post',
            success: (response) => {
                clearValidations()
                spinner(true)
                runPayroll(response.id)
            },
            error: (error) => {
                clearValidations()
                if (error.responseJSON.msgs) {
                    validations(error.responseJSON.msgs)
                } else {
                    Toast.fire({
                        title: `Something went wrong`,
                        icon: 'warning'
                    })
                }
            }
        })
    })
    $('#select-all').click(function() {
        let isChecked = $(this).is(':checked');
        $('input[type="checkbox"]').each(function() {
            if (!$(this).is(':disabled')) {
                $(this).prop('checked', isChecked);
            }
        });
    });


    function hourlyInputs(mode) {
        mode ? $('#pay_period_start-group').show() : $('#pay_period_start-group').hide()
        mode ? $('#pay_period_end-group').show() : $('#pay_period_end-group').hide()
        mode ? $('#hourly_pay').val(true) : $('#hourly_pay').val(false)
    }

    function semiMonthlyInputs(mode) {
        mode ? $('#sm_year-group').show() : $('#sm_year-group').hide();
        mode ? $('#sm_month-group').show() : $('#sm_month-group').hide();
        mode ? $('#sm_period-group').show() : $('#sm_period-group').hide();
        mode ? $('#semi_monthly_pay').val(true) : $('#semi_monthly_pay').val(false)
    }

    function monthlyInputs(mode) {
        mode ? $('#m_year-group').show() : $('#m_year-group').hide();
        mode ? $('#m_month-group').show() : $('#m_month-group').hide();
        mode ? $('#monthly_pay').val(true) : $('#monthly_pay').val(false)
    }

    function updateSelectInputs(payRate) {
        switch (payRate) {
            case "Hourly":
                hourlyInputs(true)
                semiMonthlyInputs(false)
                monthlyInputs(false)
                break;
            case "Semi-Monthly":
                hourlyInputs(false)
                semiMonthlyInputs(true)
                monthlyInputs(false)
                break;
            case "Monthly":
                hourlyInputs(false)
                semiMonthlyInputs(false)
                monthlyInputs(true)
                break;
            default:
                hourlyInputs(false)
                semiMonthlyInputs(false)
                monthlyInputs(false)
                break;
        }
    }

    function updateEmployeeCheckboxes(payRate) {
        payRate == 'Hourly' ? $('.checkbox-hourly').prop('disabled', false) : $('.checkbox-hourly').prop('disabled', true)
        payRate == 'Semi-Monthly' ? $('.checkbox-semi-monthly').prop('disabled', false) : $('.checkbox-semi-monthly').prop('disabled', true)
        payRate == 'Monthly' ? $('.checkbox-monthly').prop('disabled', false) : $('.checkbox-monthly').prop('disabled', true)
    }
    $('#target-job-types').change(function() {
        updateSelectInputs($(this).val())
        updateEmployeeCheckboxes($(this).val())
    })
</script>
@endsection