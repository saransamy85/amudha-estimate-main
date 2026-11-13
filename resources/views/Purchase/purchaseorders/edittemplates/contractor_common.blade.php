<div class="table-responsive">
    <table class="table table-bordered align-middle" id="commonContractorTable">
        <thead class="table-dark">
            <tr>
                <th width="40%">Scope of Work</th>
                <th width="15%">Quantity</th>
                <th width="10%">Unit</th>
                <th width="15%">Rate</th>
                <th width="15%">Amount</th>
                <th width="5%">
                    <button type="button" class="btn btn-success btn-sm" id="addCommonRow">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="commonContractorBody">
            @if($po->contractorItems && count($po->contractorItems) > 0)
                @foreach ($po->contractorItems as $item)
                    <tr>
                        <td>
                            <input type="hidden" name="contractor_item_id[]" value="{{ $item->id }}">
                            <input type="text" name="scope_of_work[]" class="form-control" value="{{ $item->scope_of_work }}">
                        </td>
                        <td>
                            <input type="number" name="contractor_qty[]" class="form-control contractorQty" value="{{ $item->qty }}" min="0" step="0.01">
                        </td>
                        <td>
                            <select name="contractor_unit[]" class="form-select">
                                <option value="Kg" {{ $item->unit == 'Kg' ? 'selected' : '' }}>Kg</option>
                                <option value="Nos" {{ $item->unit == 'Nos' ? 'selected' : '' }}>Nos</option>
                                <option value="Sq.ft" {{ $item->unit == 'Sq.ft' ? 'selected' : '' }}>Sq.ft</option>
                                <option value="Sq.m" {{ $item->unit == 'Sq.m' ? 'selected' : '' }}>Sq.m</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="contractor_rate[]" class="form-control contractorRate" value="{{ $item->rate }}" min="0" step="0.01">
                        </td>
                        <td>
                            <input type="number" name="contractor_amount[]" class="form-control contractorAmount" value="{{ $item->amount }}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeContractorRow">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <input type="hidden" name="contractor_item_id[]" value="">
                        <input type="text" name="scope_of_work[]" class="form-control" placeholder="Scope of Work">
                    </td>
                    <td>
                        <input type="number" name="contractor_qty[]" class="form-control contractorQty" min="0" step="0.01">
                    </td>
                    <td>
                        <select name="contractor_unit[]" class="form-select">
                            <option value="Kg">Kg</option>
                            <option value="Nos">Nos</option>
                            <option value="Sq.ft">Sq.ft</option>
                            <option value="Sq.m">Sq.m</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="contractor_rate[]" class="form-control contractorRate" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="number" name="contractor_amount[]" class="form-control contractorAmount" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeContractorRow">
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
    $(document).off('click.common', '#addCommonRow').on('click.common', '#addCommonRow', function() {
        let row = `
        <tr>
            <td>
                <input type="hidden" name="contractor_item_id[]" value="">
                <input type="text" name="scope_of_work[]" class="form-control" placeholder="Scope of Work">
            </td>
            <td>
                <input type="number" name="contractor_qty[]" class="form-control contractorQty" min="0" step="0.01">
            </td>
            <td>
                <select name="contractor_unit[]" class="form-select">
                    <option value="Kg">Kg</option>
                    <option value="Nos">Nos</option>
                    <option value="Sq.ft">Sq.ft</option>
                    <option value="Sq.m">Sq.m</option>
                </select>
            </td>
            <td>
                <input type="number" name="contractor_rate[]" class="form-control contractorRate" min="0" step="0.01">
            </td>
            <td>
                <input type="number" name="contractor_amount[]" class="form-control contractorAmount" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeContractorRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
        $('#commonContractorBody').append(row);
    });

    $(document).off('click.commonRem', '.removeContractorRow').on('click.commonRem', '.removeContractorRow', function() {
        if ($('#commonContractorBody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            $(this).closest('tr').find('input').val('');
        }
        calculateCommonContractor();
    });

    $(document).off('keyup.common change.common', '.contractorQty, .contractorRate, #gst_percent').on('keyup.common change.common', '.contractorQty, .contractorRate, #gst_percent', function() {
        calculateCommonContractor();
    });

    function calculateCommonContractor() {
        let subtotal = 0;
        $('#commonContractorBody tr').each(function() {
            let qty = parseFloat($(this).find('.contractorQty').val()) || 0;
            let rate = parseFloat($(this).find('.contractorRate').val()) || 0;
            let amount = qty * rate;
            $(this).find('.contractorAmount').val(amount.toFixed(2));
            subtotal += amount;
        });
        
        $('#subtotal').val(subtotal.toFixed(2));
        let gst = parseFloat($('#gst_percent').val()) || 0;
        let gstAmount = subtotal * gst / 100;
        $('#gst_amount').val(gstAmount.toFixed(2));
        $('#grand_total').val((subtotal + gstAmount).toFixed(2));
    }
    
    // Initial calculation
    calculateCommonContractor();
</script>
@endpush
