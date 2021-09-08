@extends('layouts.master-without-nav')

@section('title')
    Forgot Password
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('body')

    <body>
    @endsection

    @section('content')
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary"> Forgot Password</h5>
                                            <p>ลืมรหัสผ่าน</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ URL::asset('/assets/images/profile-img.png') }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div>
                                    <a>
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ URL::asset('/assets/images/logo.svg') }}" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="p-2">

                                    <form class="form-horizontal" id="forgot_password_form">

                                        <div class="alert alert-danger" id="error-msg"></div>
                                        <div id="otp-section">
                                        <div class="mb-3">
                                            <!-- <label for="phone_number" class="form-label">Phone Number</label> -->
                                            <label for="phone_number" class="form-label">รหัสผู้ใช้งาน</label>
                                            <div class="input-group">
                                            <input id="phone_number" type="text" class="form-control" name="phone_number" placeholder="หมายเลขโทรศัพท์มือถือ" required>
                                            <button class="btn btn-primary" type="button" id="send_otp_btn">รับรหัส OTP </button>
                                            </div>
                                            <div class="text-danger" id="phone_numberErr"
                                                data-ajax-feedback="phone_number"></div>
                                            <div class="text-success" id="phone_numberInfo"
                                                data-ajax-feedback="phone_number"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="otp_input" class="form-label">เลข OTP</label>

                                                <input type="text" class="form-control" id="otp_input" placeholder="เลข OTP 4 หลัก" required>

                                                <div class="text-danger" id="otp_inputErr" data-ajax-feedback="otp_input" ></div>
                                        </div>
                                        </div>

                                        <div id="change_pass_input">
                                            <div class="mb-3">
                                                <label for="new_password" class="form-label">รหัสผ่านใหม่</label>

                                                    <input type="password" class="form-control" id="new_password" placeholder="รหัสผ่านใหม่" required>

                                                    <div class="text-danger" id="new_passwordErr" data-ajax-feedback="new_password"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="new_password_confirm" class="form-label">ยืนยันรหัสผ่านใหม่</label>

                                                    <input type="password" class="form-control" id="new_password_confirm" placeholder="ยืนยันรหัสผ่านใหม่" required>

                                                    <div class="text-danger" id="new_password_confirmErr" data-ajax-feedback="new_password_confirm"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control" id="otp_token" name="otp_token">

                                        <div class="text-center">
                                                <button id="changePassBtn" class="btn btn-success w-md waves-effect waves-light"
                                                    type="button">ยืนยัน </button>
                                        </div>

                                        <div class="text-center">
                                            <button id="submitBtn" class="btn btn-success w-md waves-effect waves-light"
                                                type="submit">เปลี่ยนรหัสผ่าน</button>
                                        </div>

                                    </form>

                                    <div class="mt-5 text-center">
                                        <p>Do you want to sign ? <a href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="fw-medium text-primary"> Login now </a> </p>
                                    </div>
                                    <form id="logout-form" action="{{ route('login') }}" method="Get" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p>© <script>
                                    document.write(new Date().getFullYear())

                                </script> HappinessCorp <i class="mdi mdi-heart text-danger"></i></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endsection

