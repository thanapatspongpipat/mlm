@extends('layouts.master')

@section('title') @lang('translation.Form_Elements') @endsection

@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') ลงทะเบียนและอัพเกรด @endslot
@slot('title') อัพเกรดแพคเกจ @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">ข้อมูลสมาชิก</h4>
<!--
                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Username</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div> -->

                <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสสมาชิก</label>
                                    <div class="input-group bg-light rounded">
                                        <input type="text" class="form-control " placeholder="ใส่รหัสผู้ใช้" aria-label="Recipient's username" aria-describedby="button-addon2" id="input-user-name">
                                        <button class="btn btn-primary" type="button" id="button-addon2">
                                            <i class="bx bx-search-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">แพคเกจปัจจุบัน</label>
                                        <input class="form-control"  name="package_now" type="text" disabled>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">ชื่อ - นามสกุล</label>
                                        <input class="form-control"  name="fullname" type="text" disabled>

                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6" hidden>
                                <div class="mb-3">
                                        <label class="form-label">รหัสไปรษณีย์</label>
                                        <input class="form-control" id="send_zip_code" id="send_zip_code" name="send_zip_code" type="text" disabled>
                                        <div class="text-danger" id="zipcodeErr" data-ajax-feedback="send_zip_code"></div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6" hidden>
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" id="send_province" id="send_province" name="send_province" type="text" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6" hidden>
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control" id="send_district" id="send_district" name="send_district" type="text" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6" hidden>
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" id="send_sub_district" id="send_sub_district" name="send_sub_district" type="text" disabled>
                                </div>
                            </div>


                            <div class="col-lg-12" hidden>
                                <div class="mb-3">
                                        <label class="form-label">รายละเอียดที่อยู่เพิ่มเติม</label>
                                        <textarea class="form-control" rows="3" id="send_address" name="send_address" disabled></textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6" hidden>
                                <div class="mb-3">
                                        <label class="form-label">มือถือ</label>
                                        <input class="form-control" type="text" id="send_phone_number" name="send_phone_number" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6" hidden>
                                <div class="mb-3">
                                        <label class="form-label">อีเมล์</label>
                                        <input class="form-control" type="email" id="send_email" name="send_email" parsley-type="email" disabled>
                                </div>
                            </div>




                            <div class="col-md-6 col-sm-6" hidden>
                                <label for="example-text-input" class="col-md-2 col-form-label">การชำระเงิน</label>
                                <div class="col-md-10">
                                    <div class="d-flex justify-content-start">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="type_sent_item" id="formRadios1" value="send" checked>
                                            <label class="form-check-label" for="formRadios1">
                                                จัดส่งสินค้า&nbsp;&nbsp;
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type_sent_item" value="self_pick_up" id="formRadios2">
                                            <label class="form-check-label" for="formRadios2">
                                                <span class="text-danger">รับสินค้าเองที่ Office</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>

                <!-- <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ชื่อ - สกุล ผู้รับ</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จัดส่งที่อยู่</label>
                    <div class="col-md-10">
                        <textarea required class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รหัสไปรษณีย์</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">มือถือ</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">การชำระเงิน</label>
                    <div class="col-md-10">



                        <div class="d-flex justify-content-start">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="formRadios" id="formRadios1" checked>
                                <label class="form-check-label" for="formRadios1">
                                    จัดส่งสินค้า
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formRadios" id="formRadios2">
                                <label class="form-check-label" for="formRadios2">
                                    <span class="text-danger">รับสินค้าเองที่ Office</span>
                                </label>
                            </div>
                        </div>




                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">เพิ่มฝาก</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div> -->


            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <!-- <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">รายการสินค้า</h4>



            </div>
        </div> -->
        <!-- end card -->

        <!-- <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Order All</h4>

                <div class="d-flex justify-content-center">
                    <div class="row">
                        <div class="mb-3 d-flex align-items-center">
                            <div class="col-3 d-flex justify-content-end p-2">
                                <span >รวมคะแนน</span>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" disabled>
                                    <div class="input-group-text col-2">คะแนน</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <div class="col-3 d-flex justify-content-end p-2">
                                <span >รวมราคา</span>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" disabled>
                                    <div class="input-group-text col-2">บาท</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- end card -->


        <!-- end card -->

        <div class="card">
            <div class="card-body">

                <!-- <div class="btn-group me-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('0')">ทั้งหมด</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('1')">อาหารเสริม</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('2')">เครื่องสำอาง</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('3')">สินค้ากลุ่ม Trading</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('4')">สินค้ากลุ่ม Satpoint</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('5')">Fund VICC</button>

                </div> -->
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>ภาพ</th>
                            <!-- <th>รหัสสินค้า</th> -->
                            <th>ชื่อสินค้า</th>
                            <!-- <th>คะแนน</th> -->
                            <th>ราคา</th>
                            <!-- <th>จำนวน</th> -->
                            <th></th>
                        </tr>
                    </thead>


                    <tbody>

                    </tbody>
                </table>


            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->


        <div class="card">
            <div class="card-body">

                <div class="col-sm-auto d-flex justify-content-center">
                    <button type="button" class="btn btn-primary w-md" onclick="btnSubmitOrder()">ยืนยันการอัพเกรดแพ็คเกจ</button>
                </div>
            </div>
        </div>




    </div>
    <!-- end col -->
