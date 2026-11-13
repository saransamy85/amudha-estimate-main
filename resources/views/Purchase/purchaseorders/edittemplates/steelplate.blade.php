<div class="table-responsive">

    <table class="table table-bordered align-middle" id="steelTable">

        <thead class="table-dark">

            <tr>

                <th>Material</th>
                <th>Size</th>
                <th>Thickness</th>
                <th>Nos</th>
                <th>Approx Weight</th>
                <th>Rate/Kg</th>
                <th>Cutting</th>
                <th>Amount</th>
                <th width="70">

                    <button type="button" class="btn btn-success btn-sm" id="addSteelRow">

                        <i class="bi bi-plus-lg"></i>

                    </button>

                </th>

            </tr>

        </thead>

        <tbody id="steelBody">

            @foreach ($po->items as $item)
                <tr>

                    <td>

                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">

                        <input type="text" name="material[]" class="form-control" value="{{ $item->material }}">

                    </td>

                    <td>

                        <input type="text" name="size[]" class="form-control" value="{{ $item->size }}">

                    </td>

                    <td>

                        <input type="text" name="thickness[]" class="form-control" value="{{ $item->thickness }}">

                    </td>

                    <td>

                        <input type="number" name="nos[]" class="form-control nos" value="{{ $item->nos }}">

                    </td>

                    <td>

                        <input type="number" name="approx_weight[]" class="form-control weight"
                            value="{{ $item->approx_weight }}">

                    </td>

                    <td>

                        <input type="number" name="rate[]" class="form-control rate" value="{{ $item->rate }}">

                    </td>

                    <td>

                        <input type="number" name="cutting_charge[]" class="form-control cutting"
                            value="{{ $item->cutting_charge ?? 0 }}">

                    </td>

                    <td>

                        <input type="number" name="amount[]" class="form-control amount" value="{{ $item->amount }}"
                            readonly>

                    </td>

                    <td>

                        <button type="button" class="btn btn-danger btn-sm removeSteelRow">

                            <i class="bi bi-trash"></i>

                        </button>

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>
@push('scripts')
    <script src="{{ asset('js/edit-steelplate.js') }}"></script>
@endpush
