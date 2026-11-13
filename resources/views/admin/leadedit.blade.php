@php

if (Auth::user()->Role == 'Admin') {
$layout = 'layouts.adminbase';
} elseif (Auth::user()->Role == 'Sales') {
$layout = 'layouts.salesbase';
} elseif (Auth::user()->Role == 'Purchase') {
$layout = 'layouts.purchasebase';
} elseif (Auth::user()->Role == 'Accounts') {
$layout = 'layouts.accountsbase';
} elseif (Auth::user()->Role == 'Receptionist') {
$layout = 'layouts.receptionbase';
} else {
$layout = 'layouts.adminbase';
}

@endphp

@extends($layout)

@section('title', 'Edit Lead')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3>
            <i class="bi bi-pencil-square"></i>
            Edit Lead
        </h3>

        <a href="#" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                Lead Details
            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route('leads.update', $lead->id) }}" method="POST">

                @csrf

                @method('PUT')


                <div class="row g-3">


                    {{-- SOURCE --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Source
                        </label>

                        <select name="source" class="form-select">

                            <option value="">
                                Select Source
                            </option>

                            <option value="Website" {{ old('source', $lead->source) == 'Website' ? 'selected' : '' }}>
                                Website
                            </option>

                            <option value="Google Ads" {{ old('source', $lead->source) == 'Google Ads' ? 'selected' : '' }}>
                                Google Ads
                            </option>

                            <option value="Facebook" {{ old('source', $lead->source) == 'Facebook' ? 'selected' : '' }}>
                                Facebook
                            </option>

                            <option value="Instagram" {{ old('source', $lead->source) == 'Instagram' ? 'selected' : '' }}>
                                Instagram
                            </option>

                            <option value="Reference" {{ old('source', $lead->source) == 'Reference' ? 'selected' : '' }}>
                                Reference
                            </option>

                            <option value="Direct" {{ old('source', $lead->source) == 'Direct' ? 'selected' : '' }}>
                                Direct
                            </option>

                            <option value="Other" {{ old('source', $lead->source) == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- NAME --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Name <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="Name" class="form-control" value="{{ old('Name', $lead->Name) }}" required>

                    </div>


                    {{-- MOBILE --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Mobile <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="Mobile" id="Mobile" class="form-control" value="{{ old('Mobile', $lead->Mobile) }}" maxlength="15" required>

                    </div>


                    {{-- EMAIL --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email) }}">

                    </div>


                    {{-- PRODUCT --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Product
                        </label>

                        <input type="text" name="Product" class="form-control" value="{{ old('Product', $lead->Product) }}">

                    </div>


                    {{-- TOTAL AREA --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Total Area
                        </label>

                        <input type="text" name="Total_Area" class="form-control" value="{{ old('Total_Area', $lead->Total_Area) }}">

                    </div>


                    {{-- SITE LOCATION --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Site Location
                        </label>

                        <input type="text" name="Site_location" class="form-control" value="{{ old('Site_location', $lead->Site_location) }}">

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="Status" class="form-select" required>

                            <option value="">
                                Select Status
                            </option>

                            <option value="New" {{ old('Status', $lead->Status) == 'New' ? 'selected' : '' }}>
                                New
                            </option>

                            <option value="Details shared" {{ old('Status', $lead->Status) == 'Details shared' ? 'selected' : '' }}>
                                Details Shared
                            </option>

                            <option value="Follow up" {{ old('Status', $lead->Status) == 'Follow up' ? 'selected' : '' }}>
                                Follow Up
                            </option>

                            <option value="Quote Shared" {{ old('Status', $lead->Status) == 'Quote Shared' ? 'selected' : '' }}>
                                Quote Shared
                            </option>

                            <option value="Site Visit" {{ old('Status', $lead->Status) == 'Site Visit' ? 'selected' : '' }}>
                                Site Visit
                            </option>

                            <option value="Confirmed" {{ old('Status', $lead->Status) == 'Confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>

                            <option value="RNR" {{ old('Status', $lead->Status) == 'RNR' ? 'selected' : '' }}>
                                RNR
                            </option>

                            <option value="Cancelled" {{ old('Status', $lead->Status) == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- DESCRIPTION --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="Description" class="form-control" rows="5">{{ old('Description', $lead->Description) }}</textarea>

                    </div>


                </div>


                {{-- BUTTONS --}}
                <div class="mt-4">

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Update Lead

                    </button>

                    <a href="#" class="btn btn-secondary">

                        Cancel

                    </a>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection
