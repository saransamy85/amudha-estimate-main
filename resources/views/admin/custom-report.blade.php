@extends('layouts.adminbase')

@section('content')
    <div class="container py-4">

        <div class="card shadow rounded-4 border-0">

            <div class="card-header bg-primary text-white rounded-top-4 py-3">

                <h4 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Generate Business & Custom Statement</h4>

            </div>

            <div class="card-body p-4">

                <form action="{{ route('customreport.pdf') }}" method="POST" id="customReportFormStandalone">

                    @csrf

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Report Type</label>
                            <select name="report_type" id="reportTypeSelect" class="form-select" required>
                                <option value="business">Business Report</option>
                                <option value="source">Lead Source Report</option>
                                <option value="estimate">Estimate Report</option>
                                <option value="customer">Customer Report</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-danger w-100 py-2">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Generate
                            </button>
                        </div>

                        {{-- Multiple Choice Channels --}}
                        <div class="col-12" id="sourceChannelChoiceGroup" style="display: none;">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold small text-secondary">Acquisition Channel Scope (Multiple Choice):</span>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" id="btnCustomSelectAll">Select All</button>
                                        <span class="text-muted small">&bull;</span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-secondary" id="btnCustomClear">Clear</button>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    @php
                                        $channels = ['Website', 'WhatsApp', 'Phone Call', 'Showroom-walk-in', 'Reference', 'Old Database'];
                                    @endphp
                                    @foreach($channels as $ch)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-check p-2 bg-white rounded border">
                                                <input class="form-check-input ms-0 me-2 custom-ch-cb" type="checkbox" name="sources[]" value="{{ $ch }}" id="ch_{{ Str::slug($ch) }}" checked>
                                                <label class="form-check-label small fw-medium" for="ch_{{ Str::slug($ch) }}">{{ $ch }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const standaloneForm = document.getElementById('customReportFormStandalone');
        const typeSelect = document.getElementById('reportTypeSelect');
        const channelGroup = document.getElementById('sourceChannelChoiceGroup');
        const btnSelectAll = document.getElementById('btnCustomSelectAll');
        const btnClear = document.getElementById('btnCustomClear');
        const cbs = document.querySelectorAll('.custom-ch-cb');

        function toggleChannels() {
            if (typeSelect) {
                const isSource = (typeSelect.value === 'source');
                if (channelGroup) {
                    channelGroup.style.display = isSource ? 'block' : 'none';
                }
                if (standaloneForm) {
                    standaloneForm.method = 'POST';
                    standaloneForm.action = "{{ route('customreport.pdf') }}";
                }
            }
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', toggleChannels);
            toggleChannels();
        }

        if (btnSelectAll) {
            btnSelectAll.addEventListener('click', function(e) {
                e.preventDefault();
                cbs.forEach(cb => cb.checked = true);
            });
        }

        if (btnClear) {
            btnClear.addEventListener('click', function(e) {
                e.preventDefault();
                cbs.forEach(cb => cb.checked = false);
            });
        }
    });
    </script>
    @endpush
@endsection
