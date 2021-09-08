@extends('layouts.master-without-nav')

@section('title')
    ตั้งค่ารหัสผ่าน เริ่มต้นใชงาน
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
                                            <h5 class="text-primary"> Change Password</h5>
                                            <p>เริ่มต้นใช้งาน ตั้งค่ารหัสผ่าน</p>
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
                                    
                                    <form class="form-horizontal" id="change_password_form">
                                        <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}" />
                                    
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input id="current_password" type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                name="current_password" autocomplete="current_password"
                                                placeholder="รหัสผ่านปัจจุบัน" required>
                                            <div class="text-danger" id="current_passwordErr"
                                                data-ajax-feedback="current_password"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input id="new_password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" autocomplete="new_password" placeholder="รหัสผ่านใหม่"
                                                required>
                                            <div class="text-danger" id="passwordErr" data-ajax-feedback="new_password">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password_confirm" class="form-label">Confirm New Password</label>
                                                <input id="new_password_confirm" type="password"
                                                    class="form-control"
                                                    name="password_confirmation" placeholder="ยืนยันรหัสผ่านใหม่" required>
                                                <div class="text-danger" id="password_confirmErr" data-ajax-feedback="new_password_confirm"></div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button id="submitBtn" class="btn btn-primary w-md waves-effect waves-light"
                                                type="submit">Submit</button>
                                        </div>

                                    </form>
                                    <div class="mt-5 text-center">
                                        <p>Do you want to logout ? <a href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="fw-medium text-primary"> Logout now </a> </p>
                                    </div>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p>© <script>
                                    document.write(new Date().getFullYear())

                                </script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Zaaz</p>
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
    $('#change_password_form').on('submit',function(event){
        event.preventDefault();
        $('#submitBtn').append('<i id="loadingBtn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#submitBtn").attr("disabled", true);

        var current_password = $('#current_password').val();
        var password = $('#new_password').val();
        var password_confirm = $('#new_password_confirm').val();
        var csrf = $('#csrf_token').val();

        $('#current_passwordErr').text('');
        $('#passwordErr').text('');
        $('#password_confirmErr').text('');
        $.ajax({
            url: "{{ route('startChangePass') }}",
            type:"POST",
            data:{
                "current_password": current_password,
                "password": password,
                "password_confirmation": password_confirm,
                "_token": csrf,
            },
            success:function(response){
                $('#current_passwordErr').text('');
                $('#passwordErr').text('');
                $('#password_confirmErr').text('');
                $("#change_password_form")[0].reset();
                $("#loadingBtn").remove();
                $("#submitBtn").attr("disabled", false);

                if(response.isSuccess == false){
                    
                    $('#current_passwordErr').text(response.Message);
                    
                }else if(response.isSuccess == true){

                        Swal.fire({
                            icon: 'success',
                            title: "สำเร็จ!",
                            text: response.Message,
                            
                        }).then(function() {
                            window.location = "{{ route('root') }}";
                        });
                            
                }
                
            },
            error: function(response) {
                $("#loadingBtn").remove();
                $("#submitBtn").attr("disabled", false);
                $('#current_passwordErr').text(response.responseJSON.errors.current_password);
                $('#passwordErr').text(response.responseJSON.errors.password);
                $('#password_confirmErr').text(response.responseJSON.errors.password_confirmation);
            }
        });
    });
    </script>
    
@endsection