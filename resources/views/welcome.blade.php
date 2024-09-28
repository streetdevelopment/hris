<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS</title>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- App js -->
    <script src="{{ asset('assets/js/plugin.js') }}"></script>
    <!-- Welcome page css -->
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logos/Favicon.png') }}">
</head>

<body>
    <main>
        <section class='landing'>
            <div class="left">
                <div class="logo">
                    <img src="{{ asset('assets/images/logos/pixzel.png') }}" alt="Pixzel Digital Logo">
                </div>
                <div class="title">
                    <h1>Human Resource Information System</h1>
                </div>
                <div class="cta">
                    <button id='get-started' class='btn btn-sm gs-btn'>Get Started</button>
                    <button id='login-btn' class='btn btn-sm gs-btn ms-2'>Login</button>
                </div>
            </div>
            <div class="gif">
                <img src="{{ asset('assets/images/logos/HRAnim.gif') }}" alt="HRIS Animated Graphic">
            </div>
        </section>
    </main>
</body>

<div id='login-form-cont'>
    <form action="" id="login-form">
        @csrf
        <div class="outer">
            <div class='head'>
                <h1>Welcome Back!</h1>
                <p>Sign in to continue.</p>
            </div>
            <div class="inner">
                <label for="login_username">Username</label>
                <input placeholder="Username" type="text" class='form-control rounded-lg' name="login_username" id="login_username">

                <label for="login_password" class='mt-3'>Password</label>
                <input placeholder="Password" type="password" class='form-control rounded-lg' name="login_password" id="login_password">

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="remember-check">
                    <label class="form-check-label" for="remember-check">
                        Remember me
                    </label>
                </div>

                <div class="mt-3 mb-1 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                </div>

                <small class='text-muted'>Are you a company starting an account? <a id='reg-btn' href='#'>Click here</a></small>
            </div>
        </div>
    </form>
</div>

<div id="registration-form-cont">
    <form action="" id='registration-form'>
        @csrf
        <div class="outer">
            <div class='head'>
                <h1>Welcome!</h1>
                <p>Enter your company details below to begin!</p>
            </div>
            <div class="inner">
                <div id="step-1">
                    <div>
                        <label for="co_name">Company Name <span class='text-danger'>*</span></label>
                        <input type="text" placeholder="Company Name" class='form-control rounded-lg' name="co_name" id="co_name">
                        <small class="text-danger" id='co_name_error'></small>
                    </div>
                    <div>
                        <label for="address" class='mt-3'>Address <span class='text-danger'>*</span></label>
                        <input type="text" placeholder="Address" class='form-control rounded-lg' name="address" id="address">
                        <small class="text-danger" id='address_error'></small>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label for="city" class='mt-3'>City <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="City" class='form-control rounded-lg' name="city" id="city">
                            <small class="text-danger" id='city_error'></small>
                        </div>
                        <div class="col-4">
                            <label for="province" class='mt-3'>Province <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="Province" class='form-control rounded-lg' name="province" id="province">
                            <small class="text-danger" id='province_error'></small>
                        </div>
                        <div class="col-4">
                            <label for="postal_code" class='mt-3'>Postal Code <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="Postal Code" class='form-control rounded-lg' name="postal_code" id="postal_code">
                            <small class="text-danger" id='postal_code_error'></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="industry" class='mt-3'>Industry <span class='text-danger'>*</span></label>
                            <select class='form-control rounded-lg' name="industry" id="industry">
                                <option value="">None Selected</option>
                                <option value="Accounting & Advisory">Accounting & Advisory</option>
                                <option value="Banking & Financial Services">Banking & Financial Services</option>
                                <option value="Business Process Outsourcing">Business Process Outsourcing</option>
                                <option value="Charity, Social Work & Volunteering">Charity, Social Work & Volunteering</option>
                                <option value="Construction & Property Services">Construction & Property Services</option>
                                <option value="Education & Training">Education & Training</option>
                                <option value="Energy & Utilities">Energy & Utilities</option>
                                <option value="Engineering Consulting">Engineering Consulting</option>
                                <option value="Entertainment, Travel & Hospitality">Entertainment, Travel & Hospitality</option>
                                <option value="Environment & Agriculture">Environment & Agriculture</option>
                                <option value="Health">Health</option>
                                <option value="Law">Law</option>
                                <option value="Management Consulting">Management Consulting</option>
                                <option value="Media & Communications">Media & Communications</option>
                                <option value="Mining, Oil & Gas">Mining, Oil & Gas</option>
                                <option value="Pharmaceuticals">Pharmaceuticals</option>
                                <option value="R&D And Manufacturing">R&D And Manufacturing</option>
                                <option value="Recruitment & HR">Recruitment & HR</option>
                                <option value="Retail & Consumer Goods">Retail & Consumer Goods</option>
                                <option value="Technology">Technology</option>
                                <option value="Trading">Trading</option>
                                <option value="Transport & Logistics">Transport & Logistics</option>
                            </select>
                            <small class="text-danger" id='industry_error'></small>
                        </div>
                        <div class="col-6">
                            <label for="tin" class='mt-3'>TIN <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="TIN" class='form-control rounded-lg' name="tin" id="tin">
                            <small class="text-danger" id='tin_error'></small>
                        </div>
                    </div>
                </div>
                <div id="step-2" style='display: none;'>
                    <p>Create an admin account!</p>

                    <div class="row">
                        <div class="col-4">
                            <label for="first_name" class='mt-3'>First Name <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="First Name" class='form-control rounded-lg' name="first_name" id="first_name">
                            <small class="text-danger" id='first_name_error'></small>
                        </div>
                        <div class="col-4">
                            <label for="middle_name" class='mt-3'>Middle Name</label>
                            <input type="text" placeholder="Middle Name" class='form-control rounded-lg' name="middle_name" id="middle_name">
                            <small class="text-danger" id='middle_name_error'></small>
                        </div>
                        <div class="col-4">
                            <label for="last_name" class='mt-3'>Last Name <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="Last Name" class='form-control rounded-lg' name="last_name" id="last_name">
                            <small class="text-danger" id='last_name_error'></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label for="sex" class='mt-3'>Sex <span class='text-danger'>*</span></label>
                            <select name="sex" class='form-control' id="sex">
                                <option value="">None Selected</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <small class="text-danger" id='sex_error'></small>
                        </div>
                        <div class="col-4">
                            <label for="nationality" class='mt-3'>Nationality</label>
                            <input type="text" placeholder="Nationality" class='form-control rounded-lg' name="nationality" id="nationality">
                            <small class="text-danger" id='nationality_error'></small>
                        </div>
                        <div class="col-4">
                            <label for="permanent_address" class='mt-3'>Permanent Address <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="Permanent Address" class='form-control rounded-lg' name="permanent_address" id="permanent_address">
                            <small class="text-danger" id='permanent_address_error'></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="date_of_birth" class='mt-3'>Date of Birth <span class='text-danger'>*</span></label>
                            <input type="date" placeholder="Date of Birth" class='form-control rounded-lg' name="date_of_birth" id="date_of_birth">
                            <small class="text-danger" id='date_of_birth_error'></small>
                        </div>
                        <div class="col-6">
                            <label for="contact_number" class='mt-3'>Contact Number <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="Contact Number" class='form-control rounded-lg' name="contact_number" id="contact_number">
                            <small class="text-danger" id='contact_number_error'></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="email_address" class='mt-3'>Email Address <span class='text-danger'>*</span></label>
                            <input type="text" placeholder="Email Address" class='form-control rounded-lg' name="email_address" id="email_address">
                            <small class="text-danger" id='email_address_error'></small>
                        </div>
                        <div class="col-6">
                            <label for="password" class='mt-3'>Password <span class='text-danger'>*</span></label>
                            <input type="password" placeholder="Password" class='form-control rounded-lg' name="password" id="password">
                            <small class="text-danger" id='password_error'></small>
                        </div>
                    </div>
                </div>

                <div class="mt-3 mb-1 d-grid">
                    <button id='next-btn' class="btn btn-primary waves-effect waves-light" type="button">Next</button>
                    <div class="row">
                        <div class="col-6">
                            <div class='d-grid'>
                                <button id='prev-btn' style='display: none;' class="btn btn-primary waves-effect waves-light" type="button">Prev</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class='d-grid'>
                                <button id='submit-btn' style='display: none;' class="btn btn-warning waves-effect waves-light" type="submit">Create Account</button>
                            </div>
                        </div>
                    </div>

                </div>

                <small class='text-muted'>Already have an account? <a id='re-log-btn' href='#'>Login here</a></small>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}">
