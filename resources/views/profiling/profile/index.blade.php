@extends('layouts.layout')
@section('title', 'HRIS - Profile')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{Auth()->user()->userPI->first_name}}'s Profile</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{auth()->user()->company->co_name}}</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card pt-3 mx-n4 mt-n4 bg-info-subtle">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                @if($pi->photo)
                                <img src="{{asset('storage/' . $pi->photo)}}" alt="" class="avatar-md rounded-circle mx-auto d-block">
                                @else
                                <span class="avatar-md rounded-circle bg-success text-white font-size-24 mx-auto d-flex align-items-center justify-content-center" style='cursor: pointer; position: relative;' onclick="profileBlur(this)">
                                    {{$user->fullname()[0]}}
                                </span>
                                @endif
                                <h5 class="mt-3 mb-1">{{$user->fullname()}}</h5>
                                <p class="text-muted mb-3">{{$psi->position->title}}</p>
                                <div class="mx-auto">
                                    <span class="badge text-bg-info">{{$psi->position->jt->jt_name}}</span>
                                    <span class="badge text-bg-warning">{{$ci->contact_number}}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled hstack gap-3 mb-0 flex-grow-1">
                                    <li>
                                        <i class="bx bx-map align-middle"></i> {{$ci->permanent_address}}
                                    </li>
                                    <li>
                                        <i class="bx bx-money align-middle"></i> ₱{{$psi->position->salary}} / {{$psi->position->jt->pay_rate}}
                                    </li>
                                    <li>
                                        <i class="bx bx-time align-middle"></i> {{$psi->position->jt->work_on_sat && $psi->position->jt->work_on_sun ? '7' : ($psi->position->jt->work_on_sat || $psi->position->jt->work_on_sun ? '6' : '5')}} days working
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-unstyled vstack gap-3 mb-0">
                                <li>
                                    <div class="d-flex">
                                        <i class="bx bx-building font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Department:</h6>
                                            <span class="text-muted">{{$psi->position->department->dep_name}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="mdi mdi-file-document-multiple-outline font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Position:</h6>
                                            <span class="text-muted">{{$psi->position->title}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="bx bx-money font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Salary:</h6>
                                            <span class="text-muted">₱{{$psi->position->salary}} / {{$psi->position->jt->pay_rate}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="bx bx-calendar font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Date Hired:</h6>
                                            {{\Carbon\Carbon::parse($psi->date_hired)->format('F j, Y')}}
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="mdi mdi-information-variant font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Status:</h6>
                                            <span class="text-muted">{{$psi->status}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="mdi mdi-briefcase-outline font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Job Type:</h6>
                                            <span class="text-muted">{{$psi->position->jt->jt_name}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="bx bx-task font-size-18 text-primary"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-semibold">Job Description:</h6>
                                            <span class="text-muted">{{$psi->position->description}}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="hstack gap-2 mt-3">
                                    <a href="{{route('profiling.employees.edit', ['id' => $user->id])}}" class="btn btn-soft-primary w-100">Edit Profile</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card" id='credit-standing'>
                        <div class="card-body">
                            <h5>Your Credit Standing</h5>
                            <p>You have <span class="fw-bold {{$user->leave_credit === 0 ? 'text-danger' : ($user->leave_credit <= 2 ? 'text-warning' : 'text-primary') }}">{{$user->leave_credit}}</span> leave credit remaining for {{\Carbon\Carbon::now()->year}}</p>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Personal Information</h5>
                            <div class="table-responsive">
                                <table class="table table-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style='width: 40%;'>First Name :</th>
                                            <td>{{$pi->first_name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Middle Name :</th>
                                            <td>{{$pi->middle_name ? $pi->middle_name : 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Last Name :</th>
                                            <td>{{$pi->last_name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Sex :</th>
                                            <td>{{$pi->sex}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Date of Birth :</th>
                                            <td>{{\Carbon\Carbon::parse($pi->date_of_birth)->format('F j, Y')}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nationality :</th>
                                            <td>{{$pi->nationality}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Contact Information</h5>
                            <div class="table-responsive">
                                <table class="table table-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style='width: 40%;'>Contact Number :</th>
                                            <td>{{$ci->contact_number}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email Address :</th>
                                            <td>{{$ci->email_address}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Permanent Address :</th>
                                            <td>{{$ci->permanent_address}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Current Address :</th>
                                            <td>{{$ci->current_address ? $ci->current_address : 'N/A'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Emergency Contact Information</h5>
                            <div class="table-responsive">
                                <table class="table table-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style='width: 40%;'>Name :</th>
                                            <td>{{$ci->ec_name ? $ci->ec_name : 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Relationship to Employee :</th>
                                            <td>{{$ci->ec_relation ? $ci->ec_relation : 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Contact Number :</th>
                                            <td>{{$ci->ec_contact_number ? $ci->ec_contact_number : 'N/A'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class='d-flex justify-content-between align-items-center'>
                                <h5>Documents</h5>
                                <button type="button" class='btn btn-warning btn-sm' data-bs-toggle="modal" data-bs-target=".document-modal">+</button>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-nowrap align-middle table-hover mb-0">
                                    <tbody>
                                        @if(isset($pd))
                                        @forelse($pd as $file)
                                        @if($file->leave_req !== null)
                                        @else
                                        <tr>
                                            <td style="width: 45px;">
                                                <div class="avatar-sm">

                                                    @if (pathinfo($file->filepath, PATHINFO_EXTENSION) === 'pdf')
                                                    <span class="avatar-title rounded-circle bg-secondary-subtle text-danger font-size-24">
                                                        <i class="bx bxs-file-pdf"></i>
                                                    </span>

                                                    @else
                                                    <span class="avatar-title rounded-circle bg-secondary-subtle text-primary font-size-24">
                                                        <i class="bx bxs-file-doc"></i>
                                                    </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{$file->details->dt_name}}</a></h5>
                                                <small>Size : {{ number_format($file->filesize / 1048576, 2) }} MB</small>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <a href="javascript: void(0);" class="text-dark me-1"><i class="bx bx-download h5 m-0"></i></a>
                                                    <a href="javascript: void(0);" class='text-danger'><i class='mdi mdi-delete h5 m-0'></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @empty
                                        <tr>
                                            <td colspan='3' class='text-center'>No documents found</td>
                                        </tr>
                                        @endforelse
                                        @else
                                        <tr>
                                            <td colspan='3'>No documents found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modals')
<!--  Document modal -->
<div class="modal fade document-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="" id='dt-create' enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value='{{$user->id}}'>
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Add Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="dt_id" class='form-label'>Document</label>
                    <div>
                        <select name="dt_id" id="dt_id" class="form-control">
                            <option value="">None Selected</option>
                            @foreach ($dts as $dt)
                            <option value="{{$dt->dt_id}}">{{$dt->dt_name}}</option>
                            @endforeach
                        </select>
                        <small id="dt_id_error" class="text-danger validations"></small>
                    </div>
                    <label for="filepath" class='form-label mt-3'>File</label>
                    <div>
                        <input type="file" name="filepath" id="filepath" class="form-control" accept=".pdf,.docx" onchange="handleFileChange()">
                        <small id="filepath_error" class="text-danger validations"></small>
                    </div>
                    <label for="filesize" class='form-label mt-3'>Filesize</label>
                    <input type="text" name="filesize" id="filesize" class="form-control" placeholder="Upload a file first">
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-sm btn-danger' data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class='btn btn-sm btn-primary'>Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade image-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" id='dt-create' enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value='{{$user->id}}'>
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Add Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file">
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-sm btn-danger' data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class='btn btn-sm btn-primary'>Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section('more_scripts')
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    function handleFileChange() {
        const fileInput = document.getElementById('filepath');
        const filesizeInput = document.getElementById('filesize');
        const file = fileInput.files[0];

        if (file) {
            const validTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const maxSize = 3 * 1024 * 1024;

            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Only .pdf or .docx files are allowed.',
                    icon: 'warning'
                });
                return;
            }

            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File size must not exceed 3MB.',
                    icon: 'warning'
                });
                return;
            }

            filesizeInput.value = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            filesizeInput.value = lastValidFileSize;
        } else {
            filesizeInput.value = lastValidFileSize;
        }
    }

    $('#dt-create').submit(function(e) {
        e.preventDefault()
        let formData = new FormData(this)
        $.ajax({
            url: '{{route("profiling.pd.create")}}',
            data: formData,
            processData: false,
            contentType: false,
            type: 'post',
            success: (response) => {
                Toast.fire({
                    title: `${response.name} was successfully uploaded`,
                    icon: 'success'
                })
            },
            error: (error) => {
                if (error.responseJSON.msgs) {
                    for (let key in error.responseJSON.msgs) {
                        $(`#${key}_error`).text('This field is required')
                    }
                }
            }
        })
    })

    // Cropper Js
    let cropper;
    const imageInput = document.getElementById('marker_image-table');
    const cropperCont = document.getElementById('photo-cropper');
    const cropBtn = document.getElementById('cropper-crop-btn');
    const cancelCropTopBtn = document.getElementById('cropper-cancel-top-btn');
    const cancelCropBotBtn = document.getElementById('cropper-cancel-bot-btn');
    const removeImageUploadBtn = document.getElementById('remove-marker-image-btn');
    $('#remove-marker-image-btn').hide();
    $('#cropper-result').hide();

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

                // Destroy the previous Cropper instance if it exists
                if (cropper) {
                    cropper.destroy();
                }

                // Initialize a new Cropper instance on the updated image
                cropper = new Cropper(cropperCont, {
                    aspectRatio: 1,
                    viewMode: 1
                });
            };

            reader.readAsDataURL(file);

            // Use Bootstrap methods to toggle modal visibility
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
        $('#marker_image-table').val('');
    })
    cancelCropBotBtn.addEventListener('click', () => {
        $('#cropper-modal').modal('toggle');
        $('#marker_image-table').val('');
    })
    cropBtn.addEventListener('click', () => {
        const croppedDataUrl = cropper.getCroppedCanvas().toDataURL();

        // Create a new Blob from the data URL
        const blob = dataURLtoBlob(croppedDataUrl);

        // Create a new File object from the Blob
        const croppedFile = new File([blob], 'cropped_image.jpg', {
            type: 'image/jpeg'
        });

        // Create a new DataTransfer object
        const dataTransfer = new DataTransfer();

        // Add the new File object to the DataTransfer object
        dataTransfer.items.add(croppedFile);

        document.getElementById('marker_image-table').files = dataTransfer.files;

        // Display the cropped image in the preview
        $('#remove-marker-image-btn').show();
        $('#cropper-result').show();
        document.getElementById('cropper-result').src = croppedDataUrl;

        // Close the modals
        $('#cropper-modal').modal('toggle');
    })
    // End of Cropper Js
</script>
@endsection