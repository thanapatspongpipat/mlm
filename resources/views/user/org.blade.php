@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection



@section('css')
<link href="{{ URL::asset('/assets/libs/org-chart/src/css/jquery.orgchart.css') }}" rel="stylesheet" type="text/css" />


<style>
    #chart-container {
        font-family: Arial;
        height: 420px;
        border: 1px solid #aaa;
        overflow: auto;
        text-align: center;
    }

    #github-link {
        display: inline-block;
        background-image: url("https://dabeng.github.io/OrgChart/img/logo.png");
        background-size: cover;
        width: 64px;
        height: 64px;
        position: absolute;
        top: 0;
        left: 0;
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
        <div class="card">



            <div class="col-xl-12   ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">ค้นหา</h4>

                        <div class="d-flex justify-content-center">
                            <form class="col-sm-7">
                                <div class="row mb-4">
                                    <label class="col-sm-3 col-form-label">วันที่ทำรายการ</label>
                                    <div class="col-sm-6">
                                        <div class="input-daterange input-group" id="datepicker6" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                            <input type="text" class="form-control" name="start" placeholder="Start Date" />
                                            <input type="text" class="form-control" name="end" placeholder="End Date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-email-input" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="horizontal-email-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">ชื่อในระบบ</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="horizontal-password-input">
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div>
                                            <button type="submit" class="btn btn-primary w-md">ค้นหา</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>






            <div class="card-body">

                <!-- <h4 class="card-title">Default Datatable</h4> -->
                <!-- <p class="card-title-desc">DataTables has most features enabled by
                        default, so all you need to do to use it with your own tables is to call
                        the construction function: <code>$().DataTable();</code>.
                    </p> -->


                <div id="chart-container"></div>




            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')
<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}">
    < /scrip> <
    script src = "{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}" >
</script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<!-- Datatable init js -->

<script src="{{ asset('/assets/libs/org-chart/src/js/jquery.orgchart.js') }}"></script>

<script>
    (function($) {
        $(function() {
            var ds = {
                'name': 'Lao Lao',
                'title': 'general manager',
                'children': [{
                        'name': 'Bo Miao',
                        'title': 'department manager'
                    },
                    {
                        'name': 'Su Miao',
                        'title': 'department manager',
                        'children': [{
                                'name': 'Tie Hua',
                                'title': 'senior engineer'
                            },
                            {
                                'name': 'Hei Hei',
                                'title': 'senior engineer',
                                'children': [{
                                        'name': 'Pang Pang',
                                        'title': 'engineer'
                                    },
                                    {
                                        'name': 'Xiang Xiang',
                                        'title': 'UE engineer'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        'name': 'Hong Miao',
                        'title': 'department manager'
                    },
                    {
                        'name': 'Chun Miao',
                        'title': 'department manager'
                    }
                ]
            };

            function templateCustom(){
                return "a"
            }
            var oc = $('#chart-container').orgchart({
                'pan': true,
                nodeTemplate: templateCustom,
                'data': ds,
                'depth': 2,
                'nodeContent': 'title'
            });

        });
    })(jQuery);
</script>
@endsection
