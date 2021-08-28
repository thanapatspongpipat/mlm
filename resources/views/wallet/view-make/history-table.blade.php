
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h5>History</h5>
                    <div class="row">

                        <table id="simple_table" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">no</th>
                                    <th scope="col">From</th>
                                    <th scope="col">To</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">timestamp</th>

                                </tr>

                            </thead>
                            <tbody>
                                {{-- @dd($data) --}}
                                @foreach ($data as $row)
                                <tr class="text-center">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $row['fromUser']['username'] }}</td>
                                    <td>{{ $row['toUser']['username']}}</td>
                                    <td>{{ number_format($row['amount'], 2) }}</td>
                                    @if ($row['type'] == 'deposit')
                                        <td class="text-success">{{ $row['type']}}</td>
                                    @else
                                        <td class="text-danger">{{ $row['type'] }}</td>
                                    @endif
                                    <td>{{ date_format(date_create($row['transaction_timestamp']), "d/m/Y H:i:s") }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            <!-- end card -->
        </div>



