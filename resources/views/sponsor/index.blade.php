@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection


@section('css')
<style>
    .icon-table-row{
        font-size: 2.5rem;
    }
</style>
@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') ข้อมูลทีม @endslot
@slot('title') ตารางแนะนำ @endslot
@endcomponent

<div class="row">


    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-4">ค้นหาข้อมูล</h4>
                    <button type="button" class="btn btn-light waves-effect" id="btn-search">
                        <i class="bx bx-search-alt-2 font-size-16 align-middle me-2"></i> ค้นหา
                    </button>
                </div>
                <div class="col-sm-12 mt-4">
                    <div class="row mb-3 ">
                        <label for="horizontal-password-input" class="col-sm-4 col-form-label">รหัสสมาชิก</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="horizontal-password-input" placeholder="รหัสสมาชิก" value="{{ auth()->user()->id }}">
                        </div>
                    </div>
                    <div class="row mb-3 ">
                        <label for="horizontal-password-input" class="col-sm-4 col-form-label">ระดับชั้น</label>
                        <div class="col-md-8">
                            <select class="form-select" name="lavel" id="level-input">
                                @foreach ($levels as $key=>$level)
                                <option value="{{ $level['value'] }}" {{ $key==0 ? 'selected' : '' }}>{{ $level['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-4">ค้นหาข้อมูล</h4>
                </div>
                <div class="col-sm-12 mt-4">
                    <div class="d-flex align-items-end flex-column">
                        <span>สมาชิกใต้สายงาน</span>
                        <h4 class="mt-1" id="member_under">0</h4>
                    </div>
                    <hr class="mt-0">
                    <div class="d-flex align-items-end flex-column">
                        <span>Small</span>
                        <h4 class="mt-1" id="s-count">0</h4>
                    </div>
                    <hr class="mt-0">
                    <div class="d-flex align-items-end flex-column">
                        <span>Mediem</span>
                        <h4 class="mt-1" id="m-count">0</h4>
                    </div>
                    <hr class="mt-0">
                    <div class="d-flex align-items-end flex-column">
                        <span>Dealer</span>
                        <h4 class="mt-1" id="d-count">0</h4>
                    </div>
                    <hr class="mt-0">
                    <div class="d-flex align-items-end flex-column">
                        <span>Super Dealer</span>
                        <h4 class="mt-1" id="sd-count">0</h4>
                    </div>
                    <hr class="mt-0">
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>


    <div class="col-12">
        <div class="card">










            <div class="card-body">

                <!-- <h4 class="card-title">Default Datatable</h4> -->
                <!-- <p class="card-title-desc">DataTables has most features enabled by
                        default, so all you need to do to use it with your own tables is to call
                        the construction function: <code>$().DataTable();</code>.
                    </p> -->

                <table id="member-datatable" class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                        <tr>
                            <th width="65%">สมาชิก</th>
                            <th width="15%" class="text-center">ชั้นที่</th>
                            <th width="20%" class="text-center">วันที่สมัคร</th>
                        </tr>
                    </thead>


                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')
<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<!-- Datatable init js -->
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>


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
    $(document).ready(function() {
        function dataTableSet(d){

            d.member = $('#horizontal-password-input').val()
            d.level = $('#level-input').val()
            return d
        }
        function dataSrcSet(json){
            $('#member_under').html(json.recordsTotal)
            $('#s-count').html(json.s_count)
            $('#m-count').html(json.m_count)
            $('#d-count').html(json.d_count)
            $('#sd-count').html(json.sd_count)
            return json.data;
        }
        var table = $('#member-datatable').DataTable({
            serverSide: true,
            ajax: {
                url: '{{ route("sponsor.list") }}',
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                "data": dataTableSet,
                "dataSrc": dataSrcSet
            },
            "paging": false,
            "columns": [{
                    "data": "member",
                    className: 'align-middle'
                },
                {
                    "data": "level",
                    className: 'text-center align-middle'
                },
                {
                    "data": "date",
                    className: 'text-center align-middle'
                }

            ],
            "ordering": false,
            "searching": false,
        });
        $('#btn-search').on('click', function(){
            table.ajax.reload();
        })
    });
</script>
@endsection
