@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('salesdashboard') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-house"></i> Home
            </a>
            <a href="{{ route('estimates.show', $estimate->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> View Estimate
            </a>
            <h4 class="mb-0 ms-2">Edit Estimate: <span class="text-primary">{{ $estimate->estimate_no }}</span></h4>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please check the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('estimates.update', $estimate->id) }}" method="POST" id="estimateForm">
        @csrf
        @method('PUT')

        {{-- CUSTOMER DETAILS --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Customer Details</h6>
            </div>
            <div class="card-body">
                @php
                    $addressParts = explode(',', $estimate->customer_address, 2);
                    $line1 = trim($addressParts[0] ?? '');
                    $line2 = trim($addressParts[1] ?? '');
                @endphp

                <div class="row g-2">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $estimate->customer_name) }}" required placeholder="Customer Name">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Mobile No <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $estimate->mobile) }}" required placeholder="Mobile Number">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $line1) }}" required placeholder="Address Line 1">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $line2) }}" placeholder="Address Line 2">
                    </div>
                </div>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-text me-2"></i>Description <span class="text-danger">*</span></h6>
            </div>
            <div class="card-body">
                <textarea name="description" class="form-control" rows="4" required placeholder="Enter estimate description...">{{ old('description', $estimate->description) }}</textarea>
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Estimate Items</h6>
                <button type="button" class="btn btn-primary btn-sm" onclick="addRow()">
                    <i class="bi bi-plus-circle me-1"></i> Add Row
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="min-width: 180px;">Location</th>
                                <th style="width: 140px;">Area <span class="text-danger">*</span></th>
                                <th style="width: 130px;">Unit <span class="text-danger">*</span></th>
                                <th style="width: 140px;">Rate (₹) <span class="text-danger">*</span></th>
                                <th style="width: 160px;">Value (₹)</th>
                                <th style="width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items">
                            @forelse($estimate->items as $key => $item)
                            <tr data-item-id="{{ $item->id }}">
                                <td>
                                    <input type="text" name="location[]" class="form-control form-control-sm" value="{{ old('location.'.$key, $item->location) }}" placeholder="Location / Item Name">
                                </td>
                                <td>
                                    <input type="number" step="any" min="0" name="area[]" class="form-control form-control-sm area text-end" value="{{ old('area.'.$key, $item->area) }}" required placeholder="0.00">
                                </td>
                                <td>
                                    <select name="unit[]" class="form-select form-select-sm" required>
                                        <option value="">Select</option>
                                        @foreach(['KG', 'TON', 'SQ FT', 'SQ M', 'R.FT', 'NOS', 'JOB', 'SET', 'LOT', 'LS', 'R.Mtr'] as $u)
                                            <option value="{{ $u }}" {{ old('unit.'.$key, $item->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="any" min="0" name="rate[]" class="form-control form-control-sm rate text-end" value="{{ old('rate.'.$key, $item->rate) }}" required placeholder="0.00">
                                </td>
                                <td>
                                    <input type="text" name="value[]" class="form-control form-control-sm value text-end bg-light" value="{{ old('value.'.$key, number_format($item->value, 2, '.', '')) }}" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm removeRow" title="Delete Item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>
                                    <input type="text" name="location[]" class="form-control form-control-sm" placeholder="Location / Item Name">
                                </td>
                                <td>
                                    <input type="number" step="any" min="0" name="area[]" class="form-control form-control-sm area text-end" required placeholder="0.00">
                                </td>
                                <td>
                                    <select name="unit[]" class="form-select form-select-sm" required>
                                        <option value="">Select</option>
                                        @foreach(['KG', 'TON', 'SQ FT', 'SQ M', 'R.FT', 'NOS', 'JOB', 'SET', 'LOT', 'LS', 'R.Mtr'] as $u)
                                            <option value="{{ $u }}" {{ $u == 'SQ FT' ? 'selected' : '' }}>{{ $u }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="any" min="0" name="rate[]" class="form-control form-control-sm rate text-end" required placeholder="0.00">
                                </td>
                                <td>
                                    <input type="text" name="value[]" class="form-control form-control-sm value text-end bg-light" value="0.00" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm removeRow" title="Delete Item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()">
                    <i class="bi bi-plus-circle me-1"></i> Add Row
                </button>
            </div>
        </div>

        {{-- ADDITIONAL NOTES --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-card-text me-2"></i>Additional Notes</h6>
                <button type="button" class="btn btn-success btn-sm" id="addNote">
                    <i class="bi bi-plus-circle me-1"></i> Add Note
                </button>
            </div>
            <div class="card-body">
                <input type="hidden" name="notes_submitted" value="1">
                <div id="additionalNotes">
                    @if(isset($estimate->notes) && $estimate->notes->count())
                        @foreach($estimate->notes as $note)
                        <div class="row mb-2 additional-note align-items-center">
                            <div class="col-md-10">
                                <input type="text" name="additional_notes[]" class="form-control" value="{{ $note->note }}" placeholder="Enter additional note">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger btn-sm removeNote">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <small class="text-muted">You can add terms, payment conditions, or special instructions.</small>
            </div>
        </div>

        {{-- TOTALS & TAXES --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Adjustments & Taxes</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Discount (₹)</label>
                            <input type="number" name="discount" id="discount" class="form-control" value="{{ old('discount', $estimate->discount ?? 0) }}" min="0" step="any" placeholder="Enter discount amount">
                            <small class="text-muted">Discount cannot exceed subtotal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">GST Percentage (%)</label>
                            <input type="number" name="gst_percent" id="gst_percent" class="form-control" value="{{ old('gst_percent', $estimate->gst_percent ?? 18) }}" min="0" max="100" step="any" placeholder="Enter GST percentage">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Transportation Charges (₹)</label>
                            <input type="number" name="transportation" id="transportation" class="form-control" value="{{ old('transportation', $estimate->transport_charges ?? 0) }}" min="0" step="any" placeholder="Enter transportation charges">
                        </div>
                    </div>
                </div>
            </div>

            {{-- CALCULATION SUMMARY --}}
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-calculator me-2"></i>Calculation Summary</h6>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th class="text-secondary">Subtotal:</th>
                                <td class="text-end fw-bold fs-6" id="showSubtotal">₹ {{ number_format($estimate->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Discount:</th>
                                <td class="text-end fw-bold text-danger fs-6" id="showDiscount">- ₹ {{ number_format($estimate->discount, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">Transportation Charges:</th>
                                <td class="text-end fw-bold fs-6" id="showTransportation">₹ {{ number_format($estimate->transport_charges, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-secondary">GST (<span id="showGstPercent">{{ (float)$estimate->gst_percent }}</span>%):</th>
                                <td class="text-end fw-bold fs-6" id="showGst">₹ {{ number_format($estimate->gst_amount, 2) }}</td>
                            </tr>
                            <tr class="border-top border-2">
                                <th class="h5 pt-2 text-dark">Grand Total:</th>
                                <td class="text-end h4 pt-2 fw-bold text-success" id="showGrandTotal">₹ {{ number_format($estimate->net_total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-1"></i> Update Estimate
            </button>
            <a href="{{ route('estimates.show', $estimate->id) }}" class="btn btn-secondary px-4">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // ==========================================
    // ADD NEW ROW
    // ==========================================
    function addRow() {
        const rowHtml = `
            <tr>
                <td>
                    <input type="text" name="location[]" class="form-control form-control-sm" placeholder="Location / Item Name">
                </td>
                <td>
                    <input type="number" step="any" min="0" name="area[]" class="form-control form-control-sm area text-end" required placeholder="0.00">
                </td>
                <td>
                    <select name="unit[]" class="form-select form-select-sm" required>
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
                <td>
                    <input type="number" step="any" min="0" name="rate[]" class="form-control form-control-sm rate text-end" required placeholder="0.00">
                </td>
                <td>
                    <input type="text" name="value[]" class="form-control form-control-sm value text-end bg-light" value="0.00" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm removeRow" title="Delete Item">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        document.getElementById('items').insertAdjacentHTML('beforeend', rowHtml);
        calculateEstimateTotal();
    }

    // ==========================================
    // CLICK EVENTS (DELETE ROW, NOTES)
    // ==========================================
    document.addEventListener('click', function(e) {
        // DELETE ITEM ROW
        const removeRowBtn = e.target.closest('.removeRow');
        if (removeRowBtn) {
            const rows = document.querySelectorAll('#items tr');
            if (rows.length <= 1) {
                alert('At least one item is required in the estimate.');
                return;
            }

            if (confirm('Are you sure you want to delete this estimate item?')) {
                removeRowBtn.closest('tr').remove();
                calculateEstimateTotal();
            }
        }

        // ADD NOTE
        if (e.target.closest('#addNote')) {
            const noteHtml = `
                <div class="row mb-2 additional-note align-items-center">
                    <div class="col-md-10">
                        <input type="text" name="additional_notes[]" class="form-control" placeholder="Enter additional note">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm removeNote">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
            `;
            document.getElementById('additionalNotes').insertAdjacentHTML('beforeend', noteHtml);
        }

        // REMOVE NOTE
        const removeNoteBtn = e.target.closest('.removeNote');
        if (removeNoteBtn) {
            removeNoteBtn.closest('.additional-note').remove();
        }
    });

    // ==========================================
    // CALCULATE TOTALS
    // ==========================================
    function calculateEstimateTotal() {
        let subtotal = 0;

        document.querySelectorAll('#items tr').forEach(function(row) {
            const areaInput = row.querySelector('[name="area[]"]');
            const rateInput = row.querySelector('[name="rate[]"]');
            const valueInput = row.querySelector('[name="value[]"]');

            if (!areaInput || !rateInput) {
                return;
            }

            const area = parseFloat(areaInput.value) || 0;
            const rate = parseFloat(rateInput.value) || 0;
            const value = area * rate;

            if (valueInput) {
                valueInput.value = value.toFixed(2);
            }

            subtotal += value;
        });

        // Discount
        const discountInput = document.getElementById('discount');
        let discount = discountInput ? (parseFloat(discountInput.value) || 0) : 0;
        if (discount > subtotal) {
            discount = subtotal;
        }

        // Transportation Charges
        const transportInput = document.getElementById('transportation');
        let transportation = transportInput ? (parseFloat(transportInput.value) || 0) : 0;

        // GST Percentage
        const gstInput = document.getElementById('gst_percent');
        let gstPercent = gstInput ? (parseFloat(gstInput.value) || 0) : 0;

        // Taxable Amount
        let taxableAmount = subtotal - discount + transportation;
        if (taxableAmount < 0) {
            taxableAmount = 0;
        }

        // GST Amount
        let gstAmount = (taxableAmount * gstPercent) / 100;

        // Net Total / Grand Total
        let grandTotal = taxableAmount + gstAmount;

        // Update DOM display safely
        const showSubtotal = document.getElementById('showSubtotal');
        if (showSubtotal) showSubtotal.innerText = '₹ ' + subtotal.toFixed(2);

        const showDiscount = document.getElementById('showDiscount');
        if (showDiscount) showDiscount.innerText = '- ₹ ' + discount.toFixed(2);

        const showTransportation = document.getElementById('showTransportation');
        if (showTransportation) showTransportation.innerText = '₹ ' + transportation.toFixed(2);

        const showGstPercent = document.getElementById('showGstPercent');
        if (showGstPercent) showGstPercent.innerText = gstPercent;

        const showGst = document.getElementById('showGst');
        if (showGst) showGst.innerText = '₹ ' + gstAmount.toFixed(2);

        const showGrandTotal = document.getElementById('showGrandTotal');
        if (showGrandTotal) showGrandTotal.innerText = '₹ ' + grandTotal.toFixed(2);
    }

    // ==========================================
    // INPUT LISTENERS FOR INSTANT RECALCULATION
    // ==========================================
    document.addEventListener('input', function(e) {
        if (
            e.target.matches('[name="area[]"]') ||
            e.target.matches('[name="rate[]"]') ||
            e.target.id === 'discount' ||
            e.target.id === 'transportation' ||
            e.target.id === 'gst_percent'
        ) {
            calculateEstimateTotal();
        }
    });

    // Form submit validation
    const estimateForm = document.getElementById('estimateForm');
    if (estimateForm) {
        estimateForm.addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('#items tr');
            if (rows.length === 0) {
                e.preventDefault();
                alert('Please add at least one estimate item before updating.');
                return false;
            }
        });
    }

    // Calculate on initial load
    document.addEventListener('DOMContentLoaded', function() {
        calculateEstimateTotal();
    });
</script>
@endsection
