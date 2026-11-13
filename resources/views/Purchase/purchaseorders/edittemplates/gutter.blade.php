<div class="table-responsive">
    <table class="table table-bordered align-middle" id="gutterTable">
        <thead class="table-dark">
            <tr>
                <th width="25%">Particular</th>
                <th width="15%">Length</th>
                <th width="12%">Nos</th>
                <th width="18%">Rate / No</th>
                <th width="20%">Amount</th>
                <th width="10%">
                    <button type="button" class="btn btn-success btn-sm" id="addGutterRow">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="gutterBody">
            @if ($po->items && count($po->items) > 0)
                @foreach ($po->items as $item)
                    <tr>
                        <td>
                            <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                            <input type="text" name="material[]" class="form-control" value="{{ $item->material }}" placeholder="Particular">
                        </td>
                        <td>
                            <input type="text" name="length[]" class="form-control" value="{{ $item->length }}" placeholder="3 Mtr">
                        </td>
                        <td>
                            <input type="number" name="nos[]" class="form-control nos" value="{{ $item->nos }}">
                        </td>
                        <td>
                            <input type="number" name="rate[]" class="form-control rate" value="{{ $item->rate }}" step="0.01">
                        </td>
                        <td>
                            <input type="number" name="amount[]" class="form-control amount" value="{{ $item->amount }}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeGutterRow">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <input type="hidden" name="item_id[]" value="">
                        <input type="text" name="material[]" class="form-control" placeholder="Particular">
                    </td>
                    <td>
                        <input type="text" name="length[]" class="form-control" placeholder="3 Mtr">
                    </td>
                    <td>
                        <input type="number" name="nos[]" class="form-control nos">
                    </td>
                    <td>
                        <input type="number" name="rate[]" class="form-control rate" step="0.01">
                    </td>
                    <td>
                        <input type="number" name="amount[]" class="form-control amount" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeGutterRow">
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
        $(document).off('click.gutterAdd', '#addGutterRow').on('click.gutterAdd', '#addGutterRow', function () {
            let row = `
            <tr>
                <td>
                    <input type="hidden" name="item_id[]" value="">
                    <input type="text" name="material[]" class="form-control" placeholder="Particular">
                </td>
                <td>
                    <input type="text" name="length[]" class="form-control" placeholder="3 Mtr">
                </td>
                <td>
                    <input type="number" name="nos[]" class="form-control nos">
                </td>
                <td>
                    <input type="number" name="rate[]" class="form-control rate" step="0.01">
                </td>
                <td>
                    <input type="number" name="amount[]" class="form-control amount" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeGutterRow">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
            $('#gutterBody').append(row);
        });

        $(document).off('click.gutterRem', '.removeGutterRow').on('click.gutterRem', '.removeGutterRow', function () {
            if ($('#gutterBody tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                $(this).closest('tr').find('input').val('');
                $(this).closest('tr').find('.amount').val('0.00');
            }
            calculateGutterTotal();
        });

        $(document).off('keyup.gutter change.gutter', '.nos, .rate, #gst_percent').on('keyup.gutter change.gutter', '.nos, .rate, #gst_percent', function () {
            let row = $(this).closest('tr');
            if (row.length) {
                let nos = parseFloat(row.find('.nos').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;
                row.find('.amount').val((nos * rate).toFixed(2));
            }
            calculateGutterTotal();
        });

        function calculateGutterTotal() {
            let subtotal = 0;
            $('#gutterBody tr').each(function () {
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

        calculateGutterTotal();
    });
</script>
@endpush

