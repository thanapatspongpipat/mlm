@extends('layouts.master')

@section('title') @lang('translation.Data_Tables') @endsection



@section('css')

    <style>
        div.box-product {
            height: 200px;
        }
        .cursor-pointer{
        cursor: pointer;
        }
    </style>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') สมาชิก @endslot
        @slot('title') เลือกสินค้า @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">



                <div class="col-xl-12   ">
                    <div class="card">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between p-5">
                                @foreach ($products as $product)
                                    <div class="col-md-3 col-sm-6 p-3">
                                        <a href="{{ route('createView', ['product_id'=>$product->id, 'upline_id'=>$upline_id, 'position'=>$position]) }}">
                                            <div class="row border- rounded- box-product-" style="border: 2px solid black;">
                                                <div class="d-flex justify-content-center p-4">
                                                    <img src="//admin.happinesscorp.me/{{$product->image}}" style="height: 4.5rem;width: 4.5rem;">
                                                </div>
                                                <span class="text-dark"><b>Package</b> {{$product->name}}</span>
                                                <span class="text-dark"><b>ราคา</b> {{$product->price}}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>







            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
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

@if (session('modal'))
        <script>
            $(document).ready(function(){
                $('#modal-status').modal('show')
            })
        </script>
    @endif

@endsection
