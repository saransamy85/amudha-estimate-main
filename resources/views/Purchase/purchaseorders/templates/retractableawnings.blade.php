<table class="table table-bordered align-middle" id="retractableawningsTable">

    <thead class="table-dark">
        <tr>
            <th width="20%">Material</th>
            <th width="15%">Size</th>
            <th width="15%">Area</th>
            <th width="15%">Unit</th>
            <th width="10%">Nos</th>
            <th width="10%">Rate</th>
            <th width="10%">Total</th>
            <th width="5%">
                <button type="button" class="btn btn-success btn-sm" id="addRetractableawningsRow">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </th>
        </tr>
    </thead>

    <tbody id="retractableawningsBody">

        <tr>

            <td>
                <input type="text" class="form-control" name="material[]" placeholder="Material Name">
            </td>

            <td>
                <input type="text" class="form-control size" name="size[]">
            </td>

            <td>
                <input type="number" class="form-control area" name="qty[]" min="0" step="0.01">
            </td>

            <td>
                <select name="unit[]" class="form-select">
                    <option value="Sqft">Sq.ft</option>
                    <option value="Rft">Running Feet</option>
                    <option value="Nos">Nos</option>
                    <option value="Mtr">Meter</option>
                </select>
            </td>

            <td>
                <input type="number" class="form-control nos" name="nos[]" min="0" step="0.01">
            </td>

            <td>
                <input type="number" class="form-control rate" name="rate[]" min="0" step="0.01">
            </td>

            <td>
                <input type="number" class="form-control amount" name="amount[]" readonly>
            </td>

            <td>
                <button type="button" class="btn btn-danger btn-sm removeRetractableawningsRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>

        </tr>

    </tbody>

</table>
