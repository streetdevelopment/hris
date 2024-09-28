@extends('layouts.layout')
@section('title', 'HRIS - Attendance')
@section('more_links')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');

    .clock {
        color: #000;
        font-size: 56px;
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px
    }

    h2 {
        color: #212529;
        padding: 30px
    }

    #m {
        margin: 0 10px 0 10px
    }

    .bg {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.5em;
        height: 1.5em;
        background: white;
        position: relative;
        border-radius: 50%;
        box-shadow: inset -2px -2px 5px rgba(255, 255, 255, 1), inset 3px 3px 5px rgba(0, 0, 0, 0.2)
    }

    .bg:last-child {
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        position: relative;
        margin-left: 20px;
        width: 2em;
        height: 2em;
        font-size: 16px;
        padding: 30px;
        border-radius: 50%;
        box-shadow: inset -2px -2px 5px rgba(255, 255, 255, 1), inset 3px 3px 5px rgba(0, 0, 0, 0.2)
    }
</style>
@endsection
@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Clock In</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-2">
                    <div class="card" style='height: 150px;'>
                        <div class="card-body border border-dark rounded d-flex flex-column justify-content-between align-items-center">
                            <h3 class='mt-2 {{$user->hasTimedOut() ? "text-warning" : ""}}'>{{$user->renderTime('in')}}</h3>
                            <div><button onclick="configure('time_in')" id='time-in-btn' class='btn btn-sm btn-dark' type="buttom" data-bs-toggle='modal' data-bs-target='#time-in-modal' {{$user->hasTimedIn() ? 'disabled' : ''}}>Time In</button></div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card bg-dark" style='height: 150px;'>
                        <div class="card-body">
                            <div class="clock">
                                <div class="bg">
                                    <h2 id="h">12</h2>
                                </div>
                                <h2>:</h2>
                                <div class="bg">
                                    <h2 id="m">20</h2>
                                </div>
                                <h2>:</h2>
                                <div class="bg">
                                    <h2 id="s">00</h2>
                                </div>
                                <div class="bg">
                                    <h2 id="ap">AM</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card" style='height: 150px;'>
                        <div class="card-body border border-dark rounded d-flex flex-column justify-content-between align-items-center">
                            <h3 class='mt-2 {{$user->hasTimedIn() && $user->getRecordsToday()->count() > 1 ? "text-warning" : ""}}'>{{$user->renderTime('out')}}</h3>
                            <div><button onclick="configure('time_out')" id='time-out-btn' class='btn btn-sm btn-dark' type="button" data-bs-toggle="modal" data-bs-target="#time-out-modal" {{$user->hasTimedOut() ? 'disabled' : ''}}>Time Out</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade bs-example-modal-sm" data-bs-backdrop='static' id='time-in-modal' tabindex="-1" aria-labelledby="time-in-modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id='time-in-form' action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Time In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row px-4">
                        <label for="date" class="col-md-4 col-form-label">Date</label>
                        <div class="col-md-8">
                            <input class="form-control bg-light" name="date" type="date" id="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>
                    <div id="time-cont">
                    </div>
                    <div class="my-2 px-4 d-flex flex-column gap-0 justify-content-center align-items-center">
                        <h6 class="text-muted h6">{{Auth()->user()->company->policies->enable_camera === 1 ? 'Smile for the camera!' : ''}}</h6>
                        <div class="border border-secondary" id="myCameraIn">
                        </div>
                    </div>
                    <div class="mb-3 row px-4">
                        <label for="remarks" class="col-md-12 col-form-label">Remarks</label>
                        <div class="col-md-12">
                            <textarea id="time_in_remark" class="form-control" maxlength="225" rows="3" placeholder="Add Remarks" name="time_in_remark"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class='btn btn-sm btn-primary'>Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $.ajax({
        url: '',
        data: '',
        processData: false,
        contentType: false,
        type: 'post',
        success: (response) => {

        },
        error: (error) => {

        }
    })
