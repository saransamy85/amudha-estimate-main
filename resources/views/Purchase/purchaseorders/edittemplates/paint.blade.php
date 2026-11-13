<div class="table-responsive">
    <table class="table table-bordered align-middle" id="paintTable">
        <thead class="table-dark">
            <tr>
                <th width="40%">Material</th>
                <th width="15%">LTRS</th>
                <th width="15%">Price</th>
                <th width="20%">Value</th>
                <th width="10%">
                    <button type="button" class="btn btn-success btn-sm" id="addPaintRow">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="paintBody">
            @if ($po->items && count($po->items) > 0)
                @foreach ($po->items as $item)
                    <tr>
                        <td>
                            <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                            <input type="text" class="form-control" name="material[]" value="{{ $item->material }}" placeholder="Material Name">
                        </td>
                        <td>
                            <input type="number" class="form-control nos" name="nos[]" value="{{ $item->nos }}" min="0" step="0.01" placeholder="LTRS">
                        </td>
                        <td>
                            <input type="number" class="form-control rate" name="rate[]" value="{{ $item->rate }}" min="0" step="0.01" placeholder="Price">
                        </td>
                        <td>
                            <input type="number" class="form-control amount" name="amount[]" value="{{ $item->amount }}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removePaintRow">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <input type="hidden" name="item_id[]" value="">
                        <input type="text" class="form-control" name="material[]" placeholder="Material Name">
                    </td>
                    <td>
                        <input type="number" class="form-control nos" name="nos[]" min="0" step="0.01" placeholder="LTRS">
                    </td>
                    <td>
                        <input type="number" class="form-control rate" name="rate[]" min="0" step="0.01" placeholder="Price">
                    </td>
                    <td>
                        <input type="number" class="form-control amount" name="amount[]" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removePaintRow">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $(document).off('click.paintAdd', '#addPaintRow').on('click.paintAdd', '#addPaintRow', function () {
            let row = `
            <tr>
                <td>
                    <input type="hidden" name="item_id[]" value="">
                    <input type="text" class="form-control" name="material[]" placeholder="Material Name">
                </td>
                <td>
                    <input type="number" class="form-control nos" name="nos[]" min="0" step="0.01" placeholder="LTRS">
                </td>
                <td>
                    <input type="number" class="form-control rate" name="rate[]" min="0" step="0.01" placeholder="Price">
                </td>
                <td>
                    <input type="number" class="form-control amount" name="amount[]" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removePaintRow">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
            $('#paintBody').append(row);
        });

        $(document).off('click.paintRem', '.removePaintRow').on('click.paintRem', '.removePaintRow', function () {
            if ($('#paintBody tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                $(this).closest('tr').find('input').val('');
                $(this).closest('tr').find('.amount').val('0.00');
            }
            calculatePaintTotal();
        });

        $(document).off('keyup.paint change.paint', '.nos, .rate, #gst_percent').on('keyup.paint change.paint', '.nos, .rate, #gst_percent', function () {
            let row = $(this).closest('tr');
            if (row.length) {
                let nos = parseFloat(row.find('.nos').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;
                row.find('.amount').val((nos * rate).toFixed(2));
            }
            calculatePaintTotal();
        });

        function calculatePaintTotal() {
            let subtotal = 0;
            $('#paintBody tr').each(function () {
                let nos = parseFloat($(this).find('.nos').val()) || 0;
                let rate = parseFloat($(this).find('.rate').val()) || 0;
                let amount = nos * rate;
                $(this).find('.amount').val(amount.toFixed(2));
                subtotal += amount;
            });

            $('#subtotal').val(subtotal.toFixed(2));
            let gst = parseFloat($('#gst_percent').val()) || 0;
            let gstAmount = subtotal * gst / 100;
            $('#gst_amount').val(gstAmount.toFixed(2));
            $('#grand_total').val((subtotal + gstAmount).toFixed(2));
        }

        calculatePaintTotal();
    });
</script>
@endpush

