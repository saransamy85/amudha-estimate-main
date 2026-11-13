<div class="table-responsive">
    <table class="table table-bordered align-middle" id="polycarbonateTable">
        <thead class="table-primary text-center">
            <tr>
                <th width="5%">S.No</th>
                <th width="25%">Particular</th>
                <th width="10%">Length (m)</th>
                <th width="10%">Width (m)</th>
                <th width="8%">Nos</th>
                <th width="12%">Area (Sqm)</th>
                <th width="10%">Rate</th>
                <th width="12%">Amount</th>
                <th width="8%">Action</th>
            </tr>
        </thead>
        <tbody id="polycarbonateBody">
            @forelse ($po->items as $item)
                <tr>
                    <td class="text-center serial">{{ $loop->iteration }}</td>
                    <td>
                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                        <input type="text" name="material[]" class="form-control" value="{{ $item->material }}" placeholder="Particular">
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="length[]" class="form-control length" value="{{ $item->length }}">
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="width[]" class="form-control width" value="{{ $item->width }}">
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="nos[]" class="form-control nos" value="{{ $item->nos ?? 1 }}">
                    </td>
                    <td>
                        <input type="number" step="any" name="area[]" class="form-control area" value="{{ $item->area ?? $item->qty }}" readonly>
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="rate[]" class="form-control rate" value="{{ $item->rate }}">
                    </td>
                    <td>
                        <input type="number" step="any" name="amount[]" class="form-control amount" value="{{ $item->amount }}" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success btn-sm addPolyRow">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm removePolyRow">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center serial">1</td>
                    <td>
                        <input type="hidden" name="item_id[]" value="">
                        <input type="text" name="material[]" class="form-control" placeholder="Particular">
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="length[]" class="form-control length">
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="width[]" class="form-control width">
                    </td>
                    <td>
                        <input type="number" step="any" min="0" value="1" name="nos[]" class="form-control nos">
                    </td>
                    <td>
                        <input type="number" step="any" name="area[]" class="form-control area" readonly>
                    </td>
                    <td>
                        <input type="number" step="any" min="0" name="rate[]" class="form-control rate">
                    </td>
                    <td>
                        <input type="number" step="any" name="amount[]" class="form-control amount" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success btn-sm addPolyRow">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm removePolyRow">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        function updatePolySerialNo() {
            $('#polycarbonateBody tr').each(function (index) {
                $(this).find('.serial').text(index + 1);
            });
        }

        function calculatePolycarbonate(recalcRow = false) {
            let subtotal = 0;
            $('#polycarbonateBody tr').each(function () {
                let row = $(this);
                let length = parseFloat(row.find('.length').val()) || 0;
                let width = parseFloat(row.find('.width').val()) || 0;
                let nos = parseFloat(row.find('.nos').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;

                let areaInput = row.find('.area');
                let amountInput = row.find('.amount');

                // If dimensions exist or recalcRow is explicitly requested, compute area
                if (recalcRow || (length > 0 && width > 0 && nos > 0)) {
                    if (length > 0 && width > 0 && nos > 0) {
                        let area = length * width * nos;
                        areaInput.val(area.toFixed(3));
                    }
                }

                let currentArea = parseFloat(areaInput.val()) || 0;
                if (recalcRow || (currentArea > 0 && rate > 0)) {
                    if (currentArea > 0 && rate > 0) {
                        amountInput.val((currentArea * rate).toFixed(2));
                    }
                }

                let rowAmount = parseFloat(amountInput.val()) || 0;
                subtotal += rowAmount;
            });

            // Update subtotal if items exist
            if (subtotal > 0 || recalcRow) {
                $('#subtotal').val(subtotal.toFixed(2));
                let gstPercent = parseFloat($('#gst_percent').val()) || 0;
                let gstAmount = subtotal * gstPercent / 100;
                $('#gst_amount').val(gstAmount.toFixed(2));
                $('#grand_total').val((subtotal + gstAmount).toFixed(2));
            }
        }

        // Add Row
        $(document).off('click.polyAdd', '.addPolyRow').on('click.polyAdd', '.addPolyRow', function () {
            let row = `
            <tr>
                <td class="text-center serial"></td>
                <td>
                    <input type="hidden" name="item_id[]" value="">
                    <input type="text" name="material[]" class="form-control" placeholder="Particular">
                </td>
                <td>
                    <input type="number" step="any" min="0" name="length[]" class="form-control length">
                </td>
                <td>
                    <input type="number" step="any" min="0" name="width[]" class="form-control width">
                </td>
                <td>
                    <input type="number" step="any" min="0" value="1" name="nos[]" class="form-control nos">
                </td>
                <td>
                    <input type="number" step="any" name="area[]" class="form-control area" readonly>
                </td>
                <td>
                    <input type="number" step="any" min="0" name="rate[]" class="form-control rate">
                </td>
                <td>
                    <input type="number" step="any" name="amount[]" class="form-control amount" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-success btn-sm addPolyRow">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm removePolyRow">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
            $('#polycarbonateBody').append(row);
            updatePolySerialNo();
        });

        // Remove Row
        $(document).off('click.polyRem', '.removePolyRow').on('click.polyRem', '.removePolyRow', function () {
            if ($('#polycarbonateBody tr').length > 1) {
                $(this).closest('tr').remove();
                updatePolySerialNo();
                calculatePolycarbonate(true);
            } else {
                $(this).closest('tr').find('input').val('');
                $(this).closest('tr').find('.nos').val(1);
                $(this).closest('tr').find('.area').val('');
                $(this).closest('tr').find('.amount').val('');
                calculatePolycarbonate(true);
            }
        });

        // Auto Calculation when inputs change
        $(document).off('keyup.poly change.poly', '.length, .width, .nos, .rate, #gst_percent')
            .on('keyup.poly change.poly', '.length, .width, .nos, .rate, #gst_percent', function () {
                calculatePolycarbonate(true);
            });

        updatePolySerialNo();
        calculatePolycarbonate(false);
    });
</script>
@endpush
