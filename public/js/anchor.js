$(document).ready(function () {

    bindAnchorEvents();

});


function bindAnchorEvents() {

    // Remove previous bindings
    $(document).off('click', '#addRow');
    $(document).off('click', '.removeRow');
    $(document).off(
        'keyup change',
        '.qty,.rate,#gst_percent'
    );


    // =====================================
    // ADD ROW
    // =====================================

    $(document).on('click', '#addRow', function () {

        let row = $('#tbody tr:first').clone(false);

        row.find('input').each(function () {

            if ($(this).hasClass('amount')) {

                $(this).val('0.00');

            } else {

                $(this).val('');

            }

        });

        $('#tbody').append(row);

    });


    // =====================================
    // REMOVE ROW
    // =====================================

    $(document).on('click', '.removeRow', function () {

        if ($('#tbody tr').length > 1) {

            $(this).closest('tr').remove();

        } else {

            $(this)
                .closest('tr')
                .find('input')
                .val('');

        }

        calculateAnchor();

    });


    // =====================================
    // CALCULATE
    // =====================================

    $(document).on(
        'keyup change',
        '.qty,.rate,#gst_percent',
        function () {

            calculateAnchor();

        }
    );


    calculateAnchor();

}


// =====================================
// ANCHOR CALCULATION
// =====================================

function calculateAnchor() {

    let subtotal = 0;


    $('#tbody tr').each(function () {

        let qty = parseFloat(
            $(this).find('.qty').val()
        ) || 0;


        let rate = parseFloat(
            $(this).find('.rate').val()
        ) || 0;


        let amount = qty * rate;


        $(this)
            .find('.amount')
            .val(amount.toFixed(2));


        subtotal += amount;

    });


    // Subtotal
    $('#subtotal').val(
        subtotal.toFixed(2)
    );


    // GST
    let gst = parseFloat(
        $('#gst_percent').val()
    ) || 0;


    let gstAmount =
        subtotal * gst / 100;


    $('#gst_amount').val(
        gstAmount.toFixed(2)
    );


    // Grand Total
    $('#grand_total').val(
        (subtotal + gstAmount).toFixed(2)
    );

}