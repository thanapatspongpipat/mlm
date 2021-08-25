@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') สมาชิก @endslot
        @slot('title') ข้อมูล สมาชิก @endslot
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
                                    <div class="row mb-4">
                                        <label  class="col-sm-3 col-form-label">วันที่ทำรายการ</label>
                                        <div class="col-sm-6">
                                            <div class="input-daterange input-group" id="datepicker6" data-date-format="dd M, yyyy"
                                                data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                                <input type="text" class="form-control" name="start" placeholder="Start Date" />
                                                <input type="text" class="form-control" name="end" placeholder="End Date" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-email-input" class="col-sm-3 col-form-label">Username</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="horizontal-email-input">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-password-input" class="col-sm-3 col-form-label">ชื่อในระบบ</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="horizontal-password-input">
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">

                                            <div>
                                                <button type="submit" class="btn btn-primary w-md">ค้นหา</button>
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
                                <th>วันที่ทำรายการ</th>
                                <th>Username</th>
                                <th>ชื่อในระบบ</th>
                                <th>ผู้แนะนำ</th>
                                <th>อัฟไลน์</th>
                                <th>ตำแหน่งว่าง</th>
                                <th>มือถือ</th>
                                <th>อีเมล์</th>
                                <th>ไลน์ไอดี</th>
                            </tr>
                        </thead>


                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

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


    <script>
        $(document).ready(function() {
            $('#member-datatable').DataTable({
                serverSide: true,
                ajax: {
                    url: '{{ route("memberUserList") }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    { "data": "date" },
                    { "data": "username" },
                    { "data": "name" },
                    { "data": "invite" },
                    { "data": "upline" },
                    { "data": "position" },
                    { "data": "phone_no" },
                    { "data": "email" },
                    { "data": "line" }

                ],
                "ordering": false,
                "searching": false
            });

            // //Buttons examples
            // var table = $('#datatable-buttons').DataTable({
            //     lengthChange: false,
            //     buttons: ['copy', 'excel', 'pdf', 'colvis']
            // });

            // table.buttons().container()
            //     .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            // $(".dataTables_length select").addClass('form-select form-select-sm');
        });
    </script>
@endsection
