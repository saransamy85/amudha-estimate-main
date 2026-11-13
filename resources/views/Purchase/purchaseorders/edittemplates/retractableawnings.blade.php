<div class="table-responsive">

    <table class="table table-bordered align-middle" id="retractableawningsTable">

        <thead class="table-dark">

            <tr>

                <th width="20%">Material</th>

                <th>Size</th>

                <th>Area</th>

                <th>Unit</th>

                <th>Nos</th>

                <th>Rate</th>

                <th>Amount</th>

                <th width="70">

                    <button type="button" class="btn btn-success btn-sm" id="addRetractableawningsRow">

                        <i class="bi bi-plus-lg"></i>

                    </button>

                </th>

            </tr>

        </thead>

        <tbody>

            @foreach ($po->items as $item)
            <tr>

                <td>

                    <input type="hidden" name="item_id[]" value="{{ $item->id }}">

                    <input type="text" name="material[]" class="form-control" value="{{ $item->material }}">

                </td>

                <td>

                    <input type="text" name="size[]" class="form-control size" value="{{ $item->size }}">

                </td>

                <td>

                    <input type="number" name="qty[]" class="form-control area" value="{{ $item->qty }}" step="0.01">

                </td>

                <td>

                    <select name="unit[]" class="form-select">
                        <option value="Sqft" {{ $item->unit == 'Sqft' || $item->unit == 'Sq.ft' ? 'selected' : '' }}>Sq.ft</option>
                        <option value="Rft" {{ $item->unit == 'Rft' ? 'selected' : '' }}>Running Feet</option>
                        <option value="Nos" {{ $item->unit == 'Nos' ? 'selected' : '' }}>Nos</option>
                        <option value="Mtr" {{ $item->unit == 'Mtr' ? 'selected' : '' }}>Meter</option>
                    </select>

                </td>

                <td>

                    <input type="number" name="nos[]" class="form-control nos" value="{{ $item->nos }}" step="0.01">

                </td>

                <td>

                    <input type="number" name="rate[]" class="form-control rate" value="{{ $item->rate }}" step="0.01">

                </td>

                <td>

                    <input type="number" name="amount[]" class="form-control amount" value="{{ $item->amount }}" readonly>

                </td>

                <td>

                    <button type="button" class="btn btn-danger btn-sm removeRow">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>
            @endforeach

        </tbody>

    </table>

</div>

@push('scripts')
<script>
    $(document).on('click', '#addRetractableawningsRow', function() {

        let row = `
    <tr>

        <td>

            <input type="hidden" name="item_id[]" value="">

            <input type="text"
                   name="material[]"
                   class="form-control"
                   placeholder="Material Name">

        </td>

        <td>

            <input type="text"
                   name="size[]"
                   class="form-control size">

        </td>

        <td>

            <input type="number"
                   name="qty[]"
                   class="form-control area"
                   step="0.01">

        </td>

        <td>

            <select name="unit[]" class="form-select">
                <option value="Sqft">Sq.ft</option>
                <option value="Rft">Running Feet</option>
                <option value="Nos">Nos</option>
                <option value="Mtr">Meter</option>
            </select>

        </td>

        <td>

            <input type="number"
                   name="nos[]"
                   class="form-control nos"
                   step="0.01">

        </td>

        <td>

            <input type="number"
                   name="rate[]"
                   class="form-control rate"
                   step="0.01">

        </td>

        <td>

            <input type="number"
                   name="amount[]"
                   class="form-control amount"
                   readonly>

        </td>

        <td>

            <button type="button"
                class="btn btn-danger btn-sm removeRow">

                <i class="bi bi-trash"></i>

            </button>

        </td>

    </tr>`;

        $('#retractableawningsTable tbody').append(row);

    });

    $(document).on('click', '.removeRow', function() {

        if ($('#retractableawningsTable tbody tr').length > 1) {

            $(this).closest('tr').remove();

            calculateRetractableawnings();

        }

    });

    $(document).on('keyup change', '.area,.nos,.rate', function() {

        calculateRetractableawnings();

    });

    function calculateRetractableawnings() {

        let subtotal = 0;

        $('#retractableawningsTable tbody tr').each(function() {

            let area = parseFloat($(this).find('.area').val()) || 0;

            let nos = parseFloat($(this).find('.nos').val()) || 0;

            let rate = parseFloat($(this).find('.rate').val()) || 0;

            let amount = area * nos * rate;

            $(this).find('.amount').val(amount.toFixed(2));

            subtotal += amount;

        });

        $('#subtotal').val(subtotal.toFixed(2));

        let gst = parseFloat($('input[name="gst_percent"]').val()) || 0;

        let gstAmount = subtotal * gst / 100;

        $('#gst_amount').val(gstAmount.toFixed(2));

        $('#grand_total').val((subtotal + gstAmount).toFixed(2));

    }

    calculateRetractableawnings();

</script>
@endpush
