@section('title', 'HRIS - Email Verification')
@section('more_links')
<style>
    body {
        background-color: #365CE4;
    }

    #logout-btn {
        position: absolute;
        top: 1%;
        right: 2.5%;
    }

    .card-content {
        transition: ease-in;
    }
</style>
@endsection
@include('layouts.head')

<body>
    <div id='spinner' class='d-flex justify-content-center align-items-center' style='height: 100vh; width: 100vw; position:absolute; z-index: 2; visibility: hidden;'>
        <div class="card" style='width: 30%; height: 100px;'>
            <div class="card-body rounded bg-dark d-flex flew-row gap-3 align-items-center justify-content-center">
                <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h4 id='spinner-text' class='text-light' style='margin: 0;'>Creating department...</h4>
            </div>
        </div>
    </div>
    <a type='button' href='{{ route("dashboard") }}' class='btn btn-sm btn-danger' style="position: absolute;top: 1.5%; left: 1%;">Back</a>
    <div class="container d-flex justify-content-center align-items-center" style='height: 100vh;'>
        <div class="card" style='width: 50%;'>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mb-3 text-muted">
                            <a href="index.html" class="d-block auth-logo">
                                <img src="{{ asset('assets/images/logos/HRIS.png' ) }}" alt="" height="50" class="auth-logo-dark mx-auto bg-white rounded">
                                <img src="{{ asset('assets/images/logos/HRIS.png') }}" alt="" height="50" class="auth-logo-light mx-auto">
                            </a>
                            <p class="mt-3"><span class='bg-white px-2 py-1 rounded'>Setup your admin account!</span></p>
                        </div>
                    </div>
                </div>
                <div class="row card-content" id='card-content-1'>
                    <p class='text-center mt-2'>As an admin, we assume you are also an employee. To ensure our records are accurate, we need you to provide information about your department and position. Please create the department you are in and specify your position within that department by completing the fields below.</p>
                </div>
                <div class="row card-content" id='card-content-2' style='display: none;'>
                    <form action="" id='form-a'>
                        @csrf
                        <p class='text-center mb-2'>Add your department.</p>
                        <select name="existing_department" id="existing_department" class='form-control mb-2'>
                            <option value="new">Create New</option>
                            @forelse($departments as $dep)
                            <option value="{{$dep->dep_id}}">{{$dep->dep_name}}</option>
                            @empty
                            <p>No records found</p>
                            @endforelse
                        </select>
                        <div id='new-fields'>
                            <div class='d-flex justify-content-center flex-column'>
                                <div class="row mb-2 d-flex justify-content-center">
                                    <div class="col-6">
                                        <label for="co_id" class="col-form-label">Company</label>
                                        <input id="co_id" name="co_id" type="text" class="form-control bg-light" data-id='{{Auth()->user()->co_id}}' value='{{Auth()->user()->company->co_name}}' readonly>
                                    </div>
                                    <div class="col-6">
                                        <label for="dep_name" class="col-form-label">Department Name <span class="text-danger">*</span></label>
                                        <input id="dep_name" name="dep_name" type="text" class="form-control" placeholder="Department name">
                                        <small class="text-danger validations" id="dep_name_error"></small>
                                    </div>
                                </div>
                                <div class="row mb-2 d-flex justify-content-center">
                                    <div class="col-6">
                                        <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                                        <select name="dep_status" id="dep-status" class='form-control'>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                        <small class="text-danger validations" id="dep_status_error"></small>
                                    </div>
                                    <div class="col-6">
                                        <label for="location" class="col-form-label">Location</label>
                                        <input id="location" name="location" type="text" class="form-control" placeholder="Location">
                                    </div>
                                </div>
                                <div class="row mb-2 d-flex justify-content-center">
                                    <div class="col-6">
                                        <label for="phone_number" class="col-form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input id="phone_number" name="phone_number" type="text" class="form-control" placeholder="Department Phone Number">
                                        <small class="text-danger validations" id="phone_number_error"></small>
                                    </div>
                                    <div class="col-6">
                                        <label for="email_address" class="col-form-label">Email Address <span class="text-danger">*</span></label>
                                        <input id="dep-email_address" name="dep_email_address" type="text" class="form-control" placeholder="Department Email Address">
                                        <small class="text-danger validations" id="dep_email_address_error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row card-content" id='card-content-3' style='display: none;'>
                    <p class='text-center'>Tell us more about your position</p>
                    <form action="" id='form-c'>
                        <div class='d-flex justify-content-center flex-column'>
                            <div class="row mb-2 d-flex justify-content-center">
                                <div class="col-4">
                                    <label for="title" class="col-form-label">Job Title <span class="text-danger">*</span></label>
                                    <input id="title" name="title" type="text" class="form-control" placeholder="Job title">
                                    <small class="text-danger validations" id="title_error"></small>
                                </div>
                                <div class="col-4">
                                    <label for="jt_id" class="col-form-label">Job Type</label>
                                    <select name="jt_id" id="jt_id" class="form-control">
                                        @forelse (Auth()->user()->company->jobtypes as $jt)
                                        <option value="{{$jt->jt_id}}">{{$jt->jt_name}}</option>
                                        @empty
                                        <option value="">No records found. Please setup your company job types first.</option>
                                        @endforelse
                                    </select>
                                    <small class="text-danger validations" id="middle_name_error"></small>
                                </div>
                                <div class="col-4">
                                    <label for="salary" class="col-form-label">Salary <span class="text-danger">*</span></label>
                                    <input id="salary" name="salary" type="number" step='0.01' class="form-control" placeholder="Salary">
                                    <small class="text-danger validations" id="salary_error"></small>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex justify-content-center">
                                <div class="col-6">
                                    <label for="date_hired" class="col-form-label">Date Hired <span class="text-danger">*</span></label>
                                    <input id="date_hired" name="date_hired" type="date" class="form-control">
                                    <small class="text-danger validations" id="date_hired_error"></small>
                                </div>
                                <div class="col-6">
                                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="pos-status" class='form-control'>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                    <small class="text-danger validations" id="date_hired_error"></small>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex justify-content-center">
                                <div class="col-12">
                                    <label for="description" class="col-form-label">Job Description</label>
                                    <textarea id="description" name="description" row='2' class="form-control" placeholder="Job description"></textarea>
                                    <small class="text-danger validations" id="title_error"></small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class='d-flex justify-content-center gap-2 my-2'>
                        <button type="button" class='btn btn-sm btn-warning' id='prev-btn' page=1 style='display:none;'>Prev</button>
                        <button type="button" class='btn btn-sm btn-primary' id='next-btn' page=1>Next</button>
                        <button type="button" class='btn btn-sm btn-primary' id='save-btn' style='display: none;'>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        function validateFields() {
            return new Promise((resolve, reject) => {
                var formData = $('#form-a').serialize()
                $.ajax({
                    url: '{{ route("company.setup.validate_fields") }}',
                    data: formData,
                    success: (response) => {
                        if (response.status === false) {
                            resolve(response.msgs)
                        } else {
                            resolve(response.status);
                        }
                    },
                    error: (error) => {}
                });
            });
        }

        function removeValidations() {
            $('.validations').text('')
        }

        $(document).ready(function() {
            $('#next-btn').click(async function() {
                var page = parseInt($(this).attr('page'));
                $(`#card-content-${page}`).fadeOut(async () => {
                    if (page + 1 === 3) {
                        const result = await validateFields();
                        if (result === true) {
                            removeValidations()
                            $(`#card-content-${page + 1}`).fadeIn();
                            $(this).attr('page', page + 1);
                            $('#prev-btn').show();
                            if ($(`#card-content-${page + 2}`).length === 0) {
                                $('#next-btn').hide();
                                $('#save-btn').show();
                            }
                        } else {
                            $(`#card-content-${page}`).fadeIn();
                            removeValidations()
                            for (let key in result) {
                                $(`#${key}_error`).text('This field is required')
                            }
                        }
                    } else {
                        removeValidations()
                        $(`#card-content-${page + 1}`).fadeIn();
                        $(this).attr('page', page + 1);
                        $('#prev-btn').show();
                        if ($(`#card-content-${page + 2}`).length === 0) {
                            $('#next-btn').hide();
                        }
                    }
                });
            });

            $('#prev-btn').click(function() {
                var page = parseInt($('#next-btn').attr('page'));
                $(`#card-content-${page}`).fadeOut(() => {
                    $(`#card-content-${page - 1}`).fadeIn();
                    $('#next-btn').attr('page', page - 1);
                    if (page - 1 === 1) {
                        $('#prev-btn').hide();
                    }
                    $('#next-btn').show();
                    $('#save-btn').hide();
                });
            });

            $('#save-btn').click(function() {
                var formDataA = $('#form-a').serialize();
                var formDataB = $('#form-b').serialize();
                var formDataC = $('#form-c').serialize();

                var formData = formDataA + '&' + formDataB + '&' + formDataC;
                $.ajax({
                    url: '{{route("company.setup.submit")}}',
                    data: formData,
                    success: function(response) {
                        removeValidations();
                        $('#spinner').css('visibility', 'visible');
                        setTimeout(function() {
                            $('#spinner-text').text('Creating position...');
                            setTimeout(function() {
                                $('#spinner-text').text('Creating employee details...');
                                setTimeout(function() {
                                    $('#spinner-text').text('Redirecting to dashboard');
                                    setTimeout(function() {
                                        window.location.href = '{{route("dashboard")}}';
                                    }, 1000);
                                }, 1000);
                            }, 1000);
                        }, 1000);
                    },
                    error: function(error) {
                        removeValidations();
                        for (let key in error.responseJSON.msgs) {
                            $(`#${key}_error`).text(error.responseJSON.msgs[key]);
                        }
                    }
                });
            });

            $('#first_name, #last_name').change(function() {
                var firstName = $('#first_name').val().replace(/\s+/g, '').toLowerCase();
                var lastName = $('#last_name').val().replace(/\s+/g, '').toLowerCase();

                $('#username').val(`${firstName}.${lastName}`);
            });

            $('#existing_department').change(function() {
                if ($(this).val() == 'new') {
                    $('#new-fields').show()
                } else {
                    $('#new-fields').hide()
                }
            })
        });
    </script>



</body>

</html>