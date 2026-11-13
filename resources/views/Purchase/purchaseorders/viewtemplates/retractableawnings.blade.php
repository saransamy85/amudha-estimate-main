<div class="table-responsive">

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

            <tr>

                <th width="5%">#</th>

                <th>Material</th>

                <th>Size</th>

                <th>Area</th>

                <th>Unit</th>

                <th>Nos</th>

                <th>Rate</th>

                <th>Total</th>

            </tr>

        </thead>

        <tbody>

            @forelse($po->items as $item)
                <tr>

                    <td>

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $item->material }}

                    </td>

                    <td>

                        {{ $item->size ?? '-' }}

                    </td>

                    <td>

                        {{ $item->qty ?? '-' }}

                    </td>

                    <td>

                        {{ $item->unit ?? '-' }}

                    </td>

                    <td>

                        {{ $item->nos ?? '-' }}

                    </td>

                    <td>

                        ₹ {{ number_format((float) $item->rate, 2) }}

                    </td>

                    <td>

                        ₹ {{ number_format((float) $item->amount, 2) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center">

                        No Items Found

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

</div>
