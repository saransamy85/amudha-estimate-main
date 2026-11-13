<table class="table table-hover align-middle mb-0 custom-lead-table" id="referenceTable">
    <thead>
        <tr>
            <th class="text-start">Date & ID</th>
            <th class="text-start">Prospect Details</th>
            <th class="text-start">Site & Location</th>
            <th class="text-start">Product & Area</th>
            <th class="text-center">Channel</th>
            <th class="text-center">Status</th>
            <th class="text-center" style="min-width: 170px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($referenceLeads as $reflead)
        @php
            $refInitial = strtoupper(substr($reflead->Name ?? 'R', 0, 1));
            $cleanRefMobile = preg_replace('/[^0-9]/', '', $reflead->Mobile);
        @endphp
        <tr>
            <td class="text-start">
                <div class="fw-bold text-dark">#{{ $reflead->id }}</div>
                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ $reflead->created_at->format('d M Y') }}</small>
            </td>
            <td class="text-start">
                <div class="d-flex align-items-center gap-2">
                    <div class="client-avatar" style="background: #faf5ff; color: #9333ea;">
                        {{ $refInitial }}
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">{{ $reflead->Name }}</div>
                        @if($reflead->Mobile)
                            <div class="d-flex align-items-center gap-1">
                                <a href="tel:{{ $reflead->Mobile }}" class="text-muted text-decoration-none small">
                                    <i class="bi bi-telephone me-1"></i>{{ $reflead->Mobile }}
                                </a>
                                @if($cleanRefMobile)
                                    <a href="https://wa.me/91{{ $cleanRefMobile }}" target="_blank" class="badge bg-success text-white text-decoration-none px-1.5 py-0.5 rounded-pill" style="font-size: 10px;">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </td>
            <td class="text-start">
                <div class="text-truncate text-dark small fw-medium" style="max-width: 170px;" title="{{ $reflead->Site_location }}">
                    <i class="bi bi-geo-alt-fill text-danger me-1 small"></i>{{ $reflead->Site_location ?: '—' }}
                </div>
            </td>
            <td class="text-start">
                <span class="badge bg-light text-dark border px-2 py-1 mb-1 d-inline-block">{{ $reflead->Product ?: 'General' }}</span>
                @if($reflead->Total_Area)
                    <div class="text-muted small">{{ $reflead->Total_Area }} Sq.ft</div>
                @endif
            </td>
            <td class="text-center">
                <span class="badge bg-purple-subtle text-purple border rounded-pill px-2.5 py-1" style="background: #faf5ff; color: #9333ea; border-color: #e9d5ff !important;">
                    <i class="bi bi-people-fill me-1"></i>{{ $reflead->source }}
                </span>
            </td>
            <td class="text-center">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold">
                    {{ $reflead->Status }}
                </span>
            </td>
            <td class="text-center">
                <div class="d-inline-flex align-items-center gap-1">
                    <button class="btn btn-sm btn-light border text-primary action-icon-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal1{{ $reflead->id }}" title="Add Feedback">
                        <i class="bi bi-chat-left-text"></i>
                    </button>
                    <button class="btn btn-sm btn-light border text-info action-icon-btn" data-bs-toggle="modal" data-bs-target="#timelineModal1{{ $reflead->id }}" title="View Timeline">
                        <i class="bi bi-clock-history"></i>
                    </button>
                    @if(strtolower($reflead->Status) != 'cancelled')
                        <a href="{{ route('estimates.create1', ['lead_id' => $reflead->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 11.5px;">
                            <i class="bi bi-file-earmark-plus"></i> Estimate
                        </a>
                    @endif
                </div>

                {{-- Feedback Modal --}}
                <div class="modal fade text-start" id="feedbackModal1{{ $reflead->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <form method="POST" action="{{ route('addfeedback') }}">
                                @csrf
                                <div class="modal-header border-0 pb-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="table-row-icon bg-primary-subtle text-primary" style="width: 36px; height: 36px; border-radius: 10px; display:flex; align-items:center; justify-content:center;">
                                            <i class="bi bi-chat-quote-fill"></i>
                                        </div>
                                        <div>
                                            <h5 class="modal-title fw-bold text-dark">Reference Lead Feedback</h5>
                                            <p class="text-muted small mb-0">{{ $reflead->Name }} (#{{ $reflead->id }})</p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body py-3">
                                    <input type="hidden" name="lead_id" value="{{ $reflead->id }}">

                                    <div class="mb-3">
                                        <label class="form-label text-secondary fw-semibold small mb-1">Feedback Notes <span class="text-danger">*</span></label>
                                        <textarea name="feedback" class="form-control rounded-3 p-2.5" rows="3" placeholder="Enter conversation notes..." required></textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label text-secondary fw-semibold small mb-1">Pipeline Status <span class="text-danger">*</span></label>
                                        <select class="form-select rounded-3 py-2 px-3" name="status" required>
                                            <option value="Details shared" {{ $reflead->Status == 'Details shared' ? 'selected' : '' }}>Details shared</option>
                                            <option value="Follow up" {{ $reflead->Status == 'Follow up' ? 'selected' : '' }}>Follow up</option>
                                            <option value="Confirmed" {{ $reflead->Status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="Quote Shared" {{ $reflead->Status == 'Quote Shared' ? 'selected' : '' }}>Quote Shared</option>
                                            <option value="Site Visit" {{ $reflead->Status == 'Site Visit' ? 'selected' : '' }}>Schedule For Site Visit</option>
                                            <option value="RNR" {{ $reflead->Status == 'RNR' ? 'selected' : '' }}>RNR</option>
                                            <option value="Cancelled" {{ $reflead->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 d-inline-flex align-items-center gap-1 shadow-sm">
                                        <i class="bi bi-check-circle"></i> Save Feedback
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Timeline Modal --}}
                <div class="modal fade text-start" id="timelineModal1{{ $reflead->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header border-0 pb-0">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="table-row-icon bg-info-subtle text-info" style="width: 36px; height: 36px; border-radius: 10px; display:flex; align-items:center; justify-content:center;">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div>
                                        <h5 class="modal-title fw-bold text-dark">Timeline: {{ $reflead->Name }}</h5>
                                        <p class="text-muted small mb-0">Referral Lead #{{ $reflead->id }}</p>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body py-3">
                                <div class="modern-timeline">
                                    <div class="timeline-step">
                                        <div class="timeline-step-marker bg-primary"></div>
                                        <div class="timeline-step-content p-3 rounded-3 bg-light border mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <strong class="text-primary small">Referral Registered</strong>
                                                <span class="text-muted small">{{ $reflead->created_at->format('d M Y h:i A') }}</span>
                                            </div>
                                            <div class="small text-muted">Channel: Reference</div>
                                        </div>
                                    </div>

                                    @if(!empty($reflead->Description))
                                        <div class="timeline-step">
                                            <div class="timeline-step-marker bg-info"></div>
                                            <div class="timeline-step-content p-3 rounded-3 bg-light border mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <strong class="text-info small">Initial Notes</strong>
                                                    <span class="text-muted small">{{ $reflead->created_at->format('d M Y h:i A') }}</span>
                                                </div>
                                                <p class="small text-dark mb-0">{{ $reflead->Description }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @forelse($reflead->feedbacks as $fb)
                                        <div class="timeline-step">
                                            <div class="timeline-step-marker bg-success"></div>
                                            <div class="timeline-step-content p-3 rounded-3 bg-light border mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <strong class="text-success small">Follow-up Note</strong>
                                                    <span class="text-muted small">{{ $fb->created_at->format('d M Y h:i A') }}</span>
                                                </div>
                                                <p class="small text-dark mb-0">{{ $fb->feedback }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-3 text-center text-muted small bg-light rounded-3 border">
                                            No follow-up notes logged yet.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
