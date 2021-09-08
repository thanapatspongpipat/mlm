@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/org-chart/src/css/jquery.orgchart.css') }}" rel="stylesheet" type="text/css" />
<style>
blockquote
{
	font-style: italic;
	font-family: Georgia, Times, "Times New Roman", serif;
	padding: 2px 0;
	border-style: solid;
	border-color: rgb(190, 190, 190);
	border-width: 0;
    display: block;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 40px;
    margin-inline-end: 40px;
}
</style>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') ภาพรวม @endslot
        @slot('title') หน้าหลัก @endslot
    @endcomponent

    <div class="row">
        
        <div class="col-md-12">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="row">
                        <div class="media mb-3 text-center">
                            <div class="media-body">
                                <h5 class=" fw-medium text-muted">รายได้รวมทั้งหมด</h5>
                                <h4 class="mb-0 text-success">
                                    ฿ {{ number_format($totalIncome, 2)  ?? "0.00"}}         
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- Cash section -->
            <div class="col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="row">
                            <div class="media mb-3">
                                <div class="media-body">
                                    <p class="text-muted fw-medium">Cash Wallet</p>
                                    <h4 class="mb-0">
                                        <a href="{{route('wallet.cash-wallet.index')}}">฿
                                                        @if(isset($cashWallet->balance))
                                                        {{ number_format($cashWallet->balance, 2) }}
                                                        @else
                                                        0.00
                                                        @endif
                                        </a>
                                    </h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                    <span class="avatar-title">
                                        <i class="fas fa-money-bill-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <div class="media mt-3">
                                <div class="media-body">
                                    <p class="text-muted fw-medium">รายได้ประจำวัน (Cash)</p>
                                    <h4 class="mb-0">฿
                                        @if(isset($dataRevenue['revenue']))
                                            {{number_format($dataRevenue['revenue'], 2)}}
                                        @else
                                            0.00
                                        @endif
                                    </h4>
                                </div>
                                <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="fas fa-chart-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coin section -->
            <div class="col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="row">
                        <div class="media mb-3">
                            <div class="media-body">
                                <p class="text-muted fw-medium">Coin Wallet</p>
                                <h4 class="mb-0" >
                                    <a href="{{route('wallet.coin-wallet.index')}}">฿
                                    @if(isset($coinWallet->balance))
                                    {{ number_format($coinWallet->balance, 2) }}
                                    @else
                                        0.00
                                    @endif
                                    </a>
                                </h4>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-coins font-size-24"></i>
                                </span>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <div class="media mt-3">
                            <div class="media-body">
                                    <p class="text-muted fw-medium">รายได้ประจำวัน (Coin)</p>
                                    <h4 class="mb-0">฿
                                        {{number_format($dataCoinRevenue['revenue'], 2) ?? 0.00}}
                                    </h4>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                        <i class="fas fa-chart-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mt-0 mb-3 text-center">รหัสสมาชิก</h4>
                        <div class="card-text text-center">
                            {{$userData->id}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mt-0 mb-3 text-center">แพ็คเกจ</h4>
                        <div class="card-text text-center">
                            {{$userData->product->name}}
                        </div>
                    </div>
                </div>
            </div>


        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title mt-0 mb-3 text-center"></h4>
                <div class="card-text">
                    @if(isset($newsData->body))
                        {!!$newsData->body!!}
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')

@endsection
