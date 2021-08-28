
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h5>History</h5>
                    <div class="row">

                        <table id="simple_table" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">วันที่ทำรายการ</th>
                                    <th scope="col">รายละเอียด</th>
                                    <th scope="col">ประเภท</th>
                                    <th scope="col">จำนวน</th>
                                    <th scope="col">คงเหลือ</th>
                                </tr>

                            </thead>
                            <tbody>
                                {{-- @dd($data) --}}
                                @foreach ($data as $row)
                                <tr class="text-center">
                                    <td>{{ date_format(date_create($row['transaction_timestamp']), "d-m-Y H:i:s") }}</td>
                                    <td>{{ $row['detail'] }} </td>
                                    @if ($row['type'] == 'DEPOSIT')
                                        <td class="text-success">{{ $row['type']}}</td>
                                        <td> + {{ number_format($row['amount'], 2) }}</td>
                                    @else
                                        <td class="text-danger">{{ $row['type'] }}</td>
                                        <td> - {{ number_format($row['amount'], 2) }}</td>
                                    @endif
                                    {{-- <td>{{ number_format($row['amount'], 2) }}</td> --}}
                                    <td>{{ number_format($row['balance'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            <!-- end card -->
        </div>



