@extends('layouts.layout')
@section('title', 'HRIS - Create Leave Type')
@section('more_links')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Create Leave Type</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Type of Leaves</a></li>
                                <li class="breadcrumb-item active">Create Leave Type</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <form action="" id='lt-create'>
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <label for="co_id" class="col-form-label col-lg-2">Company</label>
                            <div class="col-lg-10">
                                <input id="co_id" name="co_id" type="text" class="form-control bg-light" data-id='{{Auth()->user()->co_id}}' value='{{Auth()->user()->company->co_name}}' readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-form-label col-lg-2">Name</label>
                            <div class="col-lg-10">
                                <input id="name" name="name" type="text" class="form-control" placeholder="Name of leave">
                                <small class="text-danger validations" id='name_error'></small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="description" class="col-form-label col-lg-2">Description</label>
                            <div class="col-lg-10">
                                <textarea name="description" id="description" class='form-control' rows="5" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="requirements" class="col-form-label col-lg-2">Requirements</label>
                            <div class="col-lg-10">
                                <select class="js-example-basic-multiple form-control" name="requirements[]" multiple="multiple">
                                    @forelse ($dts as $dt)
                                    <option value="{{$dt->dt_id}}">{{$dt->dt_name}}</option>
                                    @empty
                                    <option value="">No Document Types Found</option>
                                    @endforelse
                                </select>
                                <small class="text-danger validations" id='requirements_error'></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type='submit' class="btn btn-primary m-2 btn-sm">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('more_scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    $('.js-example-basic-multiple').select2({
        placeholder: "Select requirements",
        allowClear: true
    });

    function removeValidations() {
        $('.validations').text('')
    }
    $('#lt-create').submit(function(e) {
        e.preventDefault()
        let formData = $(this).serialize()
        $.ajax({
            url: '{{route("company.leavetypes.submit")}}',
            data: formData,
            success: (response) => {
                removeValidations()
                Toast.fire({
                    title: `${response.name} was successfully added`,
                    icon: 'success'
                })
                $('#lt-create')[0].reset()
                $('.js-example-basic-multiple').val(null).trigger('change')
            },
            error: (error) => {
                removeValidations()
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}_error`).text(error.responseJSON.msgs[key])
                    }
                } else {
                    Toast.fire({
                        title: "Something went wrong",
                        icon: 'warning'
                    })
                }
            }
        })
    })
</script>
@endsection