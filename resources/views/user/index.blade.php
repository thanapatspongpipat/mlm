@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection


@section('css')
<link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') ข้อมูลทีม @endslot
@slot('title') ตารางรายชื่อทีม @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">



            <div class="col-xl-12   ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">ค้นหา</h4>

                        <div class="d-flex justify-content-center">
                            <form class="col-sm-7">
                                <div class="row mb-4" hidden>
                                    <label class="col-sm-3 col-form-label">วันที่ลงทะเบียน</label>
                                    <div class="col-sm-6">
                                        <div class="input-daterange input-group" id="datepicker6" data-date-format="dd-mm-yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                            <input type="text" class="form-control" name="start" placeholder="Start Date" />
                                            <input type="text" class="form-control" name="end" placeholder="End Date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-email-input" class="col-sm-3 col-form-label">รหัสผู้ใช้งาน</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="horizontal-email-input" name="username" value="{{auth()->user()->id}}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">ชื่อในระบบ</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="horizontal-password-input" name="display_name">
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div>
                                            <button type="button" onclick="fnSearch()" class="btn btn-primary w-md">ค้นหา</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>






            <div class="card-body">

                <!-- <h4 class="card-title">Default Datatable</h4> -->
                <!-- <p class="card-title-desc">DataTables has most features enabled by
                        default, so all you need to do to use it with your own tables is to call
                        the construction function: <code>$().DataTable();</code>.
                    </p> -->

                <table id="member-datatable" class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                        <tr>
                            <th>วันที่ลงทะเบียน</th>
                            <th>รหัสผู้ใช้งาน</th>
                            <th>ชื่อในระบบ</th>
                            <th>ผู้แนะนำ</th>
                            <th>อัพไลน์</th>
                            <th>ตำแหน่งว่าง</th>
                            <th>แนะนำ​แล้ว</th>
                            <th>มือถือ</th>
                            <th>อีเมล์</th>
                            <th>ไลน์ไอดี</th>
                        </tr>
                    </thead>


                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@if (session('modal'))
<div class="modal fade bs-example-modal-center" id="modal-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! session('modal') !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif

@endsection

@section('script')
<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<!-- Datatable init js -->
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>


<script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datepicker/datepicker.min.js') }}"></script>

<!-- form advanced init -->
<script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>


@if (session('modal'))
<script>
    $(document).ready(function() {
        $('#modal-status').modal('show')
    })
</script>
@endif
<script>
    function fnDdata(d) {
        d.start_date = $('input[name=start]').val();
        d.end_date = $('input[name=end]').val();
        d.username = $('input[name=username]').val();
        d.display_name = $('input[name=display_name]').val();
        return d
    }
    var table = $('#member-datatable').DataTable({
        serverSide: true,
        ajax: {
            url: '{{ route("memberUserList") }}',
            type: 'POST',
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: fnDdata
        },
        "columns": [{
                "data": "date",
                className: 'text-center align-middle'
            },
            {
                "data": "id",
                className: 'text-center align-middle'
            },
            {
                "data": "name"
            },
            {
                "data": "invite",
                className: 'text-center align-middle'
            },
            {
                "data": "upline",
                className: 'text-center align-middle'
            },
            {
                "data": "position",
                className: 'text-center align-middle'
            },
            {
                "data": "invite_count",
                className: 'text-center align-middle'
            },
            {
                "data": "phone_no",
                className: 'text-center align-middle'
            },
            {
                "data": "email",
                className: 'text-center align-middle'
            },
            {
                "data": "line",
                className: 'text-center align-middle'
            }

        ],
        "ordering": false,
        "searching": false
    });

    function fnSearch() {
        table.ajax.reload();
    }
</script>
@endsection