</script>

<script>
    $(document).ready(function() {
        function toggleLoginForm() {
            if ($('#login-form-cont').css('display') == 'none') {
                if ($('#registration-form-cont').css('display') !== 'none') {
                    $('#registration-form-cont').show().animate({
                        left: '-150%'
                    });
                }
                $('#login-form-cont').show().animate({
                    left: '50%'
                });
            } else {
                if ($('#registration-form-cont').css('display') == '') {
                    $('#registration-form-cont').show().animate({
                        left: '-150%'
                    });
                }
                $('#login-form-cont').animate({
                    left: '150%'
                }, function() {
                    $('#login-form-cont').hide()
                });
            }
        }
        $('#login-btn').click(function() {
            toggleLoginForm()
        })
        $('#reg-btn, #get-started').click(function() {
            if ($('#login-form-cont').css('display') == 'none') {
                $('#registration-form-cont').show().animate({
                    left: '50%'
                });
            } else {
                $('#login-form-cont').animate({
                    left: '150%'
                }, function() {
                    $('#login-form-cont').hide()
                });
                $('#registration-form-cont').show().animate({
                    left: '50%'
                });
            }
        })
        $('#re-log-btn').click(function() {
            toggleLoginForm()
        });
        $('#next-btn, #prev-btn').click(function() {
            $('#step-1').toggle();
            $('#step-2').toggle();
            $('#next-btn').toggle();
            $('#prev-btn').toggle();
            $('#submit-btn').toggle();
        })
        $('#registration-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route("registration.admin.submit") }}',
                data: formData,
                contentType: false,
                processData: false,
                type: 'post',
                success: (response) => {
                    if (response.message == 'Registration successful') {
                        window.location.href = response.url;
                    } else {
                        alert(response.message)
                    }
                },
                error: (error) => {
                    for (let key in error.responseJSON.messages) {
                        key == 'co_name' ? $(`#${key}_error`).text('The company name field is required') : $(`#${key}_error`).text(error.responseJSON.messages[key][0])
                    }
                }
            })
        });
        $('#login-form').submit(function(e) {
            e.preventDefault()
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route("login.attempt") }}',
                data: formData,
                contentType: false,
                processData: false,
                type: 'post',
                success: (response) => {
                    window.location.href = '{{ route("dashboard") }}'
                },
                error: (error) => {
                    alert('Unsuccessful Login!')
                }
            })
        })
    })
</script>

</html>