@extends('layouts.salesbase')

@section('title', 'Create Estimate')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Create New Estimate</h4>
            <small class="text-muted">Generate a client quotation with itemized measurements and GST</small>
        </div>
        <a href="{{ route('salesdashboard') }}" class="btn btn-light border rounded-3 px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-3">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <form action="{{ route('estimates.store') }}" method="POST">
            @csrf

            <h5 class="fw-bold mb-3">Customer Details</h5>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="re_estimate" value="1" id="reEstimateCheck">
                <label class="form-check-label fw-medium" for="reEstimateCheck">Re-estimate</label>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile No" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="address_line1" class="form-control" placeholder="Address Line 1" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="address_line2" class="form-control" placeholder="Address Line 2">
                </div>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-2">Description</h5>
            <textarea name="description" class="form-control" rows="3" required>Supply and fixing of HI TENSILE CSC BRAND TENSILE 750GSM...</textarea>

            <hr class="my-4">

            <h5 class="fw-bold mb-3">Estimate Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Location</th>
                            <th width="140">Area</th>
                            <th width="140">Unit</th>
                            <th width="150">Rate (Rs)</th>
                            <th width="160">Value (Rs)</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody id="items">
                        <tr>
                            <td><input name="location[]" class="form-control" required></td>
                            <td><input type="number" step="any" min="0" name="area[]" class="form-control area" required></td>
                            <td>
                                <select name="unit[]" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="KG">KG</option>
                                    <option value="TON">TON</option>
                                    <option value="SQ FT" selected>SQ FT</option>
                                    <option value="SQ M">SQ M</option>
                                    <option value="R.FT">R.FT</option>
                                    <option value="NOS">NOS</option>
                                    <option value="JOB">JOB</option>
                                    <option value="SET">SET</option>
                                    <option value="LOT">LOT</option>
                                    <option value="LS">LS</option>
                                    <option value="R.Mtr">R.Mtr</option>
                                </select>
                            </td>
                            <td><input type="number" step="any" min="0" name="rate[]" class="form-control rate" required></td>
                            <td><input name="value[]" class="form-control value" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" onclick="addRow()" class="btn btn-sm btn-secondary rounded-3 mt-1">+ Add Row</button>

            <hr class="my-4">

            <h5 class="fw-bold mb-3">Calculation</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Discount (Rs)</label>
                    <input type="number" step="any" min="0" name="discount" id="discount" value="0" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transportation Charges (Rs)</label>
                    <input type="number" step="any" min="0" name="transport_charges" id="transport_charges" value="0" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">GST %</label>
                    <input type="number" step="any" min="0" name="gst_percent" value="18" class="form-control">
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <h6 class="mb-1">Subtotal : ₹ <span id="subtotal">0.00</span></h6>
                <h6 class="mb-1">GST Amount : ₹ <span id="gstamount">0.00</span></h6>
                <h5 class="mb-0 fw-bold">Grand Total : ₹ <span id="grandtotal">0.00</span></h5>
            </div>

            <hr class="my-4">

            <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3">
                <i class="bi bi-check2-circle me-1"></i> Generate Estimate
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function calculateRow(row) {
        const area = parseFloat(row.querySelector('.area').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const value = area * rate;
        row.querySelector('.value').value = value.toFixed(2);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll('.value').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });

        let discount = parseFloat(document.getElementById('discount').value) || 0;
        if (discount > subtotal) discount = subtotal;

        const transportCharges = parseFloat(document.getElementById('transport_charges').value) || 0;
        const gstPercent = parseFloat(document.querySelector('[name="gst_percent"]').value) || 0;

        const taxableAmount = (subtotal - discount) + transportCharges;
        const gstAmount = taxableAmount * gstPercent / 100;
        const grandTotal = taxableAmount + gstAmount;

        document.getElementById('subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('gstamount').innerText = gstAmount.toFixed(2);
        document.getElementById('grandtotal').innerText = grandTotal.toFixed(2);
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('area') || e.target.classList.contains('rate')) {
            calculateRow(e.target.closest('tr'));
        }
        if (e.target.name === 'gst_percent' || e.target.id === 'transport_charges' || e.target.id === 'discount') {
            calculateGrandTotal();
        }
    });

    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-row');
        if (removeBtn) {
            const tbody = document.getElementById('items');
            if (tbody.querySelectorAll('tr').length > 1) {
                removeBtn.closest('tr').remove();
                calculateGrandTotal();
            }
        }
    });

    function addRow() {
        document.getElementById('items').insertAdjacentHTML('beforeend', `
            <tr>
                <td><input name="location[]" class="form-control" required></td>
                <td><input type="number" step="any" min="0" name="area[]" class="form-control area" required></td>
                <td>
                    <select name="unit[]" class="form-select" required>
                        <option value="">Select</option>
                        <option value="KG">KG</option>
                        <option value="TON">TON</option>
                        <option value="SQ FT" selected>SQ FT</option>
                        <option value="SQ M">SQ M</option>
                        <option value="R.FT">R.FT</option>
                        <option value="NOS">NOS</option>
                        <option value="JOB">JOB</option>
                        <option value="SET">SET</option>
                        <option value="LOT">LOT</option>
                        <option value="LS">LS</option>
                        <option value="R.Mtr">R.Mtr</option>
                    </select>
                </td>
                <td><input type="number" step="any" min="0" name="rate[]" class="form-control rate" required></td>
                <td><input name="value[]" class="form-control value" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    }
</script>
@endpush
