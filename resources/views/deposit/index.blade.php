
@extends('layouts.master')

@section('title') Deposit   @endsection
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
        @slot('title') Deposit   @endslot
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

                        <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <h5 class="mb-1"> ข้อมูลการโอนเงิน </h5>
                                            <h6 class="mb-1"> ธนาคาร : {{ $comBank->bank_name }} สาขา {{ $comBank->bank_branch }}  </h6>
                                            <h6 class="mb-1"> ชื่อบัณชี  : {{ $comBank->bank_account_name }}  </h6>
                                            <h5 class="mb-1"> เลขบัญชี : <span class="text-primary"> {{ $comBank->bank_account_no }}  </span> </h5>
                                        </div>
                                        <i class="bx bxs-bank text-secondary display-4"></i>
                                    </div>
                                </div>
                            </div>

                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="#" class="form-horizontal" method="POST" id="deposit-form">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="amount" class="form-label"> จำนวนเงิน </label>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text"> <i class="bx bx-money"></i></label>
                                             <input type="number" class="formInput form-control" name="amount" id="amount" value="" min="1" placeholder="กรอกจำนวนเงิน" required>
                                            <label class="input-group-text">฿ </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="date" class="form-label">วันที่โอน</label>
                                                {{-- <input type="date" name="date" id="date" value="" class="form-control formInput" placeholder="-" required> --}}
                                                 <input type="text" name="date" id="date" class="form-control datepicker" data-provide="datepicker" placeholder="วันที่โอน">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="time" class="form-label">เวลาที่โอน</label>
                                                <input type="time" name="time" id="time" class="form-control formInput" value="" placeholder="-" required>
                                            </div>
                                         </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="detail" class="form-label">รายละเอียด</label>
                                        <input type="text" class="form-control formInput" name="detail" id="detail" placeholder="รายละเอียดการเติมเงิน" >
                                    </div>

                                    <input type="hidden" name="comBankAccount" id="comBankAccount" value="{{ $comBank->id }}">

                                    {{-- <div class="mb-3">
                                        <label for="bank" class="form-label">ธนาคารปลายทาง</label>
                                        <select name="bank" id="bank" class="form-control">
                                            <option value="{{ $comBank->id }}"> {{ $comBank->bank_name }} {{  $comBank->bank_account_no  }}</option>
                                        </select>
                                    </div> --}}

                                    <div class="mb-3">
                                        <label for="image" class="form-label">หลักฐานการโอน</label>
                                        <input type="file" class="form-control formInput" accept="image/*" name="" id="imgFile" placeholder="กรุณาเลือกรูปภาพ" style="display:none" onchange="loadFile(event)" required>
                                        <input type="hidden" id="imgbase64" name="imgbase64" value="" />
                                        <button style="display:block;" class="form-control" onclick="document.getElementById('imgFile').click()"> อัพโหลดรูป </button>

                                    </div>




                                    <div class="mb-3">
                                        <img id="output" max-width="300" style="max-height: 500px; display: none;" class="img-responsive form-control" />
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <br>
                                        <button class="btn btn-primary waves-effect waves-light" type="submit"> DEPOSIT </button>
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
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">หลักฐาน</th>
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
                        <img id="infoImg" max-width="300" style="max-height: 500px;" class="img-responsive form-control" />
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

        $('#simple_table').ready(function () {
            simple = $('#simple_table').DataTable({
                "processing": false,
                "serverSide": false,
                "info": false,
                "searching": true,
                "responsive": true,
                "bFilter": true,
                "destroy": true,
                "order": [
                    [0, "desc"]
                ],
                "ajax": {
                    "url": "{{ route('deposit.show') }}",
                    "method": "POST",
                    "data": {
                        "_token": "{{ csrf_token()}}",
                    },
                },
                'columnDefs': [
                    {
                        "targets": [0,1,2,3,4],
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
                        "data": "amount",
                        "render": function (data, type, full) {
                            return ' + ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
                                    text = `<a href="#" onclick="showNote('${full.note ? full.note : '' }')" class="text-danger"> <u>ยกเลิก<u> </a>`;
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
                            if(data != null){
                                 var text = `<a href="#" onclick="showInfo('{{ URL::asset('${data}') }}')"> ดูรูปภาพ </a>`;
                            }else{
                                 var text = `<a href="#"> - </a>`;
                            }

                            return text;
                        }
                    },
                ],
            });
        });

        $('#deposit-form').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            console.log('OK');
            $.ajax({
                type: "method",
                method: "POST",
                url: "{{ route('deposit.store') }}",
                processData: false,
                contentType: false,
                data: formData,
                success: function (res) {
                    console.log(res.status);
                    simple.ajax.reload();
                    if(res.status == 'success'){
                    $('#deposit-form').trigger("reset");
                       document.getElementById('output').style.display = "none";
                    }
                    Swal.fire(res.title, res.msg, res.status);

                }
            });
        });

        var loadFile = function(event) {
            // var image = document.getElementById('output');
            // image.src = URL.createObjectURL(event.target.files[0]);
            resizeImages(event.target.files[0],function(url){
                $('#imgbase64').val(url);
            });

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#output').attr('src', e.target.result);
                document.getElementById('output').style.display = "block";
            }
            reader.readAsDataURL(event.target.files[0]);
        };

        function resizeImages(file, com) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = new Image();
                img.onload = function () {
                    com(resizeInCanvas(img));
                };
                img.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }

        function resizeInCanvas(img) {
            var perferedWidth = 1048;
            var ratio = perferedWidth / img.width;
            var canvas = $("<canvas>")[0];
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            var imgfile = canvas.toDataURL('image/jpeg', 0.5);
            return imgfile;
        }

        function callBalance() {
            $.post("{{  route('deposit.get-balance')  }}", data = {
                    _token: '{{ csrf_token() }}',

                },
                function (res) {
                    $('#balance').text(res.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                },
            );
        }

        function showInfo(img){
            $('#infoModal').modal('show');
            $('#infoImg').attr('src', img);
        }


        function showNote(note){
            // console.log(note)
            $('#noteModal').modal('show');
            $('#note').text(note);
        }



    </script>
@endsection


