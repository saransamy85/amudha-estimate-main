@php
if(Auth::check() && Auth::user()->Role == 'Admin'){
    $layout = 'layouts.adminbase';
    $dashRoute = route('dashboard1');
} elseif(Auth::check() && Auth::user()->Role == 'Sales'){
    $layout = 'layouts.salesbase';
    $dashRoute = route('salesdashboard');
} else {
    $layout = 'layouts.app';
    $dashRoute = route('salesdashboard');
}
@endphp

@extends($layout)

@section('title', 'Proposal - ' . $estimate->estimate_no)

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ $dashRoute }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-2xs">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
            <h4 class="mb-0 fw-bold text-dark">Estimate: <span class="text-primary">{{ $estimate->estimate_no }}</span></h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('estimates.edit', $estimate->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm fw-semibold">
                <i class="bi bi-pencil me-1"></i> Edit Estimate
            </a>
            <a href="{{ route('estimates.pdf', $estimate->id) }}" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm fw-semibold" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        @include('estimates.template', [
            'estimate' => $estimate,
            'logo' => asset('logo.png')
        ])
    </div>
</div>
@endsection
