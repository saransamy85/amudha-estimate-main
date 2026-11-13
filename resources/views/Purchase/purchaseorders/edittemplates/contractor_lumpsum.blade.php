<div class="table-responsive">
    <table class="table table-bordered align-middle" id="lumpsumContractorTable">
        <thead class="table-dark">
            <tr>
                <th width="65%">Scope of Work</th>
                <th width="25%">Lumpsum Amount</th>
                <th width="10%">
                    <button type="button" class="btn btn-success btn-sm" id="addLumpsumRow">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="lumpsumBody">
            @if($po->contractorItems && count($po->contractorItems) > 0)
                @foreach ($po->contractorItems as $item)
                    <tr>
                        <td>
                            <input type="hidden" name="lumpsum_item_id[]" value="{{ $item->id }}">
                            <textarea name="lumpsum_scope[]" class="form-control" rows="2" placeholder="Scope of Work">{{ $item->scope_of_work }}</textarea>
                        </td>
                        <td>
                            <input type="number" name="lumpsum_amount[]" class="form-control lumpsumAmount" value="{{ $item->amount }}" min="0" step="0.01">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeLumpsumRow">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <input type="hidden" name="lumpsum_item_id[]" value="">
                        <textarea name="lumpsum_scope[]" class="form-control" rows="2" placeholder="Scope of Work"></textarea>
                    </td>
                    <td>
                        <input type="number" name="lumpsum_amount[]" class="form-control lumpsumAmount" min="0" step="0.01">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeLumpsumRow">
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
    $(document).off('click.lumpsum', '#addLumpsumRow').on('click.lumpsum', '#addLumpsumRow', function() {
        let row = `
        <tr>
            <td>
                <input type="hidden" name="lumpsum_item_id[]" value="">
                <textarea name="lumpsum_scope[]" class="form-control" rows="2" placeholder="Scope of Work"></textarea>
            </td>
            <td>
                <input type="number" name="lumpsum_amount[]" class="form-control lumpsumAmount" min="0" step="0.01">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeLumpsumRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
        $('#lumpsumBody').append(row);
    });

    $(document).off('click.lumpsumRem', '.removeLumpsumRow').on('click.lumpsumRem', '.removeLumpsumRow', function() {
        if ($('#lumpsumBody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            $(this).closest('tr').find('input, textarea').val('');
        }
        calculateLumpsumContractor();
    });

    $(document).off('keyup.lumpsum change.lumpsum', '.lumpsumAmount, #gst_percent').on('keyup.lumpsum change.lumpsum', '.lumpsumAmount, #gst_percent', function() {
        calculateLumpsumContractor();
    });

    function calculateLumpsumContractor() {
        let subtotal = 0;
        $('#lumpsumBody tr').each(function() {
            let amount = parseFloat($(this).find('.lumpsumAmount').val()) || 0;
            subtotal += amount;
        });
        
        $('#subtotal').val(subtotal.toFixed(2));
        let gst = parseFloat($('#gst_percent').val()) || 0;
        let gstAmount = subtotal * gst / 100;
        $('#gst_amount').val(gstAmount.toFixed(2));
        $('#grand_total').val((subtotal + gstAmount).toFixed(2));
    }
    
    // Initial calculation
    calculateLumpsumContractor();
</script>
@endpush