@section('script-bottom')
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$( document ).ready(function() {
    $("#error-msg").hide();
    $("#change_pass_input").hide();
    $("#submitBtn").hide();

    $('#changePassBtn').on('click',function(event){
        event.preventDefault();

        var phoneNumberInput = $('#phone_number').val();
        var otp_input = $('#otp_input').val();
        var otp_token = $('#otp_token').val();

        var phoneNumber = phoneNumberInput.trim()

        if(phoneNumber.length < 1 ){
            $('#phone_numberErr').text("Phone Number is required");
            return
        }

        $('#changePassBtn').append('<i id="loadingchangePassBtn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#changePassBtn").attr("disabled", true);

        $('#phone_numberErr').text('');
        $('#passwordErr').text('');
        $("#error-msg").hide();
        $.ajax({
            url: "{{ route('verifyOTP') }}",
            type:"POST",
            data:{
                "phone_number": phoneNumber,
                "otp_token": otp_token,
                "otp_pin": otp_input,
                "_token": '{{ csrf_token() }}',
            },
            success:function(response){
                $("#error-msg").hide();
                $('#error-msg').text('');
                $('#otp_inputErr').text('');
                $("#loadingchangePassBtn").remove();
                $("#changePassBtn").attr("disabled", false);
                $("#changePassBtn").hide();

                if(response.isSuccess == false){

                    $('#otp_inputErr').text(response.message);

                }else if(response.isSuccess == true){

                    $("#change_pass_input").show();
                    $("#submitBtn").show();
                    $("#otp-section").hide();
                    $("#send_otp_btn").attr("disabled", true);
                    $("#phone_number").attr("disabled", true);
                    $("#otp_input").attr("disabled", true);
                    var changePassBtn = document.getElementById('changePassBtn');
                    changePassBtn.parentNode.removeChild(changePassBtn);

                }

            },
            error: function(response) {

                $("#loadingchangePassBtn").remove();
                $("#changePassBtn").attr("disabled", false);
            }
        });
    });

    //===================== send otp
    $('#send_otp_btn').on('click',function(event){
        event.preventDefault();

        var phoneNumberInput = $('#phone_number').val();
        var phoneNumber = phoneNumberInput.trim()

        if(phoneNumber.length < 1 ){
            $('#phone_numberErr').text("Phone Number is required");
            return null
        }

        $('#send_otp_btn').append('<i id="loading_otp_btn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#send_otp_btn").attr("disabled", true);

        $('#phone_numberErr').text('');
        $('#phone_numberInfo').hide();
        $('#error-msg').text('');
        $("#error-msg").hide();

        $.ajax({
            url: "{{ route('generateOTP') }}",
            type:"POST",
            data:{
                "phoneNumber": phoneNumber,
                "_token": '{{ csrf_token() }}',
            },
            success:function(response){
                $('#phone_numberErr').text('');
                $("#loading_otp_btn").remove();
                $("#send_otp_btn").attr("disabled", false);

                if(response.isSuccess == false){
                    $('#phone_numberErr').text(response.phoneNumberErr);

                }else if(response.isSuccess == true){

                    $("#otp_token").val(response.otp_token);
                    $('#phone_numberInfo').text('✓  ส่งรหัส OTP 4 หลัก เรียบร้อยแล้ว');
                    var secondsBeforeExpire = 60;

                    var timer = setInterval(function () {

                        if (secondsBeforeExpire <= 0) {
                            clearInterval(timer);
                            $("#send_otp_btn").attr("disabled", false);
                            $("#send_otp_btn").text('Resend OTP');
                        } else {
                            secondsBeforeExpire--;
                            $("#send_otp_btn").prop('disabled', true);
                            $("#send_otp_btn").text(secondsBeforeExpire);
                        }
                    }, 1000);

                }

            },
            error: function(response) {
                $("#loading_otp_btn").remove();
                $("#send_otp_btn").attr("disabled", false);
                $("#error-msg").show();
                $('#error-msg').text(response.responseJSON.errors);
            }
        });
    });
});

//============= change pass

$('#forgot_password_form').on('submit',function(event){
        event.preventDefault();

        var phoneNumberInput = $('#phone_number').val();
        var password = $('#new_password').val();
        var passwordConfirm = $('#new_password_confirm').val();

        var phoneNumber = phoneNumberInput.trim()

        if(phoneNumber.length < 1 ){
            $('#phone_numberErr').text("Phone Number is required");
            return null
        }

        $('#submitBtn').append('<i id="loadingBtn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#submitBtn").attr("disabled", true);

        $('#phone_numberErr').text('');
        $('#new_passwordErr').text('');
        $("#error-msg").hide();

        $.ajax({
            url: "{{ route('resetChangePass') }}",
            type:"POST",
            data:{
                "phone_number": phoneNumber,
                "password": password,
                "password_confirmation": passwordConfirm,
                "_token": '{{ csrf_token() }}',
            },
            success:function(response){
                $("#error-msg").hide();
                $('#error-msg').text('');
                $('#new_passwordErr').text('');
                $("#loadingBtn").remove();
                $("#submitBtn").attr("disabled", false);

                if(response.isSuccess == false){

                    $('#error-msg').text(response.message);

                }else if(response.isSuccess == true){

                        Swal.fire({
                            icon: 'success',
                            title: "สำเร็จ!",
                            text: response.message,

                        }).then(function() {
                            window.location = "{{ route('login') }}";
                        });
                }

            },
            error: function(response) {

                $("#error-msg").show();
                $('#error-msg').text(response.responseJSON.errors.password);
                $("#loadingBtn").remove();
                $("#submitBtn").attr("disabled", false);
            }
        });
    });
</script>

@endsection
