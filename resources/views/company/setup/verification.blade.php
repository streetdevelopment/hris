<html lang="en" data-bs-theme="light">
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
</style>
@endsection
@include('layouts.head')

<body>
    <a type='button' id='logout-btn' href='{{ route("registration.admin.logout") }}' class='btn btn-sm btn-danger'>Logout</a>
    <div class=" account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5 text-muted">
                        <a href="index.html" class="d-block auth-logo">
                            <img src="{{ asset('assets/images/logos/HRIS.png' ) }}" alt="" height="50" class="auth-logo-dark mx-auto bg-white rounded">
                            <img src="{{ asset('assets/images/logos/HRIS.png') }}" alt="" height="50" class="auth-logo-light mx-auto">
                        </a>
                        <p class="mt-3"><span class='bg-white px-2 py-1 rounded'>Human Resources Information System</span></p>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="card-body">

                            <div class="p-2">
                                <div class="text-center">

                                    <div class="avatar-md mx-auto">
                                        <div class="avatar-title rounded-circle bg-light">
                                            <i class="bx bxs-envelope h1 mb-0 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <h4>Verify your email</h4>
                                        <p>We have sent you verification email to <span class="fw-semibold">{{Auth()->user()->email}}</span>, Please check it</p>
                                        <div class="mt-4">
                                            <a href="https://mail.google.com/mail/u/0/" class="btn btn-success w-md">Verify email</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p class='text-light'>Didn't receive an email ? <a href="#" class="fw-medium text-light text-decoration-underline"> Resend </a> </p>
                        <p class='text-light'>© 2024 Pixzel Digital. Crafted with <i class="mdi mdi-heart text-danger"></i> by Caballero, S.J.</p>
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


</body>

</html>