$(document).on('keyup change', '.area, .nos, .rate', function () {

    let row = $(this).closest('tr');

    let area = parseFloat(row.find('.area').val()) || 0;
    let nos = parseFloat(row.find('.nos').val()) || 0;
    let rate  = parseFloat(row.find('.rate').val()) || 0;

    row.find('.amount').val((area * nos * rate).toFixed(2));

    calculateRetractableawningsTotal();
});

function calculateRetractableawningsTotal(){

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

$(document).on('click', '.removeRetractableawningsRow', function () {

    if ($('#retractableawningsBody tr').length > 1) {
        $(this).closest('tr').remove();
    } else {
        $(this).closest('tr').find('input').val('');
        $(this).closest('tr').find('select[name="unit[]"]').val('Sqft');
    }

    calculateRetractableawningsTotal();
});

$(document).on('click','#addRetractableawningsRow',function(){

    let newRow = `
    <tr>
        <td><input type="text" class="form-control" name="material[]" placeholder="Material Name"></td>
        <td><input type="number" class="form-control size" name="size[]" min="0" step="0.01"></td>
        <td><input type="number" class="form-control area" name="qty[]" min="0" step="0.01"></td>
        <td>
            <select name="unit[]" class="form-select">
                <option value="Sqft">Sq.ft</option>
                <option value="Rft">Running Feet</option>
                <option value="Nos">Nos</option>
                <option value="Mtr">Meter</option>
            </select>
        </td>
        <td><input type="number" class="form-control nos" name="nos[]" min="0" step="0.01"></td>
        <td><input type="number" class="form-control rate" name="rate[]" min="0" step="0.01"></td>
        <td><input type="number" class="form-control amount" name="amount[]" readonly></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm removeRetractableawningsRow">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

    $('#retractableawningsBody').append(newRow);
});
