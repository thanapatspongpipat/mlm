@extends('layouts.master')

@section('title') @lang('translation.Form_Elements') @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') โปรไฟล์ @endslot
        @slot('title') ข้อมูลส่วนตัว @endslot
    @endcomponent
    <div class="row">
       
    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
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

