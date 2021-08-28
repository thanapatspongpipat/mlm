@extends('layouts.master')

@section('title')Package @endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
        @slot('title') Package @endslot
    @endcomponent

    <div class="row">

        <!-- end col -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                          <button class="btn btn-primary" style="float: right;" type="button" id="create_btn"> Add Package </button>
                    </div>


                    <div class="row">

                        <table id="simple_table" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="col">Img</th>
                                    <th scope="col">Package</th>
                                    <th scope="col">price</th>
                                    <th scope="col">updated_at</th>
                                    <th scope="col">Action</th>
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
        <!-- end col -->
    </div>
    <!-- end row -->
    <!--  Update Profile example -->
    <div class="modal fade update-profile" id="simpleModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel"><span id="modal_title"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="package-form">
                        @csrf
                        <input type="hidden" class="formInput" name="package_id" value="" id="package_id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name</label>
                            <input type="text" class="formInput form-control" id="name" value="" name="name"
                                placeholder="Enter package name" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Package Price</label>
                            <input type="number" name="price" id="price" step="0.01" class="formInput form-control" placeholder="Enter package price" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Package Image</label>
                            <input type="file" class="form-control formInput" accept="image/*" name="" id="imgFile"  onchange="loadFile(event)" >
                            <input type="hidden" id="imgbase64" name="imgbase64" value="" />
                        </div>

                        <div class="mb-3">
                            <img id="output" max-width="300" style="max-height: 300px;" class="img-responsive form-control" />
                        </div>


                        <div class="mt-3 d-grid">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"> SAVE </button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
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
                "processing": false,
                "serverSide": false,
                "info": false,
                "searching": true,
                "responsive": true,
                "bFilter": false,
                "destroy": true,
                "ajax": {
                    "url": "{{ route('package.show') }}",
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
                        "data": "id",
                        "render": function (data, type, full) {

                            var text = `<img src="" alt="" class="avatar-md h-auto d-block rounded center">`;
                            if(full.image){
                                text = `<a href="{{ URL::asset('${full.image}') }}" targer="_blank"><img src="{{ URL::asset('${full.image}') }}" alt="" class="avatar-md h-auto d-block rounded center"></a>`;
                            }

                            return text;
                        }
                    },
                    {
                        "data": "name",
                    },
                    {
                        "data": "price",
                        "render": function (data, type, full) {
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    {
                        "data": "updated_at",
                        "render": function (data, type, full) {
                            return moment(data).format('DD-MM-YYYY HH:mm');
                        }
                    },
                    {
                        "data": "id",
                        "render": function (data, type, full) {
                            var obj = JSON.stringify(full);
                            var button = `
                            <button type="button" class="btn btn-sm btn-info" onclick='showInfo(${obj})'> edit </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick='destroy(${data})'> delete </button>
                            `;
                            return button;

                        }
                    },
                ],
            });
        });


        $("#create_btn").click(function () {
            console.log('sjpe');
            document.getElementById("imgFile").value = "";
             document.getElementById("imgbase64").value = "";
            $('#modal_title').text('Add New Package');
            $('.formInput').val('');
            $('#output').attr('src','');
            $('#simpleModal').modal("show");

        });

        $('#package-form').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            console.log('OK');
            $.ajax({
                type: "method",
                method: "POST",
                url: "{{ route('package.store') }}",
                processData: false,
                contentType: false,
                data: formData,
                success: function (res) {
                    console.log(res);
                    console.log('successsss');
                    Swal.fire(res.title, res.msg, res.status);
                     $('#simpleModal').modal("hide");
                    simple.ajax.reload();
                }
            });
        });

        function destroy(id){

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to remove this​ package?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7A7978',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'no',
                confirmButtonText: 'yes',
            }).then((result) => {
                if (result.value) {
                    $.post("{{  route('package.delete')  }}", data = {
                            _token: '{{ csrf_token() }}',
                            id: id,
                        },
                        function (res) {
                            Swal.fire(res.title, res.msg, res.status);
                           simple.ajax.reload();
                        },
                    );
                }
            });
        }

        function showInfo(obj){
            document.getElementById("imgFile").value = "";
            document.getElementById("imgbase64").value = "";

            $('#modal_title').text('Edit Package');
            $('#simpleModal').modal("show");
            $('#id').val(obj.id);
            $('#name').val(obj.name);
            $('#price').val(obj.price);
            $('#output').attr('src', `{{ URL::asset('${obj.image}') }}`);
        }

        var loadFile = function(event) {
            // var image = document.getElementById('output');
            // image.src = URL.createObjectURL(event.target.files[0]);
            resizeImages(event.target.files[0],function(url){
                $('#imgbase64').val(url);
            });

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#output').attr('src', e.target.result);
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
            var perferedWidth = 2048;
            var ratio = perferedWidth / img.width;
            var canvas = $("<canvas>")[0];
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            var imgfile = canvas.toDataURL('image/jpeg', 0.5);
            return imgfile;
        }
        </script>
@endsection
