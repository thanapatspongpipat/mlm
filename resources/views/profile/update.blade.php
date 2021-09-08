@extends('layouts.master')

@section('title') แก้ไขข้อมูลส่วนตัว @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') โปรไฟล์ @endslot
        @slot('title') แก้ไขข้อมูลส่วนตัว @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <form id="form_update_profile">
                @method('PUT')
                <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}" />
            <div class="card">
                <div class="card-body">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">ชื่อ - นามสกุล</label>
                                        <input class="form-control" type="text" name="fullname" value="{{$userData->firstname}} {{$userData->lastname}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">รหัสสมาชิก</label>
                                        <input class="form-control" type="text"  value="{{ auth()->user()->id ?? '-' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">คำนำหน้าชื่อ</label>
                                    <select class="form-control select2-selection select2-selection--single" aria-hidden="true" id="prefix_name" name="prefix_name">
                                    @if($userData->prefix_name !=null || $userData->prefix_name !="")
                                    <option value="{{$userData->prefix_name}}" selected>{{$userData->prefix_name}}</option>
                                    @else
                                    <option value="" selected disabled>เลือก คำนำหน้าชื่อ</option>
                                    @endif
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เพศ</label>
                                    <select class="form-control select2 select2-selection--single" id="sex" name="sex" value="{{$userData->sex}}">
                                    @if($userData->sex !=null || $userData->sex !="")
                                    <option value="{{$userData->sex}}" selected>{{$userData->sex}}</option>
                                    @else
                                    <option value="" disabled selected>เลือก เพศ</option>
                                    @endif
                                    <option value="หญิง">หญิง</option>
                                    <option value="ชาย">ชาย</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">สัญชาติ</label>
                                        <input class="form-control" type="text" name="nationality" id="nationality" value="{{$userData->nationality}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Line ID</label>
                                        <input class="form-control" type="text" name="line" id="line" value="{{$userData->line}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Facebook URL</label>
                                        <input class="form-control" type="text" id="fb"  name="fb" value="{{$userData->fb}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Instagram URL</label>
                                        <input class="form-control" type="text" id="ig" name="ig" value="{{$userData->ig}}">
                                    </div>
                                </div>
                            </div>

                        </div>

                </div>
            </div>

        </div>
        <!-- end card one -->


        <!-- card two -->
        <div class="col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title mb-3">ที่อยู่ตามบัตร</h4>

                        <div class="row">

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">รหัสไปรษณีย์</label>
                                        <input class="form-control" type="text" value="{{$userData->zip_code}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" type="text" value="{{$userData->province}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control" type="text" value="{{$userData->district}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" type="text" value="{{$userData->sub_district}}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                        <label class="form-label">รายละเอียดที่อยู่เพิ่มฝาก</label>
                                        <textarea class="form-control" rows="3" disabled>{{$userData->address}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">มือถือ</label>
                                        <input class="form-control" type="text" value="{{$userData->phone_number}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">อีเมล์</label>
                                        <input class="form-control" type="text" value="{{$userData->email}}" disabled>
                                </div>
                            </div>

                        </div>

                </div>
            </div>

        </div>

         <!-- card three -->
         <div class="col-lg-6 mb-3">
            <div class="card h-100">

                <div class="card-body">
                    <h4 class="card-title mb-3">ที่อยู่ปัจจุบัน สำหรับจัดส่งสินค้า</h4>

                        <div class="row">

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">รหัสไปรษณีย์</label>
                                        <input class="form-control" id="send_zip_code" id="send_zip_code" name="send_zip_code" type="text" value="{{$userData->send_zip_code}}">
                                        <div class="text-danger" id="zipcodeErr" data-ajax-feedback="send_zip_code"></div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" id="send_province" id="send_province" name="send_province" type="text" value="{{$userData->send_province}}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control" id="send_district" id="send_district" name="send_district" type="text" value="{{$userData->send_district}}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" id="send_sub_district" id="send_sub_district" name="send_sub_district" type="text" value="{{$userData->send_sub_district}}">
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-3">
                                        <label class="form-label">รายละเอียดที่อยู่เพิ่มฝาก</label>
                                        <textarea class="form-control" rows="3" id="send_address" name="send_address">{{$userData->send_address}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">มือถือ</label>
                                        <input class="form-control" type="text" id="send_phone_number" name="send_phone_number" value="{{$userData->send_phone_number}}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">อีเมล์</label>
                                        <input class="form-control" type="email" id="send_email" name="send_email" parsley-type="email" value="{{$userData->send_email}}">
                                </div>
                            </div>

                        </div>

                </div>

            </div>

        </div>
        <!-- end card three -->



        <div class="col-12">
            <center>
                <button id="saveBtn" type="submit" class="btn btn-success btn-lg waves-effect waves-light">บันทึก </button>
            </center>

        </div>
    </form>


    </div>

@endsection

@section('script')
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/thailand/JQL.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/thailand/typeahead.bundle.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.js') }}"></script>
<script>
$.Thailand({
    database: '{{ asset('/assets/libs/thailand/db.json') }}',
    $district: $('#send_sub_district'), // input ของตำบล
    $amphoe: $('#send_district'), // input ของอำเภอ
    $province: $('#send_province'), // input ของจังหวัด
    $zipcode: $('#send_zip_code'), // input ของรหัสไปรษณีย์
    onDataFill: function(data){

    }

});

$('#form_update_profile').on('submit',function(event){
        event.preventDefault();

        $('#saveBtn').append('<i id="loadingBtn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#saveBtn").attr("disabled", true);

        var csrf = $('#csrf_token').val();
        var prefixName = $('#prefix_name').val();
        var sex = $('#sex').val();
        var nationality = $('#nationality').val();
        var line = $('#line').val();
        var fb = $('#fb').val();
        var ig = $('#ig').val();
        var line = $('#line').val();

        var sendAddress = $('#send_address').val();
        var sendProvince = $('#send_province').val();
        var sendDistrict = $('#send_district').val();
        var sendSubDistrict = $('#send_sub_district').val();
        var sendZipCode = $('#send_zip_code').val();
        var sendPhoneNumber = $('#send_phone_number').val();
        var sendEmail = $('#send_email').val();

        $('#current_passwordErr').text('');
        $('#passwordErr').text('');
        $('#password_confirmErr').text('');
        $.ajax({
            url: "{{ route('accountProfileUpdate') }}",
            type:"put",
            data:{
                "_token": csrf,
                "prefix_name": prefixName,
                "sex": sex,
                "nationality": nationality,
                "line": line,
                "fb": fb,
                "ig": ig,
                "send_address": sendAddress,
                "send_province": sendProvince,
                "send_district": sendDistrict,
                "send_sub_district": sendSubDistrict,
                "send_zip_code": sendZipCode,
                "send_phone_number": sendPhoneNumber,
                "send_email": sendEmail
            },

            success:function(response){

                $('#zipcodeErr').text('');
                $('#countryErr').text('');
                $("#loadingBtn").remove();
                $("#saveBtn").attr("disabled", false);
                if(response.isSuccess == false){

                    $('#zipcodeErr').text(response.errors.send_zip_code);
                    $('#countryErr').text(response.errors.country);

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
                $("#saveBtn").attr("disabled", false);
                $('#zipcodeErr').text(response.responseJSON.errors.send_zip_code);
            }
        });


    });
</script>
@endsection

