<!DOCTYPE html>
<html lang="en">

<head>
    <title>eDharti | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="assets/fonts/font-awesome/css/font-awesome.min.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="assets/fonts/flaticon/font/flaticon.css">

    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.css" rel="stylesheet">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('assets/frontend/assets/img/favicon.ico') }}" type="image/x-icon">

    <!-- Google fonts -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800%7CPoppins:400,500,700,800,900%7CRoboto:100,300,400,400i,500,700">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/frontend/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" id="style_sheet"
        href="{{ asset('assets/frontend/assets/css/skins/default.css') }}">
    <link rel="stylesheet" type="text/css" id="style_sheet" href="{{ asset('assets/frontend/assets/css/custom.css') }}">
    <style>
        /* Initially hide the form */
        #emailLoginForm {
            display: none;
        }

        #emailInput {
            display: block;
        }

        #dividerLogin {
            display: block;
        }

        #mobileOtp {
            display: none;
        }

        #LoginWithOTP {
            display: none;
        }

        #mobileInput {
            display: block;
        }

        .text-divider {
            margin: 2em 0;
            line-height: 0;
            text-align: center;
        }

        .text-divider span {
            background-color: #116d6e;
            padding: 7px;
            border-radius: 50%;
            color: #ffffff;
            font-size: 14px;
            position: relative;
        }

        .text-divider:before {
            content: " ";
            display: block;
            border-top: 1px solid #e3e3e3;
            border-bottom: 1px solid #f7f7f7;
            position: relative;
            z-index: -1;
        }

        .text-divider span::after {
            position: absolute;
            width: 50px;
            height: 5px;
            content: '';
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            z-index: -1;
        }
    </style>

</head>

