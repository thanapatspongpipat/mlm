@extends('layouts.master')

@section('title') @lang('translation.Form_Elements') @endsection

@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Member Upgrade @endslot
@slot('title') เพิ่ม Member Upgrade @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">สมาชิก / การชำระเงิน</h4>

                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Username</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div>
                <div class="mb-3 row">
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
                    <label for="example-text-input" class="col-md-2 col-form-label">เพิ่มเติม</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="example-text-input">
                    </div>
                </div>


            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">รายการสินค้า</h4>



            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="card">
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
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">

                <div class="col-sm-auto d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary w-md">  ยืนยันการชำระค่าสินค้า</button>
                </div>


            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">

                <div class="btn-group me-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('0')">ทั้งหมด</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('1')">อาหารเสริม</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('2')">เครื่องสำอาง</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('3')">สินค้ากลุ่ม Trading</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('4')">สินค้ากลุ่ม Satpoint</button>
                    <button type="button" class="btn btn-secondary" onclick="fnSetData('5')">Fund VICC</button>

                </div>
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>ภาพ</th>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>คะแนน</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
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




    </div>
    <!-- end col -->
</div>
<!-- end row -->


@endsection

@section('script')
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>

<script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>

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
            {
                "data": "code",
                className: 'text-center align-middle'
            },
            {
                "data": "name",
                className: 'text-center align-middle'
            },
            {
                "data": "point",
                className: 'text-center align-middle'
            },
            {
                "data": "price",
                className: 'text-center align-middle'
            },
            {
                "data": "amount",
                className: 'text-center align-middle'
            },
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
</script>
@endsection
