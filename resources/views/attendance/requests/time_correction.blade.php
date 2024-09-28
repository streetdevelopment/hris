@extends('layouts.layout')
@section('title', 'HRIS - Request for Time Correction')
@section('more_links')
<link href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" rel="stylesheet" />
<link href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" rel="stylesheet" />
<link href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" rel="stylesheet" />
@endsection
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Request for Time Correction</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Time Correction Request</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <form action="" id='time-correction-form'>
                <div class="row">
                    <div id='form-div' class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-end mb-2">
                                    <a href="{{route('attendance.attendance.history')}}" class="btn btn-sm btn-primary">Attendance History</a>
                                </div>
                                <div class="row mb-4">
                                    <label for="user_id" class="col-form-label col-lg-2">Employee</label>
                                    <div class="col-lg-10">
                                        <input id="" name="" type="text" class="form-control bg-light" value='{{$user->fullname()}}' readonly>
                                        <input id="user_id" name="user_id" type="text" value='{{$user->id}}' hidden>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="record" class="col-form-label col-lg-2">Attendance Record</label>
                                    <div class="col-lg-10">
                                        <select name="" id="record" class='form-control'>
                                            <option value="">Select an Attendance Record</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="original_clock_in" class="col-form-label col-lg-2">Original Clock In Time</label>
                                    <div class="col-lg-10">
                                        <input id="original_clock_in" name="original_clock_in" type="datetime-local" class="form-control bg-light" readonly>
                                        <small class="validations text-danger" id="original_clock_in-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="end" class="col-form-label col-lg-2">Corrected Clock In Time</label>
                                    <div class="col-lg-10">
                                        <input id="corrected_clock_in" name="corrected_clock_in" type="datetime-local" class="form-control">
                                        <small class="validations text-danger" id="corrected_clock_in-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="original_clock_out" class="col-form-label col-lg-2">Original Clock Out Time</label>
                                    <div class="col-lg-10">
                                        <input id="original_clock_out" name="original_clock_out" type="datetime-local" class="form-control bg-light" readonly>
                                        <small class="validations text-danger" id="original_clock_out-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="end" class="col-form-label col-lg-2">Corrected Clock Out Time</label>
                                    <div class="col-lg-10">
                                        <input id="corrected_clock_out" name="corrected_clock_out" type="datetime-local" class="form-control">
                                        <small class="validations text-danger" id="corrected_clock_out-error"></small>
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
                                <div class="text-center mb-2">
                                    <button type='submit' class="btn btn-sm btn-primary">Submit Request</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id='calendar-div' class="col-4" style='display: none;'>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex p-2 justify-content-between">
                                    <div class='btn btn-sm btn-warning' id="prevButton">Prev</div>
                                    <div class='btn btn-sm btn-primary' id="nextButton">Next</div>
                                </div>
                                <div id="calendar"></div>
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
<script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.js"></script>
<script src="https://uicdn.toast.com/tui-dom/latest/tui-dom.js"></script>
<script src="https://uicdn.toast.com/tui-time-picker/latest/tui-time-picker.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = tui.Calendar;

        var calendar = new Calendar('#calendar', {
            defaultView: 'day',
            taskView: false,
            scheduleView: ['time'],
            useCreationPopup: false,
            useDetailPopup: false,
            calendars: [{
                id: '1',
                name: 'My Calendar',
                color: '#ffffff',
                bgColor: '#9e5fff',
                dragBgColor: '#9e5fff',
                borderColor: '#9e5fff'
            }]
        });

        function clearAllSchedules() {
            calendar.clear();
        }

        function toggleDivs(mode = false) {
            if (mode) {
                $('#form-div').removeClass('col-12').addClass('col-8')
                $('#calendar-div').show()
            } else {
                $('#form-div').removeClass('col-8').addClass('col-12')
                $('#calendar-div').hide()
            }
        }
        $('#original_clock_in, #corrected_clock_in, #original_clock_out, #corrected_clock_out').change(function() {
            clearAllSchedules()
            if ($('#original_clock_in').val() !== '' && $('#corrected_clock_in').val() !== '' && $('#original_clock_out').val() !== '' && $('#corrected_clock_out').val() !== '') {
                toggleDivs(true)
                var originalClockIn = new Date($('#original_clock_in').val()).toISOString();
                calendar.createSchedules([{
                    id: '1',
                    calendarId: '1',
                    title: 'Original Time',
                    category: 'time',
                    dueDateClass: '',
                    start: $('#original_clock_in').val(),
                    end: $('#original_clock_out').val(),
                    color: '#ffffff',
                    bgColor: '#ff4040',
                    dragBgColor: '#ff4040',
                    borderColor: '#ff4040'
                }]);
                calendar.createSchedules([{
                    id: '2',
                    calendarId: '1',
                    title: 'Corrected Time',
                    category: 'time',
                    dueDateClass: '',
                    start: $('#corrected_clock_in').val(),
                    end: $('#corrected_clock_out').val()
                }]);
                document.getElementById('prevButton').addEventListener('click', function() {
                    calendar.prev();
                });

                document.getElementById('nextButton').addEventListener('click', function() {
                    calendar.next();
                });
            } else {
                toggleDivs(false)
            }
        })
    });
</script>
<script>
    function removeValidations() {
        $('.validations').text('')
    }
    $('#time-correction-form').submit(function(e) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        e.preventDefault()
        var data = $(this).serialize()
        $.ajax({
            url: '{{route("attendance.request.tc.submit")}}',
            data: data,
            success: (response) => {
                removeValidations()
                Toast.fire({
                    title: `Your request was sent successfully`,
                    icon: 'success'
                })
                $(this)[0].reset()
                clearAllSchedules()
                toggleDivs(false)
            },
            error: (error) => {
                removeValidations()
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}-error`).text('This field is required')
                    }
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