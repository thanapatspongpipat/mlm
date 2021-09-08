@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection



@section('css')
<link href="{{ URL::asset('/assets/libs/org-chart/src/css/jquery.orgchart.css') }}" rel="stylesheet" type="text/css" />



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
                            <div class="row mb-4" hidden>
                                <label class="col-sm-3 col-form-label">วันที่ทำรายการ</label>
                                <div class="col-sm-6">
                                    <div class="input-daterange input-group" id="datepicker6" data-language="th" data-date-format="dd-mm-yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                        <input type="text" class="form-control" name="start" placeholder="Start Date" />
                                        <input type="text" class="form-control" name="end" placeholder="End Date" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-email-input" class="col-sm-3 col-form-label">รหัสสมาชิก</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" value="{{auth()->user()->id}}" id="horizontal-email-input">
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


            <div class="card-body p-0">














            </div>
        </div>
        <!-- </div> -->
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="chart-container" style=" height:1200px ;background-color:#FFFEFF"></div>


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
    @if (session('modal'))
        <div class="modal fade bs-example-modal-center" id="modal-status" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    {{ session('modal') }}
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif

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

    @if (session('modal'))
        <script>
            $(document).ready(function(){
                $('#modal-status').modal('show')
            })
        </script>
    @endif




    <!-- chart -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.0.0/build/d3-flextree.js"></script>
    <div
      class="chart-container"
    ></div>

    <script>
      var chart;
    //   d3.csv(
    //     'https://raw.githubusercontent.com/bumbeishvili/sample-data/main/org.csv'
    //   ).then(data => {
        $.post('{{route("orgUplineList.info.array")}}', {_token: '{{ csrf_token() }}', id: 1}, (data)=>{
              console.log(data)
              chart = new d3.OrgChart()
                .container('.chart-container')
                .data(data)
                .nodeContent(function(d, i, arr, state) {
                    const colors = [
                    '#6E6B6F',
                    '#18A8B6',
                    '#F45754',
                    '#96C62C',
                    '#BD7E16',
                    '#802F74'
                    ];
                    const color = colors[d.depth % colors.length];
                    const imageDim = 80;
                    const lightCircleDim = 95;
                    const outsideCircleDim = 110;

                    return `
                        <div style="background-color:white; position:absolute;width:${
                        d.width
                        }px;height:${d.height}px;">
                        <div style="background-color:${color};position:absolute;margin-top:-${outsideCircleDim / 2}px;margin-left:${d.width / 2 - outsideCircleDim / 2}px;border-radius:100px;width:${outsideCircleDim}px;height:${outsideCircleDim}px;"></div>
                        <div style="background-color:#ffffff;position:absolute;margin-top:-${lightCircleDim /
                            2}px;margin-left:${d.width / 2 - lightCircleDim / 2}px;border-radius:100px;width:${lightCircleDim}px;height:${lightCircleDim}px;"></div>
                        <img src=" ${
                            d.data.imageUrl
                        }" style="position:absolute;margin-top:-${imageDim / 2}px;margin-left:${d.width / 2 - imageDim / 2}px;border-radius:100px;width:${imageDim}px;height:${imageDim}px;" />
                        <div class="card" style="top:${outsideCircleDim / 2 +
                            10}px;position:absolute;height:30px;width:${d.width}px;background-color:#3AB6E3;">
                            <div style="background-color:${color};height:28px;text-align:center;padding-top:10px;color:#ffffff;font-weight:bold;font-size:16px">
                                ${d.data.name}
                            </div>
                            <div style="background-color:#F0EDEF;height:28px;text-align:center;padding-top:10px;color:#424142;font-size:16px">
                                ${d.data.positionName}
                            </div>
                        </div>
                    </div>
        `;
                })
                .render();
          })
    //   });
    </script>



<script>
    $(document).ready(function(){
        // init()
    })
    function toUP(username) {
        $('#horizontal-email-input').val(username)
        init()
    }
    function showINfo(id){
        $.post('{{route("orgUplineList.info")}}', {_token: '{{ csrf_token() }}', id: id}, (data)=>{
            if(data){
                var html = `
                <div class="col-sm-6 p-3">
                    <b>รหัสสมาชิก:</b> <span class="p-2">`+data.id+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>ชื่อ - สกุล: </b><span class="p-2">`+data.firstname+` `+data.lastname+`</span>
                </div>
                <div class="col-sm-6 p-3">
                    <b>ตำแหน่ง: </b><span class="p-2">`+data.product.level+`</span>
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
            $('#chart-container').empty()
            var oc = $('#chart-container').orgchart({
                'pan': true,
                'data': data,
                'zoom': false,
                'depth': 2,
                // 'direction': 't2b',
                'nodeContent': 'title',
                nodeTemplate: templateCustom

            });
            setContainerMiddle()
        })
    }



    function templateCustom(data) {
        if (data.empty) {
            return `
                    <div class="card-chard">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="mb-4">
                                    <img class="rounded-circle avatar-sm" src="/assets/images/users/avatar.jpg" alt="">
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
                                <p class="text-muted mb-1">รหัสสมาชิก ` + data['id'] + `</p>
                                <p class="text-muted mb-1">` + data['name'] + `</p>
                                <h5 class="font-size-15 mb-0"><a href="javascript: void(0);" class="text-dark">แพ็กเกจ ` + data.level_space + `</a></h5>
                            </div>
                            <div class="card-footer bg-transparent border-top">
                                <div class="row p-3">
                                    <button type="button" class="btn btn-outline-primary w-sm-" onclick="toUP('` + data['id'] + `')">ขึ้นบน</button>
                                    <button type="button" class="btn btn-outline-primary w-sm-" onclick="showINfo(` + data['id'] + `)">รายละเอียด</button>
                                </div>
                            </div>

                        </div>
                    </div>`
        }
    }
    function setContainerMiddle(){
        // set positopn of scrollbar middle
        var outerContent = $('#chart-container');
        var innerContent = $('#chart-container > div');
        outerContent.scrollLeft((innerContent.width() - outerContent.width()) / 2);
        outerContent.scrollTop((innerContent.height() - outerContent.height()) / 2);
    }
</script>
@endsection
