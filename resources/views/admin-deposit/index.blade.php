
@extends('layouts.master')

@section('title') รายการขอเติมเงิน   @endsection
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
        @slot('title') รายการขอเติมเงิน   @endslot
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h5>รายการขอเติมเงิน</h5>
                    <br>
                    <div class="row">

                        <table id="simple_table" style="font-size:90%;" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">วันที่ทำรายการ</th>
                                    <th scope="col">รายละเอียด</th>
                                    <th scope="col">username</th>
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">หลักฐาน</th>
                                    <th scope="col">ผู้ยืนยันรายการ</th>
                                    <th></th>
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

    <!--  Large modal example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" id="infoModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">หลักฐานการเติมเงิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                        <img id="output" max-width="300" style="max-height: 500px;" class="img-responsive form-control" />
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


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

            var simple = '';

        });

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
                    "url": "{{ route('admin.deposit.show') }}",
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
                        "data": "user.username",

                    },
                    {
                        "data": "amount",
                        "render": function (data, type, full) {
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    {
                        "data": "status",
                        "render": function (data, type, full) {
                            var text = ''
                            switch (data) {
                                case 0:
                                    text = '<span class="text-warning"> รอตรวจสอบ </span>'
                                    break;
                                case 1:
                                     text = '<span class="text-success"> สำเร็จ </span>'
                                    break;
                                case 2:
                                     text = '<span class="text-danger"> ยกเลิก </span>'
                                    break;
                                default:
                                    break;
                            }
                            return text;
                        }
                    },
                    {
                        "data": "slip_img",
                        "render": function (data, type, full) {
                            // var text = `<a target="_blank" href="${data}"> หลักฐานการโอน </a>`;
                            var text = `<a href="#" onclick="showInfo('{{ URL::asset('${data}') }}')"> ดูรูปภาพ </a>`;
                            return text;
                        }
                    },

                        {
                        "data": "status",
                        "render": function (data, type, full) {
                            var text = '';
                            if(data == 1){
                                text = full.approve_user.username;
                            }else if(data == 2){
                                text = full.cancle_user.username;
                            }else{
                                text = '';
                            }

                            return text;
                        }
                    },
                      {
                        "data": "status",
                        "render": function (data, type, full) {
                            var obj = JSON.stringify(full);
                            var text = ``;


                            if(data == 0){
                                text = `<button type="button" class="btn btn-sm btn-primary" onclick='deposit(${obj}, 1)'> เติมเงิน </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick='deposit(${obj}, 2)'> ยกเลิก </button>
                                    `;
                            }else{
                                text = `<button type="button" class="btn btn-sm btn-secondary" disabled> เติมเงิน </button>
                                        <button type="button" class="btn btn-sm btn-secondary" disabled> ยกเลิก </button>
                                    `;
                            }


                            return text;
                        }
                    },


                ],
            });
        });

        function showInfo(img){
            $('#infoModal').modal('show');
            $('#output').attr('src', img);
        }

        function deposit(obj, status){
            var amount = (obj.amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var username = obj.user.username;
            var id = obj.id;

            if(status == 1){
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะเติมเงิน ${amount} ให้กับ ${username} ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: '#7A7978',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {
                        console.log('OK')
                        $.post("{{  route('admin.deposit.store')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                status: status,
                            },
                            function (res) {
                                Swal.fire(res.title, res.msg, res.status);
                                simple.ajax.reload();
                            },
                        );
                    }
                });
            }else{
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะยกเลิกเติมเงิน ${amount} ให้กับ ${username} ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#7A7978',
                    cancelButtonColor: '#556ee6',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {
                        $.post("{{  route('admin.deposit.store')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                status: status,
                            },
                            function (res) {
                                Swal.fire(res.title, res.msg, res.status);
                                simple.ajax.reload();
                            },
                        );
                    }
                });
            }
        }




    </script>
@endsection


