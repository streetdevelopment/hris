@extends('layouts.layout')
@section('title', 'HRIS - Leave Credits')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Employee Leave Credits</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                            <li class="breadcrumb-item active">Leave Credits</li>
                        </ol>
                    </div>

                </div>
            </div>
            <div class="row">
                @foreach($employees as $emp)
                <div class="col-xl-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body bg-light">
                            <div class="avatar-sm mx-auto mb-4">
                                @if($emp->userPI->photo)
                                <img src="{{ asset('storage/' . $emp->userPI->photo) }}" class="rounded-circle avatar-sm" alt="Test">
                                @else
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-16">
                                    {{$emp->fullname()[0]}}
                                </span>
                                @endif
                            </div>
                            <h5 class="font-size-15 mb-1"><a href="javascript: void(0);" class="text-dark">{{$emp->userPI->first_name}} {{$emp->userPI->last_name}}</a></h5>
                            <p class="text-muted">{{$emp->userPSI->position->title}}</p>
                            <h6>Leave Credit: <span style='font-weight: 300;'>{{$emp->leave_credit}}</span></h6>
                        </div>
                        <div class="card-footer bg-secondary">
                            <button type="button" empName="{{$emp->fullname()}}" empId="{{$emp->id}}" empLC="{{$emp->leave_credit}}" class="edit-btn btn btn-light btn-sm">Edit</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@section('modals')
<div class="modal fade bs-example-modal-sm" id="edit-modal" data-bs-backdrop='static' id='time-in-modal' tabindex="-1" aria-labelledby="edit-modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id='edit-lc-form' action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Edit Leave Credits</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row px-4">
                        <label for="date" class="col-md-12 col-form-label">Employee Name</label>
                        <div class="col-md-12">
                            <input class="form-control bg-light" name="" type="text" id="employee-name-display" placeholder="Something went wrong." readonly>
                            <input type="hidden" name="employee_id" id="employee_id">
                        </div>
                    </div>
                    <div class="mb-3 row px-4">
                        <label for="remarks" class="col-md-12 col-form-label">Leave Credits</label>
                        <div class="col-md-12">
                            <input type="text" id="employee_leave_credit" name="employee_leave_credit" class="form-control" placeholder="Enter a new leave credit value">
                            <small class="text-danger validations" id="employee_leave_credit-error"></small>
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
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    $('.edit-btn').click(function() {
        $('#employee-name-display').val($(this).attr('empName'))
        $('#employee_id').val($(this).attr('empId'))
        $('#employee_leave_credit').val($(this).attr('empLC'))
        $('#edit-modal').modal('toggle');
    })
    $('#edit-lc-form').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize()
        $.ajax({
            url: "{{route('attendance.leave_credits.edit')}}",
            data: data,
            success: (response) => {
                $('.validations').text('')
                Toast.fire({
                    title: `Leave credit succesfully saved`,
                    icon: 'success'
                })
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: (error) => {
                $('.validations').text('')

                if (error.responseJSON.msgs) {

                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}-error`).text('This field is required')
                    }
                } else if (error.responseJSON.same_lc) {
                    Toast.fire({
                        title: `No changes detected`,
                        icon: 'warning'
                    })
                }
            }
        })
    })
</script>
@endsection