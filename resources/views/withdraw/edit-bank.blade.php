@extends('layouts.master')

@section('title')  Bank Accont   @endsection

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
        @slot('title') บัญชีธนาคารสำหรับถอนเงิน   @endslot


    @endcomponent

    <div class="row">

        <div class="col-md-3"></div>

        <div class="col-md-6">
            <div class="row">
                    <div class="col-md-12">
                        <h4> <i class="bx bxs-bank"></i> บัญชีธนาคารสำหรับถอนเงิน </h4>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('withdraw.edit-bank.store') }}" class="form-horizontal" method="POST" enctype="multipart/form-data" id="package-form">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="bank" class="form-label">ธนาคาร</label>
                                        <select name="bank" id="bank" class="form-control">
                                            @if($bankAccount->bank_id)
                                                 <option value="{{ @$bankAccount->bank_id }}">{{ @$bankAccount->bank->name }}</option>
                                            @endif

                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}"> {{ $bank->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="branch" class="form-label">สาขา</label>
                                        <input type="text" name="branch" id="branch" value="{{ $bankAccount->branch }}" class="form-control" placeholder="-" >
                                    </div>


                                    <div class="mb-3">
                                        <label for="accountName" class="form-label">ชื่อบัญชี</label>
                                        <input type="text" name="accountName" id="accountName" value="{{ $bankAccount->account_name }}" class="form-control" placeholder="-" >
                                    </div>

                                    <div class="mb-3">
                                        <label for="accountNumber" class="form-label">เลขที่บัญชี</label>
                                        <input type="text" name="accountNo" id="accountNo" class="form-control" value="{{ $bankAccount->account_no }}" placeholder="-" >
                                    </div>


                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit"> บันทึกและกลับไปหน้าดำเนินการชำระเงิน </button>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <a href="{{ route('withdraw.index') }}" class="btn btn-white waves-effect waves-light" > <i class="bx bx-arrow-back"></i> ยกเลิก </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                  </div>
            </div>
        </div>

        <div class="col-md-3"></div>
    </div>

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

    <script>
        $(document).ready(function () {
            $('#simple_table').DataTable();
        });



    </script>
@endsection