</script>
<div class="modal fade bs-example-modal-sm" data-bs-backdrop='static' id='time-out-modal' tabindex="-1" aria-labelledby="time-out-modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id='time-out-form' action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Time Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row px-4">
                        <label for="date" class="col-md-4 col-form-label">Date</label>
                        <div class="col-md-8">
                            <input class="form-control" name="date" type="date" id="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                            <small id="date_of_birth_error" class="field_errors form-text text-warning"></small>
                        </div>
                        <div id="time-cont-out">
                        </div>
                        <div class="my-2 px-4 d-flex flex-column gap-0 justify-content-center align-items-center">
                            <h6 class="text-muted h6">{{Auth()->user()->company->policies->enable_camera === 1 ? 'Smile for the camera!' : ''}}</h6>
                            <div class="border border-secondary" id="myCameraOut">
                            </div>
                        </div>
                        <div class="mb-3 row px-4">
                            <label for="remarks" class="col-md-12 col-form-label">Remarks</label>
                            <div class="col-md-12">
                                <textarea id="time_out_remark" class="form-control" maxlength="225" rows="3" placeholder="Add Remarks" name="time_out_remark"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class='btn btn-sm btn-primary'>Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endsection
@section('more_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{ asset('assets/webcam/webcam.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        realTime();
    });

    function realTime() {
        var date = new Date();
        var hour = date.getHours();
        var min = date.getMinutes();
        var sec = date.getSeconds();
        var halfday = "AM";
        halfday = (hour >= 12) ? "PM" : "AM";
        hour = (hour == 0) ? 12 : ((hour > 12) ? (hour - 12) : hour);
        hour = update(hour);
        min = update(min);
        sec = update(sec);
        document.getElementById("h").innerText = hour;
        document.getElementById("m").innerText = min;
        document.getElementById("s").innerText = sec;
        document.getElementById("ap").innerText = halfday;
        setTimeout(realTime, 1000);
    }

    function update(k) {
        if (k < 10) {
            return "0" + k;
        } else {
            return k;
        }
    }
    // Webcam Functions
    function configure(mode) {
        if ('{{Auth()->user()->company->policies->enable_camera === 1}}') {
            Webcam.set({
                width: 200,
                height: 150,
                image_format: 'jpeg',
                jpeg_quality: 90
            });

            if (mode == "time_in") {
                Webcam.attach('#myCameraIn');
            } else {
                Webcam.attach('#myCameraOut');
            }
        }
    }

    function captureAndSave(mode, id) {
        if ('{{Auth()->user()->company->policies->enable_camera === 1}}') {
            Webcam.snap(function(data_uri) {
                saveImage(data_uri, mode, id);
            });
            Webcam.reset();
        }
    }

    function saveImage(imageData, mode) {
        $.ajax({
            url: '{{route("attendance.attendance.upload")}}',
            data: {
                imageData: imageData,
                mode: mode
            },
            success: function(response) {
                console.log('Photo Success!');
            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    function load_time(time_in = null) {
        $.ajax({
            url: '{{route("attendance.attendance.time")}}',
            data: '',
            contentType: false,
            processData: false,
            success: function(data) {
                (time_in) ? $('#time-cont').html(data): $('#time-cont-out').html(data);
            },
            error: (error) => {
                console.log(error)
            }
        });
    }

    $('#time-in-btn').click(function() {
        load_time(true);
    });
    $('#time-out-btn').click(function() {
        load_time(false);
    });

    function attachListeners() {
        const closeModalBtns = document.querySelectorAll('.btn-close');
        closeModalBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                Webcam.reset();
            })
        })
    }
    attachListeners()

    $('#time-in-form').submit(function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '{{route("attendance.attendance.time.in")}}',
            data: formData,
            success: (response) => {
                $('#time-in-modal').modal('toggle');
                captureAndSave("time_in");
                Swal.fire({
                    title: 'You have timed in for the day!',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                })
            },
            error: (error) => {
                console.log(error);
            }
        })
    })

    $('#time-out-form').submit(function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: '{{route("attendance.attendance.time.out")}}',
            data: formData,
            success: (response) => {
                $('#time-out-modal').modal('toggle');
                captureAndSave("time_out");
                Swal.fire({
                    title: 'You have timed out for the day!',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'Okay'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                })
            },
            error: (error) => {
                console.log(error);
            }
        })
    })
</script>
@endsection