@extends('layouts.salesbase')

@section('title', 'Sales Greetings')

@section('content')
<div class="sales-greetings-container py-3">

    {{-- Top Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <div class="header-icon-box bg-warning-subtle text-warning">
                <i class="bi bi-envelope-heart-fill"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold text-dark">Client Greetings &amp; Follow-up Mail</h4>
                <span class="text-muted small">Send personalized HTML greeting emails, festive wishes, and promotional brochures to prospects.</span>
            </div>
        </div>

        <a href="{{ route('leaddash') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-bullseye text-info"></i>
            <span>View Leads</span>
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm d-flex align-items-center gap-2 mb-4 p-3" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div class="fw-medium text-success-emphasis">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm d-flex align-items-center gap-2 mb-4 p-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <div class="fw-medium text-danger-emphasis">{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4 p-3" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following errors:</div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Left Column: Compose Greeting Form --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-send-check text-primary me-1"></i> Compose Greeting Mail
                    </h5>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">HTML Email</span>
                </div>

                <form method="POST" action="{{ route('greetings') }}" enctype="multipart/form-data" id="greetingForm">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="emailaddress" class="form-label text-secondary fw-semibold small mb-1">
                                Recipient Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email"
                                       id="emailaddress"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       class="form-control"
                                       placeholder="client@example.com">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="mailSubject" class="form-label text-secondary fw-semibold small mb-1">
                                Email Subject
                            </label>
                            <input type="text"
                                   id="mailSubject"
                                   name="subject"
                                   value="{{ old('subject', 'Warm Greetings from Amudha Decors') }}"
                                   class="form-control"
                                   placeholder="Warm Greetings from Amudha Decors">
                        </div>
                    </div>

                    {{-- Quick Greeting Templates --}}
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold small mb-1">Quick Templates</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 quick-tpl-btn" data-tpl="enquiry">
                                <i class="bi bi-hand-thumbs-up text-primary me-1"></i> Thank You for Enquiry
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 quick-tpl-btn" data-tpl="followup">
                                <i class="bi bi-chat-dots text-info me-1"></i> Quote Follow-up
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 quick-tpl-btn" data-tpl="festive">
                                <i class="bi bi-stars text-warning me-1"></i> Festive Wishes
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 quick-tpl-btn" data-tpl="sitevisit">
                                <i class="bi bi-geo-alt text-success me-1"></i> Site Visit Reminder
                            </button>
                        </div>
                    </div>

                    {{-- Rich Message Editor --}}
                    <div class="mb-3">
                        <label for="greetingEditor" class="form-label text-secondary fw-semibold small mb-1">
                            Greeting Message <span class="text-danger">*</span>
                        </label>
                        <textarea id="greetingEditor" name="message" class="form-control" rows="6">{{ old('message') }}</textarea>
                        <small class="text-danger d-none mt-1" id="messageRequiredHint">Please enter a greeting message before sending.</small>
                    </div>

                    {{-- Image Attachments --}}
                    <div class="mb-4">
                        <label for="attachments" class="form-label text-secondary fw-semibold small mb-1">
                            Attachments (Brochure / Greeting Banner — JPG, PNG, max 2MB each)
                        </label>
                        <input type="file"
                               id="attachments"
                               name="attachments[]"
                               multiple
                               accept="image/jpeg,image/png"
                               class="form-control">
                        <div id="attachmentPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light border rounded-3 px-3 py-2 text-secondary" id="resetGreetingBtn">
                            Clear
                        </button>
                        <button type="submit" class="btn btn-success rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm" id="sendGreetingBtn">
                            <i class="bi bi-send-fill"></i>
                            <span>Send Greeting</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Quick Lead Recipient Picker & Guidelines --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-person-lines-fill text-info me-1"></i> Recent Leads with Email
                        </h6>
                        <small class="text-muted">Click a lead to auto-fill recipient email</small>
                    </div>
                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                        {{ isset($emailLeads) ? count($emailLeads) : 0 }}
                    </span>
                </div>

                <div class="position-relative mb-3">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="leadEmailSearch" class="form-control form-control-sm rounded-pill ps-5 py-2" placeholder="Filter leads by name or email...">
                </div>

                <div class="lead-email-list" style="max-height: 330px; overflow-y: auto;">
                    @if(isset($emailLeads) && count($emailLeads) > 0)
                        @foreach($emailLeads as $lead)
                            <div class="lead-email-item d-flex align-items-center justify-content-between gap-2 p-2.5 rounded-3 border mb-2"
                                 data-search="{{ strtolower(($lead->Name ?? '') . ' ' . ($lead->email ?? '') . ' ' . ($lead->Product ?? '')) }}">
                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                    <div class="client-avatar-sm">
                                        {{ strtoupper(substr($lead->Name ?? 'L', 0, 1)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="fw-semibold text-dark small text-truncate">{{ $lead->Name }}</div>
                                        <div class="text-muted extra-small text-truncate">{{ $lead->email }}</div>
                                    </div>
                                </div>
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 flex-shrink-0 select-lead-email"
                                        data-email="{{ $lead->email }}"
                                        data-name="{{ $lead->Name }}"
                                        style="font-size: 11.5px;">
                                    Select
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4 small">
                            <i class="bi bi-envelope-x fs-3 d-block mb-1"></i>
                            No leads with email addresses found yet.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-light p-4">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Sending Guidelines
                </h6>
                <ul class="small text-secondary mb-0 ps-3 d-flex flex-column gap-1">
                    <li>Use <strong>Quick Templates</strong> to insert clean, formatted greeting copy in one click.</li>
                    <li>Attach up to multiple <strong>JPG / PNG</strong> images (max <strong>2 MB</strong> per image).</li>
                    <li>Recipient emails come directly from your Sales Leads database or can be typed manually.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.sales-greetings-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.header-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.client-avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #eef2ff;
    color: #4f46e5;
    font-weight: 700;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.extra-small {
    font-size: 11.5px;
}

.lead-email-item {
    background: #ffffff;
    transition: all 0.2s ease;
}

.lead-email-item:hover {
    background: #f8fafc;
    border-color: #cbd5e1 !important;
}

.ck-editor__editable_inline {
    min-height: 200px;
}

.attachment-thumb {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let greetingEditorInstance = null;
    let selectedClientName = 'Valued Customer';

    ClassicEditor
        .create(document.querySelector('#greetingEditor'))
        .then(editor => {
            greetingEditorInstance = editor;
        })
        .catch(error => {
            console.error('CKEditor init error:', error);
        });

    const templates = {
        enquiry: (name) => `<p>Dear <strong>${name}</strong>,</p><p>Thank you for reaching out to <strong>Amudha Decors</strong>. We truly appreciate your interest in our roofing and tensile solutions.</p><p>Our team is reviewing your requirements and will connect with you shortly with the best options tailored to your site.</p><p>Warm regards,<br><strong>Amudha Decors Sales Team</strong></p>`,
        followup: (name) => `<p>Dear <strong>${name}</strong>,</p><p>Greetings from <strong>Amudha Decors</strong>! Following up on the quotation shared with you, please let us know if you need any clarifications, design changes, or a site measurement visit.</p><p>We look forward to working together on your project.</p><p>Warm regards,<br><strong>Amudha Decors Sales Team</strong></p>`,
        festive: (name) => `<p>Dear <strong>${name}</strong>,</p><p>Wishing you and your family joy, prosperity, and wonderful moments on this special occasion!</p><p>Thank you for being a valued part of the <strong>Amudha Decors</strong> family.</p><p>Warm regards,<br><strong>Amudha Decors</strong></p>`,
        sitevisit: (name) => `<p>Dear <strong>${name}</strong>,</p><p>Greetings from <strong>Amudha Decors</strong>! We would be happy to schedule an on-site visit and technical measurement at your convenience.</p><p>Please reply to this email or call us to confirm your preferred date and time.</p><p>Best regards,<br><strong>Amudha Decors Sales Team</strong></p>`
    };

    const subjectDefaults = {
        enquiry: 'Thank You for Your Enquiry - Amudha Decors',
        followup: 'Following Up on Your Quotation - Amudha Decors',
        festive: 'Warm Festive Wishes from Amudha Decors',
        sitevisit: 'Site Visit & Measurement Schedule - Amudha Decors'
    };

    document.querySelectorAll('.quick-tpl-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const key = this.getAttribute('data-tpl');
            const html = templates[key] ? templates[key](selectedClientName) : '';
            if (greetingEditorInstance) {
                greetingEditorInstance.setData(html);
            } else {
                document.getElementById('greetingEditor').value = html;
            }
            if (subjectDefaults[key]) {
                document.getElementById('mailSubject').value = subjectDefaults[key];
            }
            document.getElementById('messageRequiredHint').classList.add('d-none');
        });
    });

    // Select Lead Email
    document.querySelectorAll('.select-lead-email').forEach(btn => {
        btn.addEventListener('click', function () {
            const email = this.getAttribute('data-email');
            const name = this.getAttribute('data-name') || 'Valued Customer';
            document.getElementById('emailaddress').value = email;
            selectedClientName = name;
        });
    });

    // Filter Lead List
    const leadSearch = document.getElementById('leadEmailSearch');
    if (leadSearch) {
        leadSearch.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.lead-email-item').forEach(item => {
                const hay = item.getAttribute('data-search') || '';
                item.style.display = (!q || hay.includes(q)) ? 'flex' : 'none';
            });
        });
    }

    // Attachment Image Preview
    const attachInput = document.getElementById('attachments');
    const previewContainer = document.getElementById('attachmentPreview');
    if (attachInput && previewContainer) {
        attachInput.addEventListener('change', function () {
            previewContainer.innerHTML = '';
            Array.from(this.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'attachment-thumb shadow-xs';
                        img.title = file.name;
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Sync CKEditor & validate before submit
    const form = document.getElementById('greetingForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (greetingEditorInstance) {
                const data = greetingEditorInstance.getData().trim();
                document.getElementById('greetingEditor').value = data;
                if (!data) {
                    e.preventDefault();
                    document.getElementById('messageRequiredHint').classList.remove('d-none');
                    return;
                }
            }
            const btn = document.getElementById('sendGreetingBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
            }
        });
    }

    const resetBtn = document.getElementById('resetGreetingBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (greetingEditorInstance) {
                greetingEditorInstance.setData('');
            }
            if (previewContainer) {
                previewContainer.innerHTML = '';
            }
            selectedClientName = 'Valued Customer';
            document.getElementById('messageRequiredHint').classList.add('d-none');
        });
    }
});
</script>
@endpush
