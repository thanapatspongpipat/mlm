
@extends('layouts.master')

@section('title') Withdraw   @endsection
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

    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

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
        @slot('title') Withdraw   @endslot
    @endcomponent
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <i class="bx bx-check-double" ></i>

                    </div>

                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
         <div class="col-md-3"></div>
    </div>

    <div class="row">

        <div class="col-md-3"></div>

        <div class="col-md-6">
            <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('wallet.cash-wallet.index') }}">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <h4 class="mb-0">฿ <span id="balance"></span></h4>
                                            <br>
                                            <p class="text-muted fw-medium"> CASH WALLET BALANCE</p>
                                        </div>
                                        <i class="bx bx-wallet text-primary display-4"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="#" class="form-horizontal" method="POST" id="withdraw-form">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="amount" class="form-label"> จำนวนเงิน </label>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text"> <i class="bx bx-money"></i></label>
                                             <input type="number" class="formInput form-control" id="amount" value="" min="1" step="0.01" placeholder="กรอกจำนวนเงิน" required>
                                            <label class="input-group-text">฿ </label>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="mb-3">
                                        <label for="bank" class="form-label">ธนาคาร</label> <a href="{{ route('withdraw.edit-bank') }}"><span class="badge bg-warning float-end"> <i class="bx bx-edit-alt"></i> edit</span></a>
                                        <input type="text" name="bank" id="bank" value="{{ @$bankAccount->bank->name }}" class="form-control" placeholder="-" disabled>
                                        <input type="hidden" name="bankAccountId" id="bankAccountId" value="{{ $bankAccount->id }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank" class="form-label">ชื่อบัญชี</label>
                                        <input type="text" name="accountName" id="accountName" value="{{ $bankAccount->account_name }}" class="form-control" placeholder="-" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank" class="form-label">เลขที่บัญชี</label>
                                        <input type="text" name="accountNo" id="accountNo" class="form-control" value="{{ $bankAccount->account_no }}" placeholder="-" disabled>
                                    </div>



                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="button" onclick="withdrawCash()"> WITHDRAW </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                  </div>
            </div>
        </div>

        <div class="col-md-3"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h5>History</h5>
                    <div class="row">

                        <table id="simple_table" style="font-size:90%;" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">วันที่ทำรายการ</th>
                                    <th scope="col">รายละเอียด</th>
                                    <th scope="col">ธนาคาร</th>
                                    <th scope="col">เลขบัญชี</th>
                                    <th scope="col">จำนวนที่ถอน</th>
                                    <th scope="col">จำนวนเงินที่ได้รับ</th>
                                    <th scope="col">หัก ณ ที่จ่าย </th>
                                    <th scope="col">สถานะ</th>

                                </tr>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            <!-- end card -->
        </div>
    </div>

    <!--  Note modal example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" id="noteModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"> หมายเหตุ </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                      {{-- <p class="text-center" id="note"> </p> --}}
                      <h5 class="text-center text-danger" id="note"></h5>
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('script')
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/dashboard-blog.init.js') }}"></script>
     <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs//moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('/assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function () {
            // $('#simple_table').DataTable();
            var simple = '';
            callBalance();
        });

        function withdrawCash(){
            var amount = $('#amount').val();
            var bankAccountId = $('#bankAccountId').val();
            console.log(bankAccountId);
            if(amount == null || amount == undefined || amount == ''){
                Swal.fire('แจ้งเตือน!', 'กรุณากรอกจำนวนเงิน', 'warning');
            }else if(bankAccountId == ''){
                Swal.fire('แจ้งเตือน!', 'กรุณากรอกข้อมูลธนาคาร', 'warning');
            }else{
                var amountShow = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่ ?',
                    text: `คุณต้องการถอนเงินจำนวน ${amountShow} บาทนี้หรือไม่ `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: '#7A7978',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                    }).then((result) => {
                        if (result.value) {
                            $.post("{{  route('withdraw.store')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                amount: amount,
                                bankAccountId: bankAccountId,
                                },
                                function (res) {
                                    Swal.fire(res.title, res.msg, res.status);
                                    simple.ajax.reload();
                                    $('#amount').val('');
                                    callBalance();
                                },
                            );
                        }

                    });



            }
        }

        $('#simple_table').ready(function () {
            simple = $('#simple_table').DataTable({
                "processing": true,
                "serverSide": true,
                "info": false,
                "searching": true,
                "responsive": true,
                "bFilter": true,
                "destroy": true,
                "order": [
                    [0, "desc"]
                ],
                "ajax": {
                    "url": "{{ route('withdraw.show') }}",
                    "method": "POST",
                    "data": {
                        "_token": "{{ csrf_token()}}",
                    },
                },
                'columnDefs': [
                    {
                        "targets": [0,1,2,3,4,5,6,7],
                        "className": "text-center",
                    },
                ],
                "columns": [

                    {
                        "data": "transaction_timestamp",
                         "render": function (data, type, full) {
                            return moment(data).format('DD-MM-YYYY HH:mm');
                        }
                    },
                    {
                        "data": "detail",
                    },

                    {
                        "data": "id",
                        "render": function (data, type, full) {
                            var text = ''
                            if(full.bank){
                                text = full.bank.name;
                            }


                            return text;
                        }
                    },
                    {
                        "data": "bank_account_no",
                    },

                    {
                        "data": "amount",
                        "render": function (data, type, full) {
                            data = data ? data : '';
                            return  data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                    },

                       {
                        "data": "total_amount",
                        "render": function (data, type, full) {
                            data = data ? data : '';
                            return  data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    {
                        "data": "tax",
                        "render": function (data, type, full) {
                            data = data ? data : '';
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    {
                        "data": "status",
                        "render": function (data, type, full) {
                            var text = ''
                            switch (data) {
                                case 0:
                                    text = '<span class="text-warning"> รอดำเนินการ </span>'
                                    break;
                                case 1:
                                     text = '<span class="text-success"> สำเร็จ </span>'
                                    break;
                                case 2:
                                     text = `<a href="#" onclick="showNote('${full.note ? full.note : '' }')" class="text-danger"> <u>ยกเลิก<u> </a>`;
                                    break;
                                default:
                                    break;
                            }
                            return text;
                        }

                    },
                ],
            });
        });

        function callBalance() {
            $.post("{{  route('withdraw.get-balance')  }}", data = {
                    _token: '{{ csrf_token() }}',

                },
                function (res) {
                    $('#balance').text(res.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                },
            );

        }


        function showNote(note){
            // console.log(note)
            $('#noteModal').modal('show');
            $('#note').text(note);
        }






    </script>
@endsection


