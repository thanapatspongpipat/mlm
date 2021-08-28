@extends('layouts.master')

@section('title') Wallets   @endsection
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
        @slot('title') Wallets   @endslot


    @endcomponent

    <div class="row">

        <div class="col-md-3"></div>

        @if($cashWallet != null)
        <div class="col-md-3">
            <a href="{{ route('wallet.cash-wallet.index') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="mb-0">฿ {{ number_format($cashWallet->balance, 2) }}</h4>
                                <br>
                                <p class="text-muted fw-medium"> CASH WALLET </p>
                            </div>
                            <i class="bx bx-wallet text-primary display-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @else
        <div class="col-md-3">
            <a href="{{ route('wallet.cash-wallet.create') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="mb-0 text-primary"> CREATE NEW WALLET </h4>
                                <br>
                                <p class="text-muted fw-medium"> CASH WALLET </p>
                            </div>
                            <i class="bx bx-wallet text-primary display-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($coinWallet != null)
        <div class="col-md-3">
            <a href="{{ route('wallet.coin-wallet.index') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="mb-0">฿ {{ number_format($coinWallet->balance, 2) }}</h4>
                                <br>
                                <p class="text-muted fw-medium rt"> COIN WALLET </p>
                            </div>
                            <i class="bx bx-wallet text-success display-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @else
        <div class="col-md-3">
            <a href="{{ route('wallet.coin-wallet.create') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="mb-0 text-success"> CREATE COIN WALLET </h4>
                                <br>
                                <p class="text-muted fw-medium rt"> COIN WALLET </p>
                            </div>
                            <i class="bx bx-wallet text-success display-4"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @endif

        <div class="col-md-3"></div>
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

            $.post("{{  route('wallet.cash-wallet.search')  }}", data = {
                    _token: '{{ csrf_token() }}',
                    from: from,
                    to: to,
                    type: type,
                },
                function (res) {
                    $('#appendTable').html(res.html);
                    $('#simple_table').DataTable(
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
