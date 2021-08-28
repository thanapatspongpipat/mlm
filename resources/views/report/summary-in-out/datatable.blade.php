       <div class="row">
                 <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <p class="text-muted fw-medium">Summary Balance </p>

                            <h4 class="mb-0">฿ <span id="balance"> {{ number_format($header['sumBalance'], 2) }}</span></h4>
                        </div>
                        <i class="bx bx-wallet text-primary display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- <a href="{{ route('deposit.index') }}"> --}}
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <p class="text-muted fw-medium">Summary Deposit </p>
                                <h4 class="mb-0">฿ <span id="deposit">  {{ number_format($header['sumDeposit'], 2) }} </span></h4>
                            </div>
                            <i class="bx bx-down-arrow-alt text-success display-4"></i>
                        </div>
                    </div>
                </div>
            {{-- </a> --}}
        </div>

        <div class="col-md-4">
            {{-- <a href="{{ route('withdraw.index') }}"> --}}
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <p class="text-muted fw-medium rt">Summary Withdraw </p>
                                <h4 class="mb-0">฿ <span id="withdraw">  {{ number_format($header['sumWithdraw'], 2) }} </span></h4>
                            </div>
                            <i class="bx bx-up-arrow-alt text-danger display-4"></i>
                        </div>
                    </div>
                </div>
            {{-- </a> --}}
        </div>

       </div>



        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <table id="simple_table" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th>ลำดับ</th>
                                    <th scope="col">username</th>
                                    <th scope="col">ยอดคงเหลือ</th>
                                    <th scope="col">ยอดถอน</th>
                                    <th scope="col">ยอดเติม</th>
                                </tr>

                            </thead>
                            <tbody>
                                {{-- @dd($data) --}}
                                @foreach ($data as $row)
                                <tr class="text-center">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $row['user']['username'] }}</td>
                                    <td>{{ number_format($row['balance'], 2) }}</td>
                                    <td class="text-danger">{{ number_format($row['withdraw'], 2) }}</td>
                                    <td class="text-success">{{ number_format($row['deposit'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            <!-- end card -->
        </div>



