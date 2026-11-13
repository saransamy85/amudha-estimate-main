<div class="table-responsive">

    <table class="table table-bordered align-middle" id="panelTable">

        <thead class="table-dark">

            <tr>

                <th>Material</th>

                <th>Width</th>

                <th>Length</th>

                <th>Thickness</th>

                <th>Color</th>

                <th>Nos</th>

                <th>Qty (Rft / Sq.ft / Sq.Mtr)</th>

                <th>Unit</th>

                <th>Rate</th>

                <th>Amount</th>

                <th>

                    <button type="button" class="btn btn-success btn-sm" id="addPanelRow">

                        <i class="bi bi-plus-lg"></i>

                    </button>

                </th>

            </tr>

        </thead>

        <tbody id="panelBody">

            @foreach ($po->items as $item)
                <tr>

                    <td>

                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">

                        <input type="text" name="material[]" class="form-control" value="{{ $item->material }}">

                    </td>

                    <td>

                        <input type="number" step="any" min="0" name="width[]" class="form-control width" value="{{ $item->width }}" placeholder="Width">

                    </td>

                    <td>

                        <input type="number" step="any" min="0" name="length[]" class="form-control length" value="{{ $item->length }}" placeholder="Length">

                    </td>

                    <td>

                        <input type="text" name="thickness[]" class="form-control" value="{{ $item->thickness }}" placeholder="50 mm">

                    </td>

                    <td>

                        <input type="text" name="color[]" class="form-control" value="{{ $item->color }}" placeholder="Blue">

                    </td>

                    <td>

                        <input type="number" min="0" name="nos[]" class="form-control nos" value="{{ $item->nos }}" placeholder="Nos">

                    </td>

                    <td>

                        <input type="number" step="any" name="qty[]" class="form-control qty" value="{{ $item->qty }}" readonly>

                    </td>

                    <td>

                        <select name="unit[]" class="form-select">

                            <option value="Rft" {{ $item->unit == 'Rft' ? 'selected' : '' }}>Running Feet</option>

                            <option value="Sq.ft" {{ $item->unit == 'Sq.ft' ? 'selected' : '' }}>Sq.ft</option>

                            <option value="Nos" {{ $item->unit == 'Nos' ? 'selected' : '' }}>Nos</option>

                            <option value="Mtr" {{ $item->unit == 'Mtr' ? 'selected' : '' }}>Meter</option>

                            <option value="Sq.Mtr" {{ $item->unit == 'Sq.Mtr' ? 'selected' : '' }}>Sq.Mtr</option>

                        </select>

                    </td>

                    <td>

                        <input type="number" name="rate[]" class="form-control rate" value="{{ $item->rate }}" step="0.01">

                    </td>

                    <td>

                        <input type="number" name="amount[]" class="form-control amount" value="{{ $item->amount }}"
                            readonly>

                    </td>

                    <td>

                        <button type="button" class="btn btn-danger btn-sm removePanelRow">

                            <i class="bi bi-trash"></i>

                        </button>

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>

@push('scripts')
    <script src="{{ asset('js/sandwichpanel.js') }}"></script>
@endpush
