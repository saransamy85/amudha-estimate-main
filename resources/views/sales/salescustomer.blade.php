@extends('layouts.salesbase')

@section('title', 'Sales Customers')

@section('content')
<div class="container-fluid py-3">

    {{-- Header & Action --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Customers</h4>
            <small class="text-muted">Manage confirmed clients and project details</small>
        </div>

        <button class="btn btn-primary rounded-3 px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#customerModal">
            <i class="bi bi-person-plus-fill"></i>
            <span>New Customer</span>
        </button>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div class="fw-medium">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <div class="mb-3">
            <form method="GET" action="{{ route('salescustomer') }}" class="d-flex flex-wrap gap-2 align-items-center">
                <div class="position-relative" style="min-width: 260px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" class="form-control rounded-pill ps-5" placeholder="Search name, mobile, location..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-3">Search</button>
                @if(request('search'))
                    <a href="{{ route('salescustomer') }}" class="btn btn-light border rounded-pill px-3 text-secondary">Clear</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th>Date</th>
                        <th>Mobile</th>
                        <th>Location</th>
                        <th>Area</th>
                        <th>Product</th>
                        <th class="text-end">Values</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cli as $cl)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $cl->client_name }}</td>
                            <td>{{ $cl->created_at ? $cl->created_at->format('d-m-Y') : '-' }}</td>
                            <td>{{ $cl->Mobile }}</td>
                            <td>{{ $cl->Location }}</td>
                            <td>{{ $cl->Area }} Sq.ft</td>
                            <td>{{ $cl->Product }}</td>
                            <td class="text-end fw-semibold">₹ {{ $cl->formatted_total_value ?? $cl->Total_values ?? '0.00' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No Customers Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cli->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $cli->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Customer Add Modal --}}
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('addcustomers') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" name="cusname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="tel" class="form-control" name="cusmob" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" name="cuslocation" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Area</label>
                        <input type="text" class="form-control" name="cusarea" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" name="cusproduct" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Values</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cusvalue" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