<body id="top">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TAGCODE" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="page_loader"></div>

    <!-- Login 8 start -->
    <nav class="navbar fixed-top sticky-shadow">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-7">
                    <a class="navbar-brand" href="#">
                        <img src="{{ asset('assets/frontend/assets/img/LDOLogo-white.png') }}"
                            alt="Land and Development Office" height="60">
                    </a>
                </div>
                <div class="col-lg-6 col-5 text-end">
                    <div class="d-flex justify-content-end"><img
                            src="{{ asset('assets/frontend/assets/img/eDharti-Logo-white.png') }}" alt="logo"
                            class="edharti-logo"><span class="text-white">2.0</span></div>
                </div>
            </div>
        </div>
    </nav>

    <div class="alert alert-success border-0 bg-success alert-dismissible">
        <div class="text-white">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @yield('content')

    <footer>
        <span>Copyright &copy; 2024. All Rights Reserved.</span>
    </footer>
    <!-- Login 8 end -->
    <!-- Begin OTP -->
    <!-- Button trigger modal -->


    <!-- Mobile OTP Modal -->
    <div class="modal fade" id="otpMobile" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="otp-title">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Mobile Phone Verification</h1>
                        <p class="otp-description">Enter the 4-digit verification code that was sent to your phone
                            number.</p>
                    </div>
                    <div class="text-danger text-center" id="mobileOptVerifyError"></div>
                    <div class="text-success text-center" id="mobileOptVerifySuccess"></div>
                    <form action="#" id="otp-form">
                        <div class="otp-receive-container">
                            <div class="otp_input_groups">
                                <input type="text" class="otp_input" autofocus pattern="\d*" maxlength="1" />
                                <input type="text" class="otp_input" maxlength="1" />
                                <input type="text" class="otp_input" maxlength="1" />
                                <input type="text" class="otp_input" maxlength="1" />
                            </div>
                            <button type="button" id="verifyMobileOtpBtn" class="btn otp_verify_btn">Verify Mobile
                                Number</button>
                            <p class="resent_otp">Didn't receive code? <a href="#">Resend</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Email OTP Modal -->
    <div class="modal fade" id="otpEmail" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="otp-title">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Email Verification</h1>
                        <p class="otp-description">Enter the 4-digit verification code that was sent to your email.</p>
                    </div>
                    <div class="text-danger text-center" id="emailOptVerifyError"></div>
                    <div class="text-success text-center" id="emailOptVerifySuccess"></div>
                    <form action="#" id="otp-form-email">
                        <div class="otp-receive-container">
                            <div class="otp_input_groups">
                                <input type="text" class="otp_input_email otp_input" autofocus pattern="\d*"
                                    maxlength="1" />
                                <input type="text" class="otp_input_email otp_input" maxlength="1" />
                                <input type="text" class="otp_input_email otp_input" maxlength="1" />
                                <input type="text" class="otp_input_email otp_input" maxlength="1" />
                            </div>
                            <button type="button" id="verifyEmailOtpBtn" class="btn otp_verify_btn">Verify
                                Email</button>
                            <p class="resent_otp">Didn't receive code? <a href="#">Resend</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Organization Mobile OTP Modal -->
    <div class="modal fade" id="orgOtpMobile" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="otp-title">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Mobile Phone Verification</h1>
                        <p class="otp-description">Enter the 4-digit verification code that was sent to your phone
                            number.</p>
                    </div>
                    <div class="text-danger text-center" id="orgMobileOptVerifyError"></div>
                    <div class="text-success text-center" id="orgMobileOptVerifySuccess"></div>
                    <form action="#" id="org-otp-form">
                        <div class="otp-receive-container">
                            <div class="otp_input_groups">
                                <input type="text" class="otp_input org_mobile_otp_nput" autofocus pattern="\d*"
                                    maxlength="1" />
                                <input type="text" class="otp_input org_mobile_otp_nput" maxlength="1" />
                                <input type="text" class="otp_input org_mobile_otp_nput" maxlength="1" />
                                <input type="text" class="otp_input org_mobile_otp_nput" maxlength="1" />
                            </div>
                            <button type="button" id="orgVerifyMobileOtpBtn" class="btn otp_verify_btn">Verify
                                Mobile Number</button>
                            <p class="resent_otp">Didn't receive code? <a href="#">Resend</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Organization Email OTP Modal -->
    <div class="modal fade" id="orgOtpEmail" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="otp-title">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Email Verification</h1>
                        <p class="otp-description">Enter the 4-digit verification code that was sent to your email.</p>
                    </div>
                    <div class="text-danger text-center" id="orgEmailOptVerifyError"></div>
                    <div class="text-success text-center" id="orgEmailOptVerifySuccess"></div>
                    <form action="#" id="org-otp-form-email">
                        <div class="otp-receive-container">
                            <div class="otp_input_groups">
                                <input type="text" class="org_otp_input_email otp_input" autofocus pattern="\d*"
                                    maxlength="1" />
                                <input type="text" class="org_otp_input_email otp_input" maxlength="1" />
                                <input type="text" class="org_otp_input_email otp_input" maxlength="1" />
                                <input type="text" class="org_otp_input_email otp_input" maxlength="1" />
                            </div>
                            <button type="button" id="orgVerifyEmailOtpBtn" class="btn otp_verify_btn">Verify
                                Email</button>
                            <p class="resent_otp">Didn't receive code? <a href="#">Resend</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- External JS libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/frontend/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/otp-input.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/appointment-validation.js') }}"></script>
    <script src="{{ asset('assets/frontend/assets/js/custom.js') }}"></script>
    <!-- Custom JS Script -->
    <script>
        function isValidMobile(mobile) {
            var mobilePattern = /^[0-9]{10}$/;
            return mobilePattern.test(mobile);
        }

        function isValidEmail(email) {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }
        $(document).ready(function() {
            var selectedRadio0 = null;
            var selectedRadio = null;

            $('.radio_input_0').change(function() {
                selectedRadio0 = $(this).val();
                if (selectedRadio0) {
                    $('.radio-buttons').show()
                    $('#continueButton').show()
                    $('#important_news_container').hide();
                    $('.radio-buttons').show();
                    $('.backButton').show();
                    $("#radioErr").removeClass('d-inline')
                    $("#radioErr").addClass('d-none')
                } else {
                    $("#radioErr").addClass('d-inline')
                    $("#radioErr").removeClass('d-none')
                }
            });
            $('.radio_input').change(function() {
                selectedRadio = $(this).val();
                $('#title3d').hide()
                $('#title2').show()
                $('#important_news_container').hide();
                $('.hide-after-select-usertype').hide();
                $('#formColumnIncrease').attr('class', 'col-lg-12 form-section');
                $('#' + selectedRadio + 'Div').show();
                $('#IndsubmitButton').show();
                $('#OrgsubmitButton').show();
                $('.backButton').show();
                $("#radioErr").removeClass('d-inline')
                $("#radioErr").addClass('d-none')
            });
            $('#propertyowner').change(function() {
                $('#propertyownerDiv').show();
                $('#organizationDiv').hide();
            });
            $('#organization').change(function() {
                $('#propertyownerDiv').hide();
                $('#organizationDiv').show();
            });


        });

        // Yes/No Do you Know Property ID?
        $(document).ready(function() {
            $('#Yes').change(function() {
                if ($(this).is(':checked')) {
                    $('#ifyes').show();
                    $('#locality').val('')
                    $('#block').val('')
                    $('#plot').val('')
                    $('#knownas').val('')
                    $('#landUse').val('');
                    $('#landUseSubtype').val('');
                    $('#ifYesNotChecked').hide();
                } else {
                    $('#localityFill').val('')
                    $('#blocknoInvFill').val('')
                    $('#plotnoInvFill').val('')
                    $('#knownasInvFill').val('')
                    $('#landUseInvFill').val('')
                    $('#landUseSubtypeInvFill').val('')
                    $('#ifyes').hide();
                    $('#ifYesNotChecked').show();
                }
            });

            $('#YesOrg').change(function() {
                if ($(this).is(':checked')) {
                    $('#ifyesOrg').show();
                    $('#locality_org').val('')
                    $('#block_org').val('')
                    $('#plot_org').val('')
                    $('#knownas_org').val('')
                    $('#landUseOrg').val('')
                    $('#landUseSubtypeOrg').val('')
                    $('#ifYesNotCheckedOrg').hide();
                } else {
                    $('#localityOrgFill').val('')
                    $('#blocknoOrgFill').val('')
                    $('#plotnoOrgFill').val('')
                    $('#knownasOrgFill').val('')
                    $('#landUseOrgFill').val('')
                    $('#landUseSubtypeOrgFill').val('')
                    $('#ifyesOrg').hide();
                    $('#ifYesNotCheckedOrg').show();
                }
            });
        });

        $(document).ready(function() {
            $(".numericOnly").on("input", function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ""));
            });
            //for verifying mobile
            $('#verify_mobile_otp').click(function() {
                console.log('called');
                var mobile = $('#mobileInv').val().trim();
                var emailToVerify = $('#emailInv').val().trim();
                var errorDiv = $('#verify_mobile_otp_error');
                var successDiv = $('#verify_mobile_otp_success');
                if (mobile == '') {
                    errorDiv.html('Mobile number is required')
                } else if (!isValidMobile(mobile)) {
                    errorDiv.html('Invalid mobile number');
                } else {
                    $('#verify_mobile_otp').hide();
                    $('#mobile_loader').show();
                    $.ajax({
                        url: "{{ route('saveOtp') }}",
                        type: "POST",
                        data: {
                            emailToVerify: emailToVerify,
                            mobile: mobile,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                $('#verify_mobile_otp').show();
                                $('#mobile_loader').hide();
                                errorDiv.html('')
                                successDiv.html(result.message)
                                $('#otpMobile').modal("show")
                                // $("#otpMobile").css("display", "block");
                            } else {
                                $('#verify_mobile_otp').show();
                                $('#mobile_loader').hide();
                                successDiv.html('')
                                errorDiv.html(result.message)
                            }
                        }
                    });
                }

            });

            //for verifying email
            $('#verify_email_otp').click(function() {
                var email = $('#emailInv').val().trim();
                var mobileToVerify = $('#mobileInv').val().trim();
                var errorDiv = $('#verify_email_otp_error');
                var successDiv = $('#verify_email_otp_success');
                if (email == '') {
                    errorDiv.html('Email is required')
                } else if (!isValidEmail(email)) {
                    errorDiv.html('Invalid Email');
                } else {
                    $('#verify_email_otp').hide();
                    $('#email_loader').show();
                    $.ajax({
                        url: "{{ route('saveOtp') }}",
                        type: "POST",
                        data: {
                            email: email,
                            mobileToVerify: mobileToVerify,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                $('#verify_email_otp').show();
                                $('#email_loader').hide();
                                errorDiv.html('')
                                successDiv.html(result.message)
                                $('#otpEmail').modal("show")

                                // $("#otpMobile").css("display", "block");
                            } else {
                                $('#verify_email_otp').show();
                                $('#email_loader').hide();
                                successDiv.html('')
                                errorDiv.html(result.message)
                            }
                        }
                    });
                }
            });

            //for organization
            //for verifying mobile
            $('#org_verify_mobile_otp').click(function() {
                var mobile = $('#authsignatory_mobile').val().trim();
                var emailToVerify = $('#emailauthsignatory').val().trim();
                var errorDiv = $('#org_verify_mobile_otp_error');
                var successDiv = $('#org_verify_mobile_otp_success');
                if (mobile == '') {
                    errorDiv.html('Mobile number is required')
                } else if (!isValidMobile(mobile)) {
                    errorDiv.html('Invalid mobile number');
                } else {
                    $('#org_verify_mobile_otp').hide();
                    $('#org_mobile_loader').show();
                    $.ajax({
                        url: "{{ route('saveOtp') }}",
                        type: "POST",
                        data: {
                            emailToVerify: emailToVerify,
                            mobile: mobile,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                $('#org_verify_mobile_otp').show();
                                $('#org_mobile_loader').hide();
                                errorDiv.html('')
                                successDiv.html(result.message)
                                $('#orgOtpMobile').modal("show")
                                // $("#otpMobile").css("display", "block");
                            } else {
                                $('#org_verify_mobile_otp').show();
                                $('#org_mobile_loader').hide();
                                successDiv.html('')
                                errorDiv.html(result.message)
                            }
                        }
                    });
                }

            });

            //for verifying email
            $('#org_verify_email_otp').click(function() {
                console.log("Called");
                var email = $('#emailauthsignatory').val().trim();
                var mobileToVerify = $('#authsignatory_mobile').val().trim();
                var errorDiv = $('#org_verify_email_otp_error');
                var successDiv = $('#org_verify_email_otp_success');
                if (email == '') {
                    errorDiv.html('Email is required')
                } else if (!isValidEmail(email)) {
                    errorDiv.html('Invalid Email');
                } else {
                    $('#org_verify_email_otp').hide();
                    $('#org_email_loader').show();
                    $.ajax({
                        url: "{{ route('saveOtp') }}",
                        type: "POST",
                        data: {
                            email: email,
                            mobileToVerify: mobileToVerify,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                $('#org_verify_email_otp').show();
                                $('#org_email_loader').hide();
                                errorDiv.html('')
                                successDiv.html(result.message)
                                $('#orgOtpEmail').modal("show")

                                // $("#otpMobile").css("display", "block");
                            } else {
                                $('#org_verify_email_otp').show();
                                $('#org_email_loader').hide();
                                successDiv.html('')
                                errorDiv.html(result.message)
                            }
                        }
                    });
                }

            });


            $("#verifyMobileOtpBtn").click(function() {
                var otpDigits = [];
                var verifyMobileBtn = $('#verify_mobile_otp')
                var mobileVerifyErrorDiv = $('#mobileOptVerifyError');
                var mobileVerifySuccessDiv = $('#mobileOptVerifySuccess');
                var errorDiv = $('#verify_mobile_otp_error');
                var successDiv = $('#verify_mobile_otp_success');
                $(".otp_input").each(function() {
                    otpDigits.push($(this).val());
                });
                var mobileOtp = otpDigits.join('');
                var mobile = $('#mobileInv').val();

                if (mobile != '' && mobileOtp != '') {
                    $.ajax({
                        url: "{{ route('verifyOtp') }}",
                        method: 'POST',
                        data: {
                            mobileOtp: mobileOtp,
                            mobile: mobile,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                verifyMobileBtn.hide()
                                errorDiv.html('')
                                successDiv.html('')
                                mobileVerifyErrorDiv.html('')
                                $("#mobileInv").prop("readonly", true);
                                $('#green-tick-icon').css("display", "block");
                                $('#otp-form')[0].reset();
                                mobileVerifySuccessDiv.html(response.message)
                                $('#otpMobile').modal("hide")
                                var Indmobile = document.getElementById('mobileInv');
                                Indmobile.setAttribute('data-id', '1');
                            } else {
                                mobileVerifySuccessDiv.html('')
                                mobileVerifyErrorDiv.html(response.message)
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error response (e.g., show error message)
                            alert('Error verifying OTP');
                        }
                    });
                } else {
                    mobileVerifySuccessDiv.html('')
                    mobileVerifyErrorDiv.html('Please enter Otp')
                }

            });


            $("#verifyEmailOtpBtn").click(function() {
                var otpDigits = [];
                var verifyMobileBtn = $('#verify_email_otp')
                var emailVerifyErrorDiv = $('#emailOptVerifyError');
                var emailVerifySuccessDiv = $('#emailOptVerifySuccess');
                var errorDiv = $('#verify_email_otp_error');
                var successDiv = $('#verify_email_otp_success');
                $(".otp_input_email").each(function() {
                    otpDigits.push($(this).val());
                });
                var emailOtp = otpDigits.join('');
                var email = $('#emailInv').val();

                if (email != '' && emailOtp != '') {
                    $.ajax({
                        url: "{{ route('verifyOtp') }}",
                        method: 'POST',
                        data: {
                            emailOtp: emailOtp,
                            email: email,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                verifyMobileBtn.hide()
                                errorDiv.html('')
                                successDiv.html('')
                                emailVerifyErrorDiv.html('')
                                $("#emailInv").prop("readonly", true);
                                $('#green-tick-icon-email').css("display", "block");
                                $('#otp-form-email')[0].reset();
                                emailVerifySuccessDiv.html(response.message)
                                $('#otpEmail').modal("hide")
                                var emailInv = document.getElementById('emailInv');
                                emailInv.setAttribute('data-id', '1');
                            } else {
                                emailVerifySuccessDiv.html('')
                                emailVerifyErrorDiv.html(response.message)
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error response (e.g., show error message)
                            alert('Error verifying OTP');
                        }
                    });
                } else {
                    emailVerifySuccessDiv.html('')
                    emailVerifyErrorDiv.html('Please enter Otp')
                }

            });


            //for organization
            $("#orgVerifyMobileOtpBtn").click(function() {
                var otpDigits = [];
                var verifyMobileBtn = $('#org_verify_mobile_otp')
                var mobileVerifyErrorDiv = $('#orgMobileOptVerifyError');
                var mobileVerifySuccessDiv = $('#orgMobileOptVerifySuccess');
                var errorDiv = $('#org_verify_mobile_otp_error');
                var successDiv = $('#org_verify_mobile_otp_success');
                $(".org_mobile_otp_nput").each(function() {
                    otpDigits.push($(this).val());
                });
                var mobileOtp = otpDigits.join('');
                var mobile = $('#authsignatory_mobile').val();

                if (mobile != '' && mobileOtp != '') {
                    $.ajax({
                        url: "{{ route('verifyOtp') }}",
                        method: 'POST',
                        data: {
                            mobileOtp: mobileOtp,
                            mobile: mobile,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                verifyMobileBtn.hide()
                                errorDiv.html('')
                                successDiv.html('')
                                mobileVerifyErrorDiv.html('')
                                $("#authsignatory_mobile").prop("readonly", true);
                                $('#org_green-tick-icon').css("display", "block");
                                $('#org-otp-form')[0].reset();
                                mobileVerifySuccessDiv.html(response.message)
                                $('#orgOtpMobile').modal("hide")
                                var Indmobile = document.getElementById('authsignatory_mobile');
                                Indmobile.setAttribute('data-id', '1');
                            } else {
                                mobileVerifySuccessDiv.html('')
                                mobileVerifyErrorDiv.html(response.message)
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error response (e.g., show error message)
                            alert('Error verifying OTP');
                        }
                    });
                } else {
                    mobileVerifySuccessDiv.html('')
                    mobileVerifyErrorDiv.html('Please enter Otp')
                }

            });


            $("#orgVerifyEmailOtpBtn").click(function() {
                var otpDigits = [];
                var verifyMobileBtn = $('#org_verify_email_otp')
                var emailVerifyErrorDiv = $('#orgEmailOptVerifyError');
                var emailVerifySuccessDiv = $('#orgEmailOptVerifySuccess');
                var errorDiv = $('#org_verify_email_otp_error');
                var successDiv = $('#org_verify_email_otp_success');
                $(".org_otp_input_email").each(function() {
                    otpDigits.push($(this).val());
                });
                var emailOtp = otpDigits.join('');
                var email = $('#emailauthsignatory').val();

                if (email != '' && emailOtp != '') {
                    $.ajax({
                        url: "{{ route('verifyOtp') }}",
                        method: 'POST',
                        data: {
                            emailOtp: emailOtp,
                            email: email,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                verifyMobileBtn.hide()
                                errorDiv.html('')
                                successDiv.html('')
                                emailVerifyErrorDiv.html('')
                                $("#emailauthsignatory").prop("readonly", true);
                                $('#org_green-tick-icon-email').css("display", "block");
                                $('#org-otp-form-email')[0].reset();
                                emailVerifySuccessDiv.html(response.message)
                                $('#orgOtpEmail').modal("hide")
                                var emailInv = document.getElementById('emailauthsignatory');
                                emailInv.setAttribute('data-id', '1');
                            } else {
                                emailVerifySuccessDiv.html('')
                                emailVerifyErrorDiv.html(response.message)
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle error response (e.g., show error message)
                            alert('Error verifying OTP');
                        }
                    });
                } else {
                    emailVerifySuccessDiv.html('')
                    emailVerifyErrorDiv.html('Please enter Otp')
                }

            });
        });


        //get all blocks of selected locality
        $('#locality').on('change', function() {
            var locality = this.value;
            $("#block").html('');
            $.ajax({
                url: "{{ route('localityBlocks') }}",
                type: "POST",
                data: {
                    locality: locality,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#block').html('<option value="">Select Block</option>');
                    $.each(result, function(key, value) {
                        $("#block").append('<option value="' + value.block_no + '">' + value
                            .block_no + '</option>');
                    });
                }
            });
        });

        //get all plots of selected block
        $('#block').on('change', function() {
            var locality = $('#locality').val();
            var block = this.value;
            $("#plot").html('');
            $.ajax({
                url: "{{ route('blockPlots') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#plot').html('<option value="">Select Plot</option>');
                    $.each(result, function(key, value) {

                        $("#plot").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });


        //get known as of selected plot
        $('#plot').on('change', function() {
            var locality = $('#locality').val();
            var block = $('#block').val();
            var plot = this.value;
            $("#knownas").html('');
            $.ajax({
                url: "{{ route('plotKnownas') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#knownas').html('<option value="">Select Known as</option>');

                    $.each(result, function(key, value) {

                        $("#knownas").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });

        //for organization 
        //get all blocks of selected locality
        $('#locality_org').on('change', function() {
            var locality = this.value;
            $("#block_org").html('');
            $.ajax({
                url: "{{ route('localityBlocks') }}",
                type: "POST",
                data: {
                    locality: locality,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#block_org').html('<option value="">Select Block</option>');
                    $.each(result, function(key, value) {
                        $("#block_org").append('<option value="' + value.block_no + '">' + value
                            .block_no + '</option>');
                    });
                }
            });
        });

        //get all plots of selected block
        $('#block_org').on('change', function() {
            var locality = $('#locality_org').val();
            var block = this.value;
            $("#plot_org").html('');
            $.ajax({
                url: "{{ route('blockPlots') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#plot_org').html('<option value="">Select Plot</option>');
                    $.each(result, function(key, value) {

                        $("#plot_org").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });


        //get known as of selected plot
        $('#plot_org').on('change', function() {
            var locality = $('#locality_org').val();
            var block = $('#block_org').val();
            var plot = this.value;
            $("#knownas_org").html('');
            $.ajax({
                url: "{{ route('plotKnownas') }}",
                type: "POST",
                data: {
                    locality: locality,
                    block: block,
                    plot: plot,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('#knownas_org').html('<option value="">Select Known as</option>');
                    $.each(result, function(key, value) {

                        $("#knownas_org").append('<option value="' + value + '">' + value +
                            '</option>');
                    });
                }
            });
        });

        function getSubTypesByType(type, subtype) {
            var idPropertyType = $(`#${type}`).val();
            $(`#${subtype}`).html('');
            $.ajax({
                url: "{{ route('prpertySubTypes') }}",
                type: "POST",
                data: {
                    property_type_id: idPropertyType,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {

                    $(`#${subtype}`).html('<option value="">Select land use subtype</option>');
                    $.each(result, function(key, value) {
                        $(`#${subtype}`).append('<option value="' + value
                            .id + '">' + value.item_name + '</option>');
                    });
                }
            });
        }


        // For login with mobile and email By SOURAV CHAUHAN - 13 Aug 2024
        $(document).ready(function() {
            $('#emailInput').focus(function() {
                $(this).hide();
                $('#dividerLogin').hide();
                $('#mobileOtp').hide();
                $('#mobileInput').hide();
                $('#emailLoginForm').slideDown();
                $('#email').focus();
            });

            $('#mobileInput').focus(function() {
                $(this).hide();
                $('#dividerLogin').hide();
                $('#emailLoginForm').hide();
                $('#emailInput').hide();
                $('#mobileOtp').slideDown();
                $('#otpMobile').focus();
            });

            $('#getOtp').click(function() {
                var mobile = $('#otpMobile').val().trim();
                var errorDiv = $('#login_verify_mobile_otp_error');
                var button = $(this);
                if (mobile == '') {
                    errorDiv.html('Mobile number is required')
                } else if (!isValidMobile(mobile)) {
                    errorDiv.html('Invalid mobile number');
                } else {
                    button.prop('disabled', true);
                    button.html('Sending...');
                    $.ajax({
                        url: "{{ route('sendLoginOtp') }}",
                        type: "POST",
                        data: {
                            mobile: mobile,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                errorDiv.html('')
                                $('#mobileOtp').hide()
                                $('#mobile').val(mobile)
                                $('#LoginWithOTP').show()
                            } else {
                                errorDiv.html(result.message)
                                button.prop('disabled', false);
                                button.html('Get OTP');
                            }
                        }
                    });

                }

            });


            $('#verifyLoginOtp').click(function() {
                var mobile = $('#mobile').val().trim();
                var otp = $('#otp').val().trim();
                var errorDiv = $('#login_form_verify_mobile_otp_error');
                var button = $(this);
                if (mobile == '' && otp == '') {
                    errorDiv.html('Mobile number / OTP is required')
                } else if (!isValidMobile(mobile)) {
                    errorDiv.html('Invalid mobile number');
                } else {
                    button.prop('disabled', true);
                    button.html('Verifying...');
                    $.ajax({
                        url: "{{ route('verifyLoginOtp') }}",
                        type: "POST",
                        data: {
                            mobile: mobile,
                            otp: otp,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.success) {
                                location.reload(true)
                            } else {
                                errorDiv.html(result.message)
                                button.prop('disabled', false);
                                button.html('Login');
                            }
                        }
                    });

                }

            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const allowedCharsRegex = /^[0-9a-zA-Z\s\-\#\/\:\,\.\(\)]*$/;
            const maxLength = 250;

            const remarkInv = document.getElementById('remarkInv');
            const remarkOrg = document.getElementById('remarkOrg');
            const errorInv = document.getElementById('errorInv');
            const errorOrg = document.getElementById('errorOrg');

            function validateTextarea(textarea, errorDiv) {
                textarea.addEventListener('input', () => {
                    const value = textarea.value;
                    if (!allowedCharsRegex.test(value)) {
                        errorDiv.textContent = 'Invalid characters entered.';
                        textarea.value = value.replace(/[^0-9a-zA-Z\s\-\#\/\:\.\(\)]/g, '');
                    } else {
                        errorDiv.textContent = '';
                    }

                    if (value.length > maxLength) {
                        textarea.value = value.substring(0, maxLength);
                        errorDiv.textContent = `Maximum length of ${maxLength} characters exceeded.`;
                    }
                });
            }

            validateTextarea(remarkInv, errorInv);
            validateTextarea(remarkOrg, errorOrg);
        });
    </script>
</body>

</html>
