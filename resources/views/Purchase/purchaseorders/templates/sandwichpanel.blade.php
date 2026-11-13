<div class="table-responsive">

    <table class="table table-bordered align-middle" id="panelTable">

        <thead class="table-dark">

            <tr>

                <th width="17%">Material</th>

                <th width="8%">Width</th>

                <th width="8%">Length</th>

                <th width="8%">Thickness</th>

                <th width="8%">Color</th>

                <th width="7%">Nos</th>

                <th width="13%">Qty (Rft / Sq.ft / Sq.Mtr)</th>

                <th width="9%">Unit</th>

                <th width="8%">Rate</th>

                <th width="14%">Amount</th>

                <th width="5%">

                    <button type="button" class="btn btn-success btn-sm" id="addPanelRow">

                        <i class="bi bi-plus-lg"></i>

                    </button>

                </th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>

                    <input type="text" name="material[]" class="form-control">

                </td>

                <td>

                    <input type="number" step="any" min="0" name="width[]" class="form-control width" placeholder="Width">

                </td>

                <td>

                    <input type="number" step="any" min="0" name="length[]" class="form-control length" placeholder="Length">

                </td>

                <td>

                    <input type="text" name="thickness[]" class="form-control" placeholder="50 mm">

                </td>

                <td>

                    <input type="text" name="color[]" class="form-control" placeholder="Blue">

                </td>

                <td>

                    <input type="number" name="nos[]" class="form-control nos" min="0" value="1" placeholder="Nos">

                </td>

                <td>

                    <input type="number" name="qty[]" class="form-control qty" step="any" readonly>

                </td>

                <td>

                    <select name="unit[]" class="form-select">

                        <option value="Rft">Running Feet</option>

                        <option value="Sq.ft">Sq.ft</option>

                        <option value="Nos">Nos</option>

                        <option value="Mtr">Meter</option>

                        <option value="Sq.Mtr">Sq.Mtr</option>

                    </select>

                </td>

                <td>

                    <input type="number" name="rate[]" class="form-control rate" step="0.01">

                </td>

                <td>

                    <input type="number" name="amount[]" class="form-control amount" readonly>

                </td>

                <td>

                    <button type="button" class="btn btn-danger btn-sm removePanelRow">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>

            </tr>

        </tbody>

    </table>

</div>

<script>
    function bindPanelEvents() {

        $(document).off('click', '#addPanelRow').on('click', '#addPanelRow', function() {

            let row = $('#panelTable tbody tr:first').clone();

            row.find('input').each(function() {
                if ($(this).hasClass('nos')) {
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

        $(document).off('click', '.removePanelRow').on('click', '.removePanelRow', function() {

            if ($('#panelTable tbody tr').length > 1) {

                $(this).closest('tr').remove();

                calculatePanelTotal();

            }

        });

        $(document).off('keyup change input', '#panelTable .length, #panelTable .width, #panelTable .nos').on('keyup change input', '#panelTable .length, #panelTable .width, #panelTable .nos', function() {

            let tr = $(this).closest('tr');

            let length = parseFloat(tr.find('.length').val()) || 0;
            let width = parseFloat(tr.find('.width').val()) || 0;
            let nos = parseFloat(tr.find('.nos').val()) || 0;

            let qty = length * width * nos;
            tr.find('.qty').val(qty.toFixed(2));

            let rate = parseFloat(tr.find('.rate').val()) || 0;
            let amount = qty * rate;

            tr.find('.amount').val(amount.toFixed(2));

            calculatePanelTotal();

        });

        $(document).off('keyup change input', '#panelTable .qty, #panelTable .rate').on('keyup change input', '#panelTable .qty, #panelTable .rate', function() {

            let tr = $(this).closest('tr');

            let qty = parseFloat(tr.find('.qty').val()) || 0;
            let rate = parseFloat(tr.find('.rate').val()) || 0;

            let amount = qty * rate;

            tr.find('.amount').val(amount.toFixed(2));

            calculatePanelTotal();

        });

        $(document).off('keyup change input', '#gst_percent, input[name="gst_percent"]').on('keyup change input', '#gst_percent, input[name="gst_percent"]', function() {

            calculatePanelTotal();

        });

        calculatePanelTotal();

    }

    function calculatePanelTotal() {

        let subtotal = 0;

        $('#panelTable .amount').each(function() {

            subtotal += parseFloat($(this).val()) || 0;

        });

        $('#subtotal').val(subtotal.toFixed(2));

        let gst = parseFloat($('#gst_percent').val() || $('input[name="gst_percent"]').val()) || 0;

        let gstAmount = subtotal * gst / 100;

        $('#gst_amount').val(gstAmount.toFixed(2));

        $('#grand_total').val((subtotal + gstAmount).toFixed(2));

    }

    bindPanelEvents();

</script>
