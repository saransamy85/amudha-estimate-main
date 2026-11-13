$(document).ready(function () {

    bindPaintEvents();

});

function bindPaintEvents() {

    $(document).off('click', '#addPaintRow');
    $(document).off('click', '.removePaintRow');
    $(document).off('keyup change', '.qty,.rate,#gst_percent');

    // Add Row
    $(document).on('click', '#addPaintRow', function () {

        let row = $('#paintBody tr:first').clone(false);

        row.find('input').val('');

        row.find('.amount').val('0.00');

        $('#paintBody').append(row);

    });

    // Remove Row
    $(document).on('click', '.removePaintRow', function () {

        if ($('#paintBody tr').length > 1) {

            $(this).closest('tr').remove();

            calculatePaint();

        }

    });

    // Calculate
    $(document).on('keyup change', '.qty,.rate,#gst_percent', function () {

        calculatePaint();

    });

    calculatePaint();

}

function calculatePaint() {

    let subtotal = 0;

    $('#paintBody tr').each(function () {

        let nos = parseFloat($(this).find('.nos').val()) || 0;

        let rate = parseFloat($(this).find('.rate').val()) || 0;

        let amount = nos * rate;

        $(this).find('.amount').val(amount.toFixed(2));

        subtotal += amount;

    });

    $('#subtotal').val(subtotal.toFixed(2));

    let gst = parseFloat($('#gst_percent').val()) || 18;

    let gstAmount = subtotal * gst / 100;

    $('#gst_amount').val(gstAmount.toFixed(2));

    $('#grand_total').val((subtotal + gstAmount).toFixed(2));

}