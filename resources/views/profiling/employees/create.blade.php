@extends('layouts.layout')
@section('title', 'HRIS - Add Employee')
@section('more_links')
<link rel="stylesheet" href="{{ asset('assets/cropperjs/cropper.min.css') }}" />
@endsection
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add Employee</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <form action="" id='employee-create' enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class='mb-4'>Personal Information</h5>
                                <div class="mb-4 d-flex gap-2 justify-content-center">
                                    <?php $randomnumber = rand(1, 8); ?>
                                    <img src="{{asset('assets/images/users/avatar-' . $randomnumber . '.jpg')}}" alt="" class='rounded rounded-circle avatar-lg img-thumbnail' id='cropper-result'>
                                    <div class='d-flex justify-content-center flex-column'>
                                        <h6>Employee Photo</h6>
                                        <input type="file" name="photo" id="photo" class="form-control form-control-sm">
                                        <button type="button" class="btn btn-sm btn-danger mt-1" id='remove-marker-image-btn'>Remove</button>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <input id='first_name' name='first_name' class='form-control' type="text" placeholder="First name">
                                        <small class='validations text-danger' id='first_name-error'></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="middle_name">Middle Name</label>
                                        <input id='middle_name' name='middle_name' class='form-control' type="text" placeholder="Middle name">
                                    </div>
                                    <div class="col-4">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <input id='last_name' name='last_name' class='form-control' type="text" placeholder="Last name">
                                        <small class='validations text-danger' id='last_name-error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label for="sex">Sex <span class="text-danger">*</span></label>
                                        <select name="sex" id="sex" class="form-control">
                                            <option value="">None Selected</option>
                                            <option value="Male">Male</option>
                                            <option value="Male">Female</option>
                                            <option value="Male">Non-binary</option>
                                        </select>
                                        <small class='validations text-danger' id='sex-error'></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                        <input id='date_of_birth' name='date_of_birth' class='form-control' type="date">
                                        <small class='validations text-danger' id='date_of_birth-error'></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="nationality">Nationality</label>
                                        <input id='nationality' name='nationality' class='form-control' type="text" placeholder="Nationality">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class='mb-4'>Contact Information</h5>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                        <input id='contact_number' name='contact_number' class='form-control' type="text" placeholder="Contact number">
                                        <small class='validations text-danger' id='contact_number-error'></small>
                                    </div>
                                    <div class="col-6">
                                        <label for="email_address">Email Address <span class="text-danger">*</span></label>
                                        <input id='email_address' name='email_address' class='form-control' type="email" placeholder="Email address">
                                        <small class='validations text-danger' id='email_address-error'></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label for="permanent_address">Permanent Address <span class="text-danger">*</span></label>
                                        <input id='permanent_address' name='permanent_address' class='form-control' type="text" placeholder="Permanent address">
                                        <small class='validations text-danger' id='permanent_address-error'></small>
                                    </div>
                                    <div class="col-6">
                                        <label for="current_address">Current Address</label>
                                        <input id='current_address' name='current_address' class='form-control' type="text" placeholder="Current address">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label for="ec_name">Emergency Contact Name</label>
                                        <input id='ec_name' name='ec_name' class='form-control' type="text" placeholder="Emergency contact name">
                                    </div>
                                    <div class="col-4">
                                        <label for="ec_relation">Relationship to Emergency Contact</label>
                                        <input id='ec_relation' name='ec_relation' class='form-control' type="text" placeholder="Relationship to Emergency Contact">
                                    </div>
                                    <div class="col-4">
                                        <label for="ec_contact_number">Emergency Contact Number</label>
                                        <input id='ec_contact_number' name='ec_contact_number' class='form-control' type="text" placeholder="Emergency contact number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class='mb-4'>Position Information</h5>
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label for="title">Title <span class="text-danger">*</span></label>
                                        <input id='title' name='title' class='form-control' type="text" placeholder="Title">
                                        <small class="validations text-danger" id="title-error"></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="date_hired">Date Hired <span class="text-danger">*</span></label>
                                        <input id='date_hired' name='date_hired' class='form-control' type="date">
                                        <small class="validations text-danger" id="date_hired-error"></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="input-status" class="form-control">
                                            <option value="">None Selected</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="On Leave">On Leave</option>
                                        </select>
                                        <small class="validations text-danger" id="status-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label for="jt_id">Job Type <span class="text-danger">*</span></label>
                                        <select name="jt_id" id="jt_id" class="form-control">
                                            <option value="">None Selected</option>
                                            @foreach($jts as $jt)
                                            <option value="{{$jt->jt_id}}" rate='{{$jt->pay_rate}}'>{{$jt->jt_name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="validations text-danger" id="jt_id-error"></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="salary">Salary (<span id='pay_rate'>Monthly</span>) <span class="text-danger">*</span></label>
                                        <input id='salary' name='salary' class='form-control' type="number" step='0.01' placeholder="Salary">
                                        <small class="validations text-danger" id="salary-error"></small>
                                    </div>
                                    <div class="col-4">
                                        <label for="dep_id">Department <span class="text-danger">*</span></label>
                                        <select name="dep_id" id="dep_id" class="form-control">
                                            <option value="">None Selected</option>
                                            @foreach($dps as $dp)
                                            <option value="{{$dp->dep_id}}">{{$dp->dep_name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="validations text-danger" id="dep_id-error"></small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="description">Job Description</label>
                                    <div class="col-12">
                                        <textarea name="description" id="description" rows="2" class='form-control' placeholder="Job description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class='mb-4'>Account Information</h5>
                                <div class="row mb-4">
                                    <div class="col-3">
                                        <label for="display_only">Company <span class="text-danger">*</span></label>
                                        <input id='display_only' name='' class='form-control bg-light' type="text" value='{{Auth()->user()->company->co_name}}' readonly>
                                    </div>
                                    <input type="hidden" name="co_id" value='{{Auth()->user()->company->co_id}}'>
                                    <div class="col-3">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input id='username' name='username' class='form-control bg-light' type="text" placeholder="Username" readonly>
                                        <small class="validations text-danger" id="username-error"></small>
                                    </div>
                                    <div class="col-3">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="text" id="plain-password" name='plain-password' class="form-control" placeholder="Password">
                                        <input id='password' name='password' class='form-control' type="password" placeholder="Password" hidden>
                                        <small class="validations text-danger" id="password-error"></small>
                                    </div>
                                    <div class="col-3">
                                        <label for="role">Role <span class="text-danger">*</span></label>
                                        <select name="role" id="role" class="form-control" {{Auth()->user()->role !== 'admin' ? 'disabled' : ''}}>
                                            <option value="employee">Employee</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <small class="validations text-danger" id="username-error"></small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class='text-center mb-3'>
                                        <button class='btn btn-primary px-5' type="submit">Add New Employee</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modals')
<div class="modal fade" id="cropper-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Crop Image</h4>
                <button type="button" id="cropper-cancel-top-btn" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <img id="photo-cropper" alt="Upload an Image" />
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="cropper-cancel-bot-btn" type="button" class="btn btn-default">Cancel</button>
                <button id="cropper-crop-btn" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="bg-light py-3 px-4" id="email-spinner" style="display: none; border-radius: 10px; position: fixed; top: 10%; right: 2.5%; z-index: 10;">
    <div class="d-flex align-items-center gap-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h4 style="margin-bottom: 0;">Adding records</h4>
    </div>
</div>
@endsection
@section('more_scripts')
<script src="{{ asset('assets/cropperjs/cropper.min.js') }}"></script>
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('#plain-password').change(function() {
        $('#password').val($('#plain-password').val())
    })

    function removeValidations() {
        $('.validations').text('')
    }

    function emailSpinner(mode) {
        if (mode) {
            $('#email-spinner').show()
            $('#email-modal').modal('toggle')
        } else {
            $('#email-spinner').hide()
        }
    }

    $('#jt_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var payRate = selectedOption.attr('rate');
        $('#pay_rate').text(payRate);
    });

    $('#employee-create').submit(function(e) {
        e.preventDefault()
        var formData = new FormData(this);
        $.ajax({
            url: '{{route("profiling.employees.create.submit")}}',
            data: formData,
            contentType: false,
            processData: false,
            type: 'POST',
            beforeSend: () => {
                emailSpinner(true);
            },
            success: (response) => {
                removeValidations()
                Toast.fire({
                    title: `${response.name} has been added successfully`,
                    icon: 'success'
                })
                $(this)[0].reset()
            },
            error: (error) => {
                removeValidations()
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        if (key == 'jt_id') {
                            $(`#${key}-error`).text('The job type field is required')
                        } else {
                            $(`#${key}-error`).text(error.responseJSON.msgs[key]);
                        }
                    }
                }
            },
            complete: () => {
                emailSpinner(false);
            }
        })
    })
    $('#first_name, #last_name').change(function() {
        var firstName = $('#first_name').val().replace(/\s+/g, '').toLowerCase();
        var lastName = $('#last_name').val().replace(/\s+/g, '').toLowerCase();

        $('#username').val(`${firstName}.${lastName}`);
    });
    let cropper;
    const imageInput = document.getElementById('photo');
    const cropperCont = document.getElementById('photo-cropper');
    const cropBtn = document.getElementById('cropper-crop-btn');
    const cancelCropTopBtn = document.getElementById('cropper-cancel-top-btn');
    const cancelCropBotBtn = document.getElementById('cropper-cancel-bot-btn');
    const removeImageUploadBtn = document.getElementById('remove-marker-image-btn');
    $('#remove-marker-image-btn').hide();
    $('#cropper-result').show();

    function dataURLtoBlob(dataURL) {
        const parts = dataURL.split(';base64,');
        const contentType = parts[0].split(':')[1];
        const raw = window.atob(parts[1]);
        const rawLength = raw.length;
        const uint8Array = new Uint8Array(rawLength);

        for (let i = 0; i < rawLength; ++i) {
            uint8Array[i] = raw.charCodeAt(i);
        }

        return new Blob([uint8Array], {
            type: contentType
        });
    }
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                cropperCont.src = e.target.result;

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(cropperCont, {
                    aspectRatio: 1,
                    viewMode: 1
                });
            };

            reader.readAsDataURL(file);

            $('#cropper-modal').modal('toggle');
        }
    });
    removeImageUploadBtn.addEventListener('click', () => {
        document.getElementById('cropper-result').src = "";
        imageInput.value = '';
        $('#cropper-result').hide();
        $('#remove-marker-image-btn').hide();
    })
    cancelCropTopBtn.addEventListener('click', () => {
        $('#cropper-modal').modal('toggle');
        // $('#photo').val('');
    })
    cancelCropBotBtn.addEventListener('click', () => {
        $('#cropper-modal').modal('toggle');
        // $('#photo').val('');
    })
    cropBtn.addEventListener('click', () => {
        const croppedDataUrl = cropper.getCroppedCanvas().toDataURL();

        const blob = dataURLtoBlob(croppedDataUrl);

        const croppedFile = new File([blob], 'cropped_image.jpg', {
            type: 'image/jpeg'
        });

        const dataTransfer = new DataTransfer();

        dataTransfer.items.add(croppedFile);

        document.getElementById('photo').files = dataTransfer.files;

        $('#remove-marker-image-btn').show();
        $('#cropper-result').show();
        document.getElementById('cropper-result').src = croppedDataUrl;

        $('#cropper-modal').modal('toggle');
    })
</script>
@endsection