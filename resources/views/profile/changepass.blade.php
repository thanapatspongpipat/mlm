@extends('layouts.master')

@section('title') เปลี่ยนรหัสผ่าน @endsection


@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') โปรไฟล์ @endslot
        @slot('title') เปลี่ยนรหัสผ่าน @endslot
    @endcomponent
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    
                
                    <div class="card-body">

                        <form method="POST" id="change_password">
                            <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}" />
                            @method('PUT')
    
                            <div class="form-group row mb-3">
                                <label for="current_password"
                                    class="col-md-4 col-form-label text-md-right">รหัสผ่านปัจจุบัน</label>
    
                                <div class="col-md-6">
                                    <input id="current_password" type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        name="current_password" autocomplete="current_password" placeholder="" required>
                                        <div class="text-danger" id="current_passwordErr" data-ajax-feedback="current_password"></div>
                                </div>
                            </div>
    
                            <div class="form-group row mb-3">
                                <label for="new_password"
                                    class="col-md-4 col-form-label text-md-right">รหัสผ่านใหม่</label>
                                <div class="col-md-6">
                                    <input id="new_password" type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password" autocomplete="new_password" placeholder="" required>
                                        <div class="text-danger" id="passwordErr" data-ajax-feedback="new_password"></div>
                                </div>
                            </div>
    
                            <div class="form-group row mb-3">
                                <label for="new_password_confirm"
                                    class="col-md-4 col-form-label text-md-right">ยืนยันรหัสผ่านใหม่</label>
    
                                <div class="col-md-6">
                                    <input id="new_password_confirm" type="password"
                                        class="form-control"
                                        name="password_confirmation" placeholder="" required>
                                    <div class="text-danger" id="password_confirmErr" data-ajax-feedback="new_password_confirm"></div>
                                </div>
                            </div>
                           
    
                            <div class="form-group row mt-5 mb-0">
                                    <div class="col-md-6 offset-md-4">
                                    <button id="submitBtn" type="submit" class="btn btn-success">
                                        บันทึก
                                    </button>
                                </div>
                            </div>
                        </form>
    
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        
    $('#change_password').on('submit',function(event){
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
            url: "{{ route('updatePass') }}",
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
                $("#change_password")[0].reset();
                $("#loadingBtn").remove();
                $("#submitBtn").attr("disabled", false);

                if(response.isSuccess == false){
                        $('#current_passwordErr').text(response.Message);
                }else if(response.isSuccess == true){

                        Swal.fire(
                            'สำเร็จ!',
                             response.Message,
                            'success'
                        )
                            
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