@extends('layouts.layout')
@section('title', 'HRIS - Deductions')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="fluid-container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Deductions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Deductions</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row {{Auth()->user()->role == 'employee' ? 'pe-none' : ''}}">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body" id='list'>
                            @if(Auth()->user()->role == 'employee')
                            <h4 class='text-center'>Read Only</h4>
                            @endif
                            @include('company.deductions.list')
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <form action="" id='deductions-form'>
                        <div class="card">
                            <div class="card-body">
                                @if(Auth()->user()->role == 'employee')
                                <h4 class='text-center'>Read Only</h4>
                                @endif
                                <div class="row d-flex align-items-end">
                                    <div class='col-6 text-start'>
                                        <h6>Add Deductions</h6>
                                    </div>
                                    <div class='col-6 text-end'>
                                        <button type='submit' class="btn btn-sm btn-primary px-5">Add</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label for="">Company <span class="text-danger">*</span></label>
                                        <input name='' type="text" class='form-control bg-light' value='{{Auth()->user()->company->co_name}}' readonly>
                                        <input name='co_id' type="text" class='form-control bg-light' value='{{Auth()->user()->company->co_id}}' hidden>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label for="deduction_type">Deduction Type <span class="text-danger">*</span></label>
                                        <input name='deduction_type' type="text" class='form-control'>
                                        <small class="text-danger validations" id="deduction_type-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" rows='3' class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label for="value">Amount in ₱<span class="text-danger">*</span></label>
                                        <input name='value' type="number" step='0.01' name='value' class='form-control'>
                                        <small class="text-danger validations" id="value-error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modals')
<div class="modal fade bs-example-modal-sm" id='edit-modal' tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="" id='deduction-edit-form'>
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Edit Deduction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="">Company <span class="text-danger">*</span></label>
                                <input name='' type="text" class='form-control bg-light' value='{{Auth()->user()->company->co_name}}' readonly>
                                <input name='modal_co_id' type="text" class='form-control bg-light' value='{{Auth()->user()->company->co_id}}' hidden>
                                <small class="validations text-danger" id='modal_co_id-error'></small>
                            </div>
                            <input type="hidden" name="modal_id" id="modal_id">
                            <div class="col-12 mt-2">
                                <label for="value">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" id="select-status">
                                    <option value="">None Selected</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <small class="validations text-danger" id='status-error'></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end col -->
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

    function renderList() {
        $.ajax({
            url: '{{route("company.deductions.reload")}}',
            data: '',
            success: (html) => {
                $('#list').html(html)
                attachListeners()
            },
            error: (error) => {
                Toast.fire({
                    title: `An error has occued`,
                    icon: 'warning'
                })
            }
        })
    }

    function attachListeners() {
        $('.delete-btn').click(function() {
            var id = $(this).attr('data-id')
            Swal.fire({
                icon: 'warning',
                title: 'Do you intend to delete this deduction?',
                confirmButtonText: 'Yes',
                showDenyButton: true,
                denyButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route("company.deductions.delete")}}',
                        data: {
                            'id': id
                        },
                        success: (response) => {
                            Toast.fire({
                                title: `${response.name} was successfully deleted!`,
                                icon: 'success'
                            })
                            renderList();
                        },
                        error: (error) => {
                            Toast.fire({
                                title: `An error has occued`,
                                icon: 'warning'
                            })
                        }
                    })
                }
            })
        })
        $('.edit-btn').click(function() {
            $('#edit-modal').modal('toggle')
            var id = $(this).attr('data-id')
            $.ajax({
                url: '{{route("company.deductions.find")}}',
                data: {
                    'id': id
                },
                success: (response) => {
                    $('#select-status').val(response.deduction.status);
                    $('#modal_id').val(response.deduction.ded_id);
                },
                error: (error) => {
                    $('#edit-modal').modal('toggle')
                    Toast.fire({
                        title: `An error has occued`,
                        icon: 'warning'
                    })
                }
            })
        })
    }
    attachListeners()
    $('#deductions-form').submit(function(e) {
        e.preventDefault()
        var formData = $(this).serialize()
        $.ajax({
            url: '{{route("company.deductions.add")}}',
            data: formData,
            success: (response) => {
                clearValidations()
                Toast.fire({
                    title: `Deduction added`,
                    icon: 'success'
                })
                $(this)[0].reset()
                renderList()
            },
            error: (error) => {
                clearValidations()
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}-error`).text("This field is required")
                    }
                } else {
                    Toast.fire({
                        title: `An error has occued`,
                        icon: 'warning'
                    })
                }
            }
        })
    })
    $('#deduction-edit-form').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize()
        $.ajax({
            url: '{{route("company.deductions.edit")}}',
            data: data,
            success: (response) => {
                clearValidations()
                $('#edit-modal').modal('toggle')
                Toast.fire({
                    title: `${response.name} was successfully edited!`,
                    icon: 'success'
                })
                renderList();
            },
            error: (error) => {
                clearValidations()
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}-error`).text("This field is required")
                    }
                } else {
                    $('#edit-modal').modal('toggle')
                    Toast.fire({
                        title: `An error has occued`,
                        icon: 'warning'
                    })
                }
            }
        })
    })
</script>
@endsection