</div>
<!-- end row -->


@endsection

@section('script')
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>

<script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var filter = 0;
    var table = $('#datatable-buttons').DataTable({
        "paging": false,
        "searching": false,
        "ordering": false,
        ajax: {
            url: '{{ route("member.product-list") }}',
            type: 'POST',
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "data": fnDataTableSet
        },
        "columns": [{
                "data": "image",
                className: 'text-center align-middle'
            },
            // {
            //     "data": "code",
            //     className: 'text-center align-middle'
            // },
            {
                "data": "name",
                className: 'text-center align-middle'
            },
            // {
            //     "data": "point",
            //     className: 'text-center align-middle'
            // },
            {
                "data": "price",
                className: 'text-center align-middle'
            },
            // {
            //     "data": "amount",
            //     className: 'text-center align-middle'
            // },
            {
                "data": "tool",
                className: 'text-center align-middle'
            },
        ]
    })

    function fnSetData(id) {
        filter = id
        table.ajax.reload();
    }

    function fnDataTableSet(d) {
        d.product = filter
        return d
    }
    $(document).on('click', '.btn-action-slect-package', function(){
        package_id = $(this).data('id')
        var parent = $(this).parents('tr')
        $('#datatable-buttons tr').removeClass(set_class)
        parent.addClass(set_class)
    })
    var user_id = null
    var package_id = null
    var set_class = "bg-info text-white";
    $('#button-addon2').on('click', function(){

        $.post('{{route("api.check_user")}}', {
            _token: "{{ csrf_token()}}",
            username: $('#input-user-name').val()
        }, function(data){
            if(data.data){
                $('input[name="fullname"]').val(data.data.firstname+' '+data.data.lastname)
                $('input[name="send_zip_code"]').val(data.data.zip_code)
                $('input[name="send_province"]').val(data.data.send_address)
                $('input[name="send_district"]').val(data.data.send_district)
                $('input[name="send_sub_district"]').val(data.data.send_sub_district)
                $('textarea[name="send_address"]').text(data.data.send_address)
                $('input[name="send_phone_number"]').val(data.data.send_phone_number)
                $('input[name="email"]').val(data.data.email)
                $('input[name="package_now"]').val(data.data.product.name)
                user_id = data.data.id
                $('button.btn-action-slect-package').prop('disabled', false);
                // manage button
                if(data.data.product.level == 'S'){
                    $('button.btn-package-S').prop('disabled', true);
                }
                if(data.data.product.level == 'M'){
                    $('button.btn-package-S').prop('disabled', true);
                    $('button.btn-package-M').prop('disabled', true);
                }
                if(data.data.product.level == 'D'){
                    $('button.btn-package-S').prop('disabled', true);
                    $('button.btn-package-M').prop('disabled', true);
                    $('button.btn-package-D').prop('disabled', true);
                }
                if(data.data.product.level == 'SD'){
                    $('button.btn-package-S').prop('disabled', true);
                    $('button.btn-package-M').prop('disabled', true);
                    $('button.btn-package-D').prop('disabled', true);
                    $('button.btn-package-SD').prop('disabled', true);
                }
            }
            Swal.fire(
                '',
                data.message,
                data.status
            )
        }, 'json')
    })
    function btnSubmitOrder(){
        var type_sent = $("input[name='type_sent_item']:checked").val();
        if(user_id !== null && package_id !== null){

            $.post('{{route("api.update.save")}}', {
                _token: "{{ csrf_token()}}",
                user_id: user_id,
                sent_type: type_sent,
                package_id: package_id
            }, function(data){
                Swal.fire(
                    '',
                    data.message,
                    data.status
                )
                if(data.status == 'success'){
                    $('input[name="fullname"]').val('')
                    $('input[name="send_zip_code"]').val('')
                    $('input[name="send_province"]').val('')
                    $('input[name="send_district"]').val('')
                    $('input[name="send_sub_district"]').val('')
                    $('textarea[name="send_address"]').text()
                    $('input[name="send_phone_number"]').val('')
                    $('input[name="email"]').val('')
                    $('input[name="package_now"]').val('')
                    // $('input[name="type_sent_item"]').val('send')
                    $('#datatable-buttons tr').removeClass(set_class)
                    $('#input-user-name').val('')
                    $('button.btn-action-slect-package').prop('disabled', false); // reset pageage
                    $('input:radio[name=type_sent_item]').filter('[value=send]').prop('checked', true);
                    user_id = null
                    package_id = null
                }
            }, 'json')
        }else{
            Swal.fire(
                '',
                "กรุณากรอกข้อมูลให้ครบถ้วน",
                'error'
            )
        }
    }
</script>
@endsection
