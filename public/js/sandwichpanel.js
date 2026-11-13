function initSandwichPanel() {

    // =====================================
    // ADD ROW
    // =====================================

    $(document)
        .off('click.sandwichpanel', '#addPanelRow')
        .on('click.sandwichpanel', '#addPanelRow', function () {

            let row = $('#panelTable tbody tr:first').clone();

            row.find('input').each(function () {
                if ($(this).attr('name') === 'item_id[]') {
                    $(this).val('');
                } else if ($(this).hasClass('nos')) {
                    $(this).val(1);
                } else if ($(this).hasClass('qty') || $(this).hasClass('amount')) {
                    $(this).val('0.00');
                } else {
                    $(this).val('');
                }
            });

            row.find('select').prop('selectedIndex', 0);

            $('#panelTable tbody').append(row);

        });


    // =====================================
    // REMOVE ROW
    // =====================================

    $(document)
        .off('click.sandwichpanel', '.removePanelRow')
        .on('click.sandwichpanel', '.removePanelRow', function () {

            if ($('#panelTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calculatePanelTotal();
            }

        });


    // =====================================
    // LENGTH / WIDTH / NOS CALCULATION (QTY = LENGTH * WIDTH * NOS)
    // =====================================

    $(document)
        .off(
            'input.sandwichpanel change.sandwichpanel',
            '#panelTable .length, #panelTable .width, #panelTable .nos'
        )
        .on(
            'input.sandwichpanel change.sandwichpanel',
            '#panelTable .length, #panelTable .width, #panelTable .nos',
            function () {

                let row = $(this).closest('tr');

                let length = parseFloat(row.find('.length').val()) || 0;
                let width = parseFloat(row.find('.width').val()) || 0;
                let nos = parseFloat(row.find('.nos').val()) || 0;

                let qty = length * width * nos;
                row.find('.qty').val(qty.toFixed(2));

                let rate = parseFloat(row.find('.rate').val()) || 0;
                let amount = qty * rate;

                row.find('.amount').val(amount.toFixed(2));

                calculatePanelTotal();

            });


    // =====================================
    // QTY / RATE CALCULATION
    // =====================================

    $(document)
        .off(
            'input.sandwichpanel change.sandwichpanel',
            '#panelTable .qty, #panelTable .rate'
        )
        .on(
            'input.sandwichpanel change.sandwichpanel',
            '#panelTable .qty, #panelTable .rate',
            function () {

                let row = $(this).closest('tr');

                let qty = parseFloat(row.find('.qty').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;

                let amount = qty * rate;

                row.find('.amount').val(amount.toFixed(2));

                calculatePanelTotal();

            });


    // =====================================
    // GST
    // =====================================

    $(document)
        .off('input.sandwichpanel change.sandwichpanel', '#gst_percent, input[name="gst_percent"]')
        .on(
            'input.sandwichpanel change.sandwichpanel',
            '#gst_percent, input[name="gst_percent"]',
            function () {

                calculatePanelTotal();

            });


    // Initial calculation
    calculatePanelTotal();

}


// =====================================
// PANEL TOTAL
// =====================================

function calculatePanelTotal() {

    let subtotal = 0;

    // Calculate subtotal from amounts
    $('#panelTable .amount').each(function () {

        subtotal += parseFloat($(this).val()) || 0;

    });


    // =================================
    // SUBTOTAL
    // =================================

    $('#subtotal').val(
        subtotal.toFixed(2)
    );


    // =================================
    // GST
    // =================================

    let gst =
        parseFloat(
            $('#gst_percent').val() || $('input[name="gst_percent"]').val()
        ) || 0;


    let gstAmount =
        subtotal * gst / 100;


    $('#gst_amount').val(
        gstAmount.toFixed(2)
    );


    // =================================
    // GRAND TOTAL
    // =================================

    let grandTotal =
        subtotal + gstAmount;


    $('#grand_total').val(
        grandTotal.toFixed(2)
    );

}


// =====================================
// INITIALIZE
// =====================================

initSandwichPanel();