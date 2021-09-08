@extends('layouts.master')

@section('title') @lang('translation.Form_Elements') @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') สมาชิก @endslot
        @slot('title') เพิ่ม สมาชิก @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">ข้อมูลทั่วไป</h4>

                    <form class="needs-validation" method="POST" action="{{ route('createUser') }}" novalidate>
                        <input type="hidden" value="{{$product_id}}" name="product_id">
                        @csrf
                        <div class="row">
                            <!-- <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="formrow-username-input" class="form-label">ชื่อในระบบ</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="formrow-username-input" name="username" value="{{ old('username', $data->username ?? null) }}" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-firstname" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="formrow-firstname" value="{{ old('firstname', $data->firstname ?? null) }}"  name="firstname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-lastname" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="formrow-lastname" value="{{ old('lastname', $data->lastname ?? null) }}"  name="lastname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-on_cardt" class="form-label">เลขที่บัตร</label>
                                    <input type="text" class="form-control @error('on_card') is-invalid @enderror" id="formrow-on_card" value="{{ old('on_card', $data->on_card ?? null) }}"  name="on_card">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-dob" class="form-label">วันเกิด</label>
                                    <div class="col-md-12">
                                        <input class="form-control @error('username') is-invalid @enderror" type="date" value="{{ old('dob', $data->dob ?? null) }}"  id="example-dob" name="dob">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-email" class="form-label">อีเมล์</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="formrow-email" value="{{ old('email', $data->email ?? null) }}"  name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-phone_number" class="form-label">มือถือ</label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="formrow-phone_number" value="{{ old('phone_number', $data->phone_number ?? null) }}"  name="phone_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-line" class="form-label">ไลน์ไอดี</label>
                                    <input type="text" class="form-control @error('line') is-invalid @enderror" id="formrow-line" value="{{ old('line', $data->line ?? null) }}"  name="line">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formrow-fb" class="form-label">เฟสบุค</label>
                                    <input type="text" class="form-control @error('fb') is-invalid @enderror" id="formrow-fb" value="{{ old('fb', $data->fb ?? null) }}"  name="fb">
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mb-4 mt-4">ผังองค์กร</h4>
                        <div class="row">
                            <div class="col-lg-2">
                                <!-- <div class="mb-3">
                                    <label for="formrow-user_invite_id" class="form-label">ผู้แนะนำ</label>
                                    <input type="text" class="form-control @error('user_invite_id') is-invalid @enderror" id="formrow-user_invite_id" value="{{ old('user_invite_id', $data->user_invite_id ?? null) }}"  name="user_invite_id">
                                </div> -->
                                <label for="formrow-user_invite_id" class="form-label">รหัส ผู้แนะนำ</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('user_invite_id') is-invalid @enderror" id="formrow-user_invite_id" value="{{ old('user_invite_id', $data->user_invite_id ?? null) }}"  name="user_invite_id" required>
                                    <button type="button" class="btn btn-primary" onclick="fnInviteSearch()">ค้นหา</button>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-user_invite" class="form-label">ชื่อผู้แนะนำ</label>
                                    <input type="text" class="form-control" id="formrow-user_invite" disabled>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="formrow-user_upline_id" class="form-label">รหัส อัพไลน์</label>
                                    <input type="text" class="form-control @error('user_upline_id') is-invalid @enderror" id="formrow-user_upline_id" value="{{ old('user_upline_id', $data->user_upline_id ?? $user_upline->id ?? '') }}"  name="user_upline_id" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-nameUpline" class="form-label">ชื่ออัพไลน์</label>
                                    <input type="text" class="form-control" id="formrow-nameUpline" value="{{$user_upline->firstname.' '.$user_upline->lastname}}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <h5 class="font-size-14 mb-4">ตำแหน่วงว่าง</h5>
                                    <div class="d-flex">
                                        <input type="hidden" value="{{$position}}">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input @error('position_space') is-invalid @enderror" type="radio" id="formRadios1" name="position_space" value="left" {{ $position == 'left' ? 'checked' : '' }} disabled>
                                            <label class="form-check-label" for="formRadios1">
                                                ซ้าย
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input @error('position_space') is-invalid @enderror" type="radio" name="position_space" value="right" id="formRadios2" {{ $position == 'right' ? 'checked' : '' }} disabled>
                                            <label class="form-check-label" for="formRadios2">
                                                ขวา
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mb-4 mt-4">ข้อมูลธนาคาร</h4>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-inputCity" class="form-label">ธนาคาร</label>
                                    <select class="form-select @error('bank_id') is-invalid @enderror" id="autoSizingSelect" value="{{ old('bank_id', $data->bank_id ?? null) }}"  name="bank_id">
                                        <option value="" selected disabled hidden>Choose...</option>
                                        @foreach ($banks as $bank)
                                        <option value="{{ $bank->code }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-bank_no" class="form-label">เลขบัญชี</label>
                                    <input type="text" class="form-control @error('bank_no') is-invalid @enderror" id="formrow-bank_no" value="{{ old('bank_no', $data->bank_no ?? null) }}"  name="bank_no">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="formrow-bank_own_name" class="form-label">ชื่อบัญชี</label>
                                    <input type="text" class="form-control @error('bank_own_name') is-invalid @enderror" id="formrow-bank_own_name" value="{{ old('bank_own_name', $data->bank_own_name ?? null) }}"  name="bank_own_name">
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mb-4 mt-4">ที่อยู่ตามบัตร</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสไปรษณีย์</label>
                                    <input class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" type="text" value="{{ old('zip_code', $data->zip_code ?? null) }}">
                                    <div class="text-danger" id="zipcodeErr" data-ajax-feedback="zip_code"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" id="province" name="province" type="text" value="{{ old('province', $data->province ?? null) }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control"  id="district" name="district" type="text" value="{{ old('district', $data->district ?? null) }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" id="sub_district" name="sub_district" type="text" value="{{ old('sub_district', $data->sub_district ?? null) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="formrow-address" class="form-label">รายละเอียดเพิ่มเติม</label>
                                    <div>
                                        <textarea class="form-control @error('address') is-invalid @enderror" rows="3" name="address">{{ old('address', $data->address ?? null) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h4 class="card-title mb-4 mt-4">ที่อยู่จัดส่งสินค้า</h4>
                        <div class="row">

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสไปรษณีย์</label>
                                    <input class="form-control @error('send_zip_code') is-invalid @enderror" id="send_zip_code" name="send_zip_code" type="text" value="{{ old('send_zip_code', $data->send_zip_code ?? null) }}">
                                    <div class="text-danger" id="zipcodeErr2" data-ajax-feedback="send_zip_code"></div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" id="send_province" id="send_province" name="send_province" type="text" value="{{ old('send_province', $data->send_province ?? null) }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control" id="send_district" id="send_district" name="send_district" type="text" value="{{ old('send_district', $data->send_district ?? null) }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" id="send_sub_district" name="send_sub_district" type="text" value="{{ old('send_sub_district', $data->send_sub_district ?? null) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="formrow-send_address" class="form-label">รายละเอียดเพิ่มฝาก</label>
                                    <div>
                                        <textarea class="form-control @error('send_address') is-invalid @enderror" rows="3" name="send_address">{{ old('send_address', $data->send_address ?? null) }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <h4 class="card-title mb-4 mt-4">กำหนดรหัสผ่าน</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="formrow-password" class="form-label">รหัสผ่าน <span class="text-danger">(อย่างน้อย 6 ตัวอักษร)</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="formrow-password" name="password" placeholder="รหัสผ่าน" required  data-parsley-min="6">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="formrow-password-2" class="form-label">ยืนยันรหัสผ่าน</label>
                                    <input type="password" class="form-control" id="formrow-password-2" required data-parsley-equalto="#formrow-password" name="password_confirmation"  placeholder="ยืนยันรหัสผ่าน">
                                </div>
                            </div>

                        </div>


                        <div>
                            <button type="submit" class="btn btn-primary w-md">ยืนยันการสมัคร</button>
                        </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->


@endsection

@section('script')
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

        $.Thailand({
            database: '{{ asset('/assets/libs/thailand/db.json') }}',
            $district: $('#sub_district'), // input ของตำบล
            $amphoe: $('#district'), // input ของอำเภอ
            $province: $('#province'), // input ของจังหวัด
            $zipcode: $('#zip_code'), // input ของรหัสไปรษณีย์
            onDataFill: function(data){

            }

        });

    </script>

    <script>

        function fnInviteSearch(){
            var invite = $('input[name=user_invite_id]').val()
            $.post("{{ route('create.user.find.invite') }}", {invite_id: invite,_token: "{{ csrf_token()}}"}, (data)=>{
                console.log(data)
                $('#formrow-user_invite').val(data)
            })
        }
    </script>
@endsection

