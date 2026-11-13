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

        <tr>

            <td>
                <input type="text"
                       class="form-control"
                       name="material[]"
                       placeholder="Material Name">
            </td>

            <td>
                <input type="number"
                       class="form-control nos"
                       name="nos[]"
                       min="0"
                       step="0.01">
            </td>

            <td>
                <input type="number"
                       class="form-control rate"
                       name="rate[]"
                       min="0"
                       step="0.01">
            </td>

            <td>
                <input type="number"
                       class="form-control amount"
                       name="amount[]"
                       readonly>
            </td>

            <td>
                <button type="button"
                        class="btn btn-danger btn-sm removePaintRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>

        </tr>

    </tbody>

</table>
<script>
    $(document).on('keyup change', '.nos, .rate', function () {

    let row = $(this).closest('tr');

    let nos = parseFloat(row.find('.nos').val()) || 0;
    let rate  = parseFloat(row.find('.rate').val()) || 0;

    row.find('.amount').val((nos * rate).toFixed(2));

    calculatePaintTotal();
});

function calculatePaintTotal(){

    let subtotal = 0;

    $('.amount').each(function(){

        subtotal += parseFloat($(this).val()) || 0;

    });

    $('#subtotal').val(subtotal.toFixed(2));

    let gst = parseFloat($('#gst_percent').val()) || 18;

    let gstAmount = subtotal * gst / 100;

    $('#gst_amount').val(gstAmount.toFixed(2));

    $('#grand_total').val((subtotal + gstAmount).toFixed(2));

}

$(document).on('click', '.removePaintRow', function () {

    if ($('#paintBody tr').length > 1) {
        $(this).closest('tr').remove();
    } else {
        $(this).closest('tr').find('input').val('');
    }

    calculatePaintTotal();
});

$(document).on('click','#addPaintRow',function(){

    let newRow = `
    <tr>
        <td><input type="text" class="form-control" name="material[]"></td>
        <td><input type="number" class="form-control nos" name="nos[]" min="0" step="0.01"></td>
        <td><input type="number" class="form-control rate" name="rate[]" min="0" step="0.01"></td>
        <td><input type="number" class="form-control value" name="amount[]" readonly></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm removePaintRow">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

    $('#paintBody').append(newRow);
});
</script>
