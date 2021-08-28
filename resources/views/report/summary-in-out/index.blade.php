@extends('layouts.master')

@section('title') รายงานสรุปรายได้ @endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/fontawesome.min.css"  />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.css') }}">
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            }
    </style>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Dashboards @endslot
        @slot('title') รายงานสรุปรายได้  @endslot
    @endcomponent

    <div class="row">




        <!-- end col -->

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5> Filter </h5>
                        <div class="col-md-6">
                            <br>
                            {{-- <label for="from" class="form-label">From</label> --}}
                            <input type="text" name="from" id="from" class="form-control datepicker"
                                data-provide="datepicker" autocomplete="off" placeholder="จากวันที่">
                        </div>

                        <div class="col-md-6">
                            <br>
                            {{-- <label for="to" class="form-label">To</label> --}}
                            <input type="text" name="to" id="to" class="form-control datepicker"
                                data-provide="datepicker" autocomplete="off" placeholder="ถึงวันที่">
                        </div>

                        <div class="col-md-6">
                            <br>
                            {{-- <label for="type" class="form-label"> รูปแบบ </label> --}}
                            <select name="type" id="type" class="form-control">
                                <option value=""> -- เลือกรูปแบบรายงาน --</option>
                                <option value="d"> รายวัน </option>
                                <option value="m"> รายเดือน </option>
                                <option value="y"> รายปี </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <br>
                            <button type="button" class="btn btn-primary form-control" onclick="filter()"> <i
                                    class="bx bx-search-alt-2"></i> Search </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="appendTable">

        </div>

        <!-- end col -->
    </div>
    <!-- end row -->
    <!--  Update Profile example -->
@endsection

@section('script')
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>

    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- blog dashboard init -->
    <script src="{{ URL::asset('/assets/js/pages/dashboard-blog.init.js') }}"></script>
     <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs//moment/moment.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
       <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('/assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function () {
            $('#simple_table').DataTable();
        });


        function filter() {
            var from = $('#from').val();
            var to = $('#to').val();
            var type = $('#type').val();

            $.post("{{  route('admin.report.summary-in-out.show')  }}", data = {
                    _token: '{{ csrf_token() }}',
                    from: from,
                    to: to,
                    type: type,
                },
                function (res) {
                    $('#appendTable').html(res.html);
                    $('#simple_table').DataTable({
                           "processing": false,
                            "serverSide": false,
                            "info": false,
                            "searching": true,
                            "responsive": true,
                            "bFilter": false,
                            "bLengthChange": true,
                            "destroy": true,
                            "pageLength": 100,
                    });

                },
            );
        }

        $("#type").change(function(){
            $('#from').val('');
            $('#to').val('');
        });

        $(".datepicker").change(function(){
            $('#type').val('');
        });



    </script>
@endsection
