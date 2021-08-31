@extends('layouts.master')

@section('title')Cash - Wallet   @endsection
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
        @slot('title') Cash - Wallet   @endslot


    @endcomponent

    <div class="row">


        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium"> Balance </p>

                            <h4 class="mb-0">฿ {{ number_format($wallet->balance, 2) }}</h4>
                        </div>
                        <i class="bx bx-wallet text-primary display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <a href="{{ route('deposit.index') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <p class="text-muted fw-medium"> Deposit </p>
                                <h4 class="mb-0">฿ {{ number_format($wallet->deposit, 2) }}</h4>
                            </div>
                            <i class="bx bx-down-arrow-alt text-success display-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('withdraw.index') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <p class="text-muted fw-medium rt"> Withdraw </p>
                                <h4 class="mb-0">฿ {{ number_format($wallet->withdraw, 2) }}</h4>
                            </div>
                            <i class="bx bx-up-arrow-alt text-danger display-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>



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
                                data-provide="datepicker" placeholder="From">
                        </div>

                        <div class="col-md-6">
                            <br>
                            {{-- <label for="to" class="form-label">To</label> --}}
                            <input type="text" name="to" id="to" class="form-control datepicker"
                                data-provide="datepicker" placeholder="To">
                        </div>

                        <div class="col-md-6">
                            <br>
                            {{-- <label for="type" class="form-label">Type</label> --}}
                            <select name="type" id="type" class="form-control">
                                <option value="all"> All </option>
                                <option value="in"> Income </option>
                                <option value="out"> Outcome </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <br>
                            <button type="button" class="btn btn-primary form-control" onclick="filter1()"> <i
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
            filterFirst()
        });

    function filterFirst() {
            var from = '';
            var to = moment().format('DD-MM-YYYY');
            var type = 'all';

            $.post("{{  route('wallet.cash-wallet.search')  }}", data = {
                    _token: '{{ csrf_token() }}',
                    from: from,
                    to: to,
                    type: type,
                },
                function (res) {
                    $('#appendTable').html(res.html);
                    $('#simple_table').DataTable({
                           "searching": true,
                            "responsive": true,
                            "bFilter": false,
                            "bLengthChange": true,
                            "destroy": true,
                            "pageLength": 50,
                            "order": [
                                [0, "desc"]
                            ],
                    });
                },
            );
        }


        function filter1() {
            var from = $('#from').val();
            if(from != null || from != ''){
                from = moment(from).format('DD-MM-YYYY');
            }

            var to = $('#to').val();
            if(to != null || to != ''){
                to = moment(to).format('DD-MM-YYYY');
            }

            var type = $('#type').val();

            $.post("{{  route('wallet.cash-wallet.search')  }}", data = {
                    _token: '{{ csrf_token() }}',
                    from: from,
                    to: to,
                    type: type,
                },
                function (res) {
                    $('#appendTable').html(res.html);
                    $('#simple_table').DataTable({
                           "searching": true,
                            "responsive": true,
                            "bFilter": false,
                            "bLengthChange": true,
                            "destroy": true,
                            "pageLength": 50,
                            "order": [
                                [0, "desc"]
                            ],
                    });
                },
            );
        }

    </script>
@endsection
