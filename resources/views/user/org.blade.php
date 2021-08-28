@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection



@section('css')
<link href="{{ URL::asset('/assets/libs/org-chart/src/css/jquery.orgchart.css') }}" rel="stylesheet" type="text/css" />


<style>
    #chart-container {
        /* font-family: Arial; */
        /* height: 820px; */
        border: 2px dashed #aaa;
        border-radius: 5px;
        overflow: auto;
        text-align: center;
    }



    .orgchart {
        background: #f8f8fb;
    }
</style>

@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') สมาชิก @endslot
@slot('title') ข้อมูล สมาชิก @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <!-- <div class="card"> -->



        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">ค้นหา</h4>

                    <div class="d-flex justify-content-center">
                        <form class="col-sm-7">
                            <div class="row mb-4">
                                <label class="col-sm-3 col-form-label">วันที่ทำรายการ</label>
                                <div class="col-sm-6">
                                    <div class="input-daterange input-group" id="datepicker6" data-language="th" data-date-format="dd-mm-yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                        <input type="text" class="form-control" name="start" placeholder="Start Date" />
                                        <input type="text" class="form-control" name="end" placeholder="End Date" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-email-input" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" value="{{auth()->user()->username}}" id="horizontal-email-input">
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-9">

                                    <div>
                                        <button type="button" class="btn btn-primary w-md" onclick="init()">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->


            <div class="card-body">


                <div id="chart-container"></div>


            </div>
        </div>
        <!-- </div> -->
    </div> <!-- end col -->
</div> <!-- end row -->
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-hidden="true" id="modal-info">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-tag-name">ข้อมูล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="row" id="html-modal-show">
                        <div class="col-sm-6">
                            <b>รหัสสมาชิก</b><span>   Pheemwara479</span>
                        </div>
                        <div class="col-sm-6">
                            <b>ชื่อ - สกุล</b><span>   ภีมวรา สงวนเรือง</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('script')
<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<!-- Datatable init js -->

<script src="{{ asset('/assets/libs/org-chart/src/js/jquery.orgchart.js') }}"></script>


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
    // (function($) {
    //     $(function() {



    //     });
    // })(jQuery);
    function toUP(username) {
        $('#horizontal-email-input').val(username)
        init()
    }
    function showINfo(id){
        $.post('{{route("orgUplineList.info")}}', {_token: '{{ csrf_token() }}', id: id}, (data)=>{
            if(data){
                var html = `
                <div class="col-sm-6 p-3">
                    <b>รหัสสมาชิก:</b> <span class="p-2">`+data.username+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>ชื่อ - สกุล: </b><span class="p-2">`+data.firstname+` `+data.lastname+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>ผู้แนะนำ: </b><span class="p-2">`+data.username+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>วันที่สมัคร: </b><span class="p-2">`+data.username+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>เบอร์โทร: </b><span class="p-2">`+data.phone_number+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>Line ID: </b><span class="p-2">`+data.line+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>Facebook: </b><span class="p-2">`+data.fb+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>Email: </b><span class="p-2">`+data.email+`</span>
                </div>
                `
                $('#html-modal-show').empty()
                $('#html-modal-show').html(html);
                $('#modal-info').modal('show')
            }
        })
    }

    function init() {
        $.post("{{route('orgUplineList')}}", {
            _token: '{{ csrf_token() }}',
            username: $('#horizontal-email-input').val(),
            start: $('input[name=start]').val(),
            end: $('input[name=end]').val()
        }, function(data, status) {
            console.log(data)
            $('#chart-container').empty()
            var oc = $('#chart-container').orgchart({
                'pan': false,
                'data': data,
                // 'depth': 2,
                'nodeContent': 'title',
                nodeTemplate: templateCustom

            });

        })
    }
    init()

    function templateCustom(data) {
        if (data.empty) {
            return `
                    <div class="card-chard">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="mb-4">
                                    <img class="rounded-circle avatar-sm" src="/assets/images/brands/slack.png" alt="">
                                </div>
                                <p class="text-muted">ไม่พบผู้สมัคร</p>
                                <a href="/member/items/` + data['parent_id'] + `/` + data['position'] + `" class="btn btn-primary btn-rounded waves-effect waves-light">สมัครตำแหน่งนี้</a>
                            </div>

                        </div>
                    </div>`
        } else {
            return `
                    <div class="card-chard">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="mb-4">
                                    <img class="rounded-circle avatar-sm" src="` + data['avatar'] + `" alt="">
                                </div>
                                <h5 class="font-size-15 mb-1"><a href="javascript: void(0);" class="text-dark">Level Silver</a></h5>
                                <p class="text-muted">` + data['name'] + `</p>
                            </div>
                            <div class="card-footer bg-transparent border-top">
                                <div class="contact-links d-flex font-size-20">
                                    <div class="btn-group btn-group-example" role="group">
                                        <button type="button" class="btn btn-outline-primary w-sm" onclick="toUP('` + data['username'] + `')">ขึ้นบน</button>
                                        <button type="button" class="btn btn-outline-primary w-sm" onclick="showINfo(` + data['id'] + `)">รายละเอียด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`
        }
    }
</script>
@endsection
