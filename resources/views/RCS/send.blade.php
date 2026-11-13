@extends('layouts.adminbase')

@section('title', 'RCS Campaign')

@section('content')
<div class="rcs-campaign-wrapper py-2">

    <!-- Top Navigation / Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-light rounded-circle shadow-sm border p-0 d-flex align-items-center justify-content-center" 
                    style="width: 36px; height: 36px;" onclick="window.history.back()" title="Back">
                <i class="bi bi-arrow-left text-secondary fs-6"></i>
            </button>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 20px;">RCS Campaign</h5>
                    <span class="badge rounded-pill fw-semibold" 
                          style="background-color: #d1fae5; color: #059669; font-size: 10px; letter-spacing: 0.5px; padding: 4px 10px;">
                        RCS MESSAGE
                    </span>
                </div>
                <div class="d-flex align-items-center gap-1 text-muted small mt-1" style="font-size: 12px;">
                    <i class="bi bi-diagram-3-fill text-secondary"></i>
                    <span>All Channel</span>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 9px;"></i>
                    <span>RCS Message</span>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 9px;"></i>
                    <span class="text-primary fw-medium">RCS Campaign</span>
                </div>
            </div>
        </div>

        <div class="text-end text-nowrap small d-none d-md-block">
            <span class="text-muted">: Update</span> 
            <i class="bi bi-bell-fill text-success ms-1"></i> 
            <span style="color: #10b981; font-weight: 500;">Technology is best when it brings people together</span> 
            <span class="text-muted ms-1">|</span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center mb-3" role="alert">
        <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
        <div>{{ $errors->first() }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <form method="POST" action="{{ route('rcs.send') }}" id="rcsCampaignForm">
            @csrf

            <!-- Row 1: Campaign Name & Agent -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-secondary fw-semibold small mb-1">Campaign Name</label>
                    <input type="text" name="campaign_name" id="campaignName" class="form-control rounded-3 py-2 px-3" 
                           value="{{ now()->format('m/d/Y h:i:s A') }}" required style="border-color: #d1d5db; font-size: 14px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary fw-semibold small mb-1">Agent</label>
                    <select name="agent" id="agentSelect" class="form-select rounded-3 py-2 px-3" style="border-color: #d1d5db; font-size: 14px;">
                        <option value="Amudha Decors (696f30bdb75b5a2355706225) - Promotional" selected>
                            Amudha Decors (696f30bdb75b5a2355706225) - Promotional
                        </option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Template Type & Select Template with Action Buttons -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-secondary fw-semibold small mb-1">Template Type</label>
                    <div class="position-relative">
                        <select name="template_type" id="templateType" class="form-select rounded-3 py-2 px-3 template-type-select" 
                                style="border: 1.5px solid #10b981; font-size: 14px; box-shadow: 0 0 0 1px rgba(16, 185, 129, 0.25);">
                            <option value="">Select Template</option>
                            <option value="Rich Card" selected>Rich Card</option>
                            <option value="Carousel">Carousel</option>
                            <option value="Text Message">Text Message</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-secondary fw-semibold small mb-1">Select Template</label>
                    <select name="template" id="templateSelect" class="form-select rounded-3 py-2 px-3" required 
                            style="border-color: #d1d5db; font-size: 14px;">
                        <option value="">Select Template</option>
                        <option value="Republic_2026" data-type="Rich Card">Republic_2026</option>
                        <option value="Welcome_MSG" data-type="Text Message">Welcome_MSG</option>
                        <option value="Tensile" data-type="Rich Card">Tensile</option>
                        <option value="Products" data-type="Carousel">Products</option>
                        <option value="Ind_2026" data-type="Rich Card">Ind_2026</option>
                        <option value="AyuthaPooja2026" data-type="Rich Card">AyuthaPooja2026</option>
                        <option value="Welcome__Message" data-type="Text Message">Welcome__Message</option>
                    </select>

                    <!-- Upload Files & Groups Buttons below template select -->
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success rounded-3 px-3 py-1 fw-medium d-inline-flex align-items-center gap-1 action-pill-btn" 
                                id="btnUploadFiles" style="border-color: #10b981; color: #10b981; font-size: 13px;">
                            <i class="bi bi-upload"></i> Upload Files
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1 fw-medium d-inline-flex align-items-center gap-1 action-pill-btn" 
                                id="btnOpenGroups" style="border-color: #3b82f6; color: #3b82f6; font-size: 13px;" 
                                data-bs-toggle="modal" data-bs-target="#groupsModal">
                            <i class="bi bi-people-fill"></i> Groups
                        </button>
                        <input type="file" id="bulkFileInput" accept=".csv,.txt" style="display: none;">
                    </div>
                </div>
            </div>

            <!-- Row 3: Segmented Tab Bar (Header | Body | Buttons) -->
            <div class="mb-3">
                <div class="segmented-control-bar rounded-3 border overflow-hidden text-center" style="border-color: #e5e7eb; background: #fff;">
                    <div class="row g-0">
                        <div class="col-4 py-2 border-end segment-tab-item active" data-target="tab-header" role="button">
                            <span class="fw-semibold">Header</span>
                        </div>
                        <div class="col-4 py-2 border-end segment-tab-item" data-target="tab-body" role="button">
                            <span class="fw-semibold">Body</span>
                        </div>
                        <div class="col-4 py-2 segment-tab-item" data-target="tab-buttons" role="button">
                            <span class="fw-semibold">Buttons</span>
                        </div>
                    </div>
                </div>

                <!-- Tab Preview Panes -->
                <div class="tab-content-container mt-2 p-3 rounded-3 border bg-light-subtle" id="tabContentPreview" style="border-color: #f1f5f9; display: none;">
                    <div class="tab-pane-item" id="pane-header">
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-image text-primary"></i>
                            <strong>Header:</strong> <span id="headerPreviewText">Banner Media / Title included with template</span>
                        </div>
                    </div>
                    <div class="tab-pane-item" id="pane-body" style="display: none;">
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-chat-text text-primary"></i>
                            <strong>Body:</strong> <span id="bodyPreviewText">Message body text will be populated from the selected template.</span>
                        </div>
                    </div>
                    <div class="tab-pane-item" id="pane-buttons" style="display: none;">
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-hand-index-thumb text-primary"></i>
                            <strong>Buttons:</strong> <span id="buttonsPreviewText">Interactive suggested actions & quick replies.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: Numbers (0) ( Paste up to 5,000 numbers only. ) -->
            <div class="mb-3">
                <label class="form-label text-dark fw-semibold small mb-1">
                    Numbers (<span id="numberCounter">0</span>) 
                    <span class="text-muted fw-normal" style="font-size: 13px;">( Paste up to 5,000 numbers only. )</span>
                </label>
                <textarea name="numbers" id="numbersTextarea" class="form-control rounded-3 p-3" rows="4" 
                          placeholder="Enter number in comma separated, e.g. 919999999999,918888888888" 
                          style="border-color: #d1d5db; font-size: 14px; resize: vertical;" required></textarea>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-muted" id="numberInfoText">Paste comma, space, or newline separated mobile numbers.</small>
                    <small class="text-danger fw-medium" id="numberErrorText" style="display: none;"></small>
                </div>
            </div>

            <!-- Row 5: Switches (Remove Duplicate, Remove BlackList, Schedule Now) -->
            <div class="d-flex flex-wrap align-items-center gap-4 my-3 py-1">
                <div class="form-check form-switch d-inline-flex align-items-center gap-2 mb-0">
                    <input class="form-check-input custom-emerald-switch" type="checkbox" name="remove_duplicate" id="removeDuplicateSwitch" checked value="1" style="cursor: pointer;">
                    <label class="form-check-label text-secondary fw-semibold small mb-0" for="removeDuplicateSwitch" style="cursor: pointer;">
                        Remove Duplicate
                    </label>
                </div>

                <div class="form-check form-switch d-inline-flex align-items-center gap-2 mb-0">
                    <input class="form-check-input custom-emerald-switch" type="checkbox" name="remove_blacklist" id="removeBlacklistSwitch" checked value="1" style="cursor: pointer;">
                    <label class="form-check-label text-secondary fw-semibold small mb-0" for="removeBlacklistSwitch" style="cursor: pointer;">
                        Remove BlackList
                    </label>
                </div>

                <div class="form-check form-switch d-inline-flex align-items-center gap-2 mb-0">
                    <input class="form-check-input custom-emerald-switch" type="checkbox" name="schedule_now" id="scheduleNowSwitch" value="1" style="cursor: pointer;">
                    <label class="form-check-label text-secondary fw-semibold small mb-0" for="scheduleNowSwitch" style="cursor: pointer;">
                        Schedule Now
                    </label>
                </div>
            </div>

            <!-- Schedule Date/Time Picker (Hidden by default) -->
            <div id="scheduleContainer" class="mb-3 p-3 rounded-3 border bg-light" style="display: none; max-width: 360px;">
                <label class="form-label text-secondary fw-semibold small mb-1">Select Schedule Date & Time</label>
                <input type="datetime-local" name="schedule_time" id="scheduleTime" class="form-control rounded-3 py-2 px-3" style="font-size: 14px;">
            </div>

            <!-- Row 6: Bottom Action Bar -->
            <div class="d-flex justify-content-between align-items-center pt-2 mt-4 border-top" style="border-color: #f1f5f9;">
                <div>
                    <button type="button" class="btn btn-outline-primary rounded-3 px-3 py-2 d-inline-flex align-items-center gap-2 failed-over-btn" 
                            id="btnFailedOver" data-bs-toggle="modal" data-bs-target="#failedOverModal" 
                            style="border-color: #93c5fd; color: #2563eb; background: #fff; font-size: 14px; font-weight: 500;">
                        <i class="bi bi-list-task"></i> Failed Over
                    </button>
                </div>

                <div>
                    <button type="submit" class="btn text-white rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm send-now-btn" 
                            id="btnSendNow" style="background-color: #059669; border: none; font-size: 14px; font-weight: 500; letter-spacing: 0.3px;">
                        <i class="bi bi-send-fill" style="transform: rotate(45deg); display: inline-block;"></i> Send Now
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Modal: Select Groups -->
<div class="modal fade" id="groupsModal" tabindex="-1" aria-labelledby="groupsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark" id="groupsModalLabel">
                    <i class="bi bi-people-fill text-primary me-1"></i> Import Numbers from Groups
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="text-muted small mb-3">Select the contact groups you want to add to this RCS campaign.</p>

                <div class="list-group rounded-3 border">
                    <label class="list-group-item d-flex align-items-center justify-content-between p-3" style="cursor: pointer;">
                        <div class="d-flex align-items-center gap-3">
                            <input class="form-check-input m-0 group-check" type="checkbox" id="checkCustomers" value="customers">
                            <div>
                                <div class="fw-semibold text-dark">All Customers</div>
                                <small class="text-muted">Verified customer contacts</small>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            {{ count($customerMobiles ?? []) }} numbers
                        </span>
                    </label>

                    <label class="list-group-item d-flex align-items-center justify-content-between p-3" style="cursor: pointer;">
                        <div class="d-flex align-items-center gap-3">
                            <input class="form-check-input m-0 group-check" type="checkbox" id="checkLeads" value="leads">
                            <div>
                                <div class="fw-semibold text-dark">All Leads</div>
                                <small class="text-muted">Potential leads and enquiries</small>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                            {{ count($leadMobiles ?? []) }} numbers
                        </span>
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-3 py-2 small" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-3 px-4 py-2 small" id="btnApplyGroupNumbers">
                    <i class="bi bi-plus-circle me-1"></i> Add to Campaign
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Failed Over Information -->
<div class="modal fade" id="failedOverModal" tabindex="-1" aria-labelledby="failedOverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark" id="failedOverModalLabel">
                    <i class="bi bi-shield-check text-info me-1"></i> Failed Over Settings
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="alert alert-info border-0 rounded-3 small mb-3">
                    <i class="bi bi-info-circle me-1"></i> Fallback channel automatically delivers your message if RCS is unreachable or device is unsupported.
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold small mb-1">Fallback Channel</label>
                    <select class="form-select rounded-3" id="fallbackChannel">
                        <option value="sms" selected>SMS Fallback (Standard)</option>
                        <option value="whatsapp">WhatsApp Fallback</option>
                        <option value="none">No Fallback</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label text-secondary fw-semibold small mb-1">Wait Time before Failover</label>
                    <select class="form-select rounded-3" id="failoverTimeout">
                        <option value="300" selected>5 Minutes</option>
                        <option value="600">10 Minutes</option>
                        <option value="1800">30 Minutes</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary rounded-3 px-4 py-2 small" data-bs-dismiss="modal">Save Preferences</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styling matching the Screenshot */
.rcs-campaign-wrapper {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.template-type-select:focus {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25) !important;
}

.custom-emerald-switch:checked {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
}

.custom-emerald-switch {
    width: 2.5em !important;
    height: 1.3em !important;
    cursor: pointer;
}

.action-pill-btn:hover {
    filter: brightness(0.95);
}

.segmented-control-bar {
    background-color: #f8fafc;
}

.segment-tab-item {
    cursor: pointer;
    color: #4b5563;
    transition: all 0.2s ease;
    user-select: none;
}

.segment-tab-item:hover {
    background-color: #f1f5f9;
    color: #1f2937;
}

.segment-tab-item.active {
    background-color: #eff6ff;
    color: #2563eb;
    font-weight: 600;
}

.send-now-btn:hover {
    background-color: #047857 !important;
}

.failed-over-btn:hover {
    background-color: #eff6ff !important;
    border-color: #60a5fa !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const numbersInput = document.getElementById('numbersTextarea');
    const numberCounter = document.getElementById('numberCounter');
    const removeDuplicateSwitch = document.getElementById('removeDuplicateSwitch');
    const scheduleNowSwitch = document.getElementById('scheduleNowSwitch');
    const scheduleContainer = document.getElementById('scheduleContainer');
    const templateTypeSelect = document.getElementById('templateType');
    const templateSelect = document.getElementById('templateSelect');
    const btnUploadFiles = document.getElementById('btnUploadFiles');
    const bulkFileInput = document.getElementById('bulkFileInput');
    const tabItems = document.querySelectorAll('.segment-tab-item');
    const tabContentPreview = document.getElementById('tabContentPreview');
    const paneHeader = document.getElementById('pane-header');
    const paneBody = document.getElementById('pane-body');
    const paneButtons = document.getElementById('pane-buttons');
    const headerPreviewText = document.getElementById('headerPreviewText');
    const bodyPreviewText = document.getElementById('bodyPreviewText');
    const buttonsPreviewText = document.getElementById('buttonsPreviewText');

    // Group numbers passed from backend
    const customerMobiles = @json($customerMobiles ?? []);
    const leadMobiles = @json($leadMobiles ?? []);

    // Template database metadata for preview
    const templateMetadata = {
        'Republic_2026': {
            type: 'Rich Card',
            header: 'Republic Day Special Offer Banner Image',
            body: 'Wishing you a Happy Republic Day! Enjoy exclusive discounts on tensile and decor solutions from Amudha Decors.',
            buttons: '1. Visit Website | 2. Call Now'
        },
        'Welcome_MSG': {
            type: 'Text Message',
            header: 'No Media Header (Text Only)',
            body: 'Welcome to Amudha Decors! We are delighted to have you. Contact us for custom shades, interior decor and tensile works.',
            buttons: '1. Reply YES | 2. Contact Support'
        },
        'Tensile': {
            type: 'Rich Card',
            header: 'Tensile Architecture Portfolio Image',
            body: 'Upgrade your outdoor space with durable, stylish tensile membrane structures engineered by Amudha Decors.',
            buttons: '1. View Catalog | 2. Get Free Quote'
        },
        'Products': {
            type: 'Carousel',
            header: 'Product Carousel (Multi-Card Slide)',
            body: 'Explore our latest interior decor products, tensile shades, canopies and premium fittings.',
            buttons: '1. Shop Products | 2. Request Callback'
        },
        'Ind_2026': {
            type: 'Rich Card',
            header: 'Independence Day Promotional Banner',
            body: 'Celebrate with exciting festive offers on all Amudha Decors installations. Book your site visit today!',
            buttons: '1. Claim Offer | 2. Talk to Expert'
        },
        'AyuthaPooja2026': {
            type: 'Rich Card',
            header: 'Ayutha Pooja Celebration Card',
            body: 'Warm Ayutha Pooja wishes from Amudha Decors! May this festive season bring success and joy to your business.',
            buttons: '1. Special Offers | 2. Our Services'
        },
        'Welcome__Message': {
            type: 'Text Message',
            header: 'No Media Header (Text Only)',
            body: 'Hello! Thank you for connecting with Amudha Decors. How can we help you today with your estimate?',
            buttons: '1. Chat with Us | 2. Call Us'
        }
    };

    // Parse and count numbers
    function parseNumbers(text) {
        if (!text) return [];
        const rawItems = text.split(/[\s,;\n\r]+/);
        const valid = [];
        for (let item of rawItems) {
            let cleaned = item.replace(/\D/g, '');
            if (cleaned.length === 10) {
                cleaned = '91' + cleaned;
            } else if (cleaned.length === 11 && cleaned.startsWith('0')) {
                cleaned = '91' + cleaned.substring(1);
            }
            if (cleaned.length === 12 && cleaned.startsWith('91')) {
                valid.push(cleaned);
            }
        }
        return valid;
    }

    function updateNumberCount() {
        const text = numbersInput.value;
        let numbers = parseNumbers(text);
        if (removeDuplicateSwitch.checked) {
            numbers = [...new Set(numbers)];
        }
        numberCounter.textContent = numbers.length;
    }

    numbersInput.addEventListener('input', updateNumberCount);

    // Toggle Remove Duplicate
    removeDuplicateSwitch.addEventListener('change', function () {
        if (this.checked) {
            const raw = numbersInput.value;
            if (raw.trim()) {
                const uniqueNumbers = [...new Set(parseNumbers(raw))];
                numbersInput.value = uniqueNumbers.join(', ');
            }
        }
        updateNumberCount();
    });

    // Schedule Now Toggle
    scheduleNowSwitch.addEventListener('change', function () {
        if (this.checked) {
            scheduleContainer.style.display = 'block';
        } else {
            scheduleContainer.style.display = 'none';
        }
    });

    // Template Type filter
    templateTypeSelect.addEventListener('change', function () {
        const selectedType = this.value;
        const options = templateSelect.querySelectorAll('option');

        let firstMatch = '';

        options.forEach(opt => {
            if (!opt.value) {
                opt.style.display = '';
                return;
            }
            const itemType = opt.getAttribute('data-type');
            if (!selectedType || itemType === selectedType) {
                opt.style.display = '';
                if (!firstMatch) firstMatch = opt.value;
            } else {
                opt.style.display = 'none';
            }
        });

        // If current selection is hidden, select first match
        const currentOpt = templateSelect.querySelector(`option[value="${templateSelect.value}"]`);
        if (currentOpt && currentOpt.style.display === 'none') {
            templateSelect.value = firstMatch || '';
        }
        updateTemplatePreview();
    });

    // Template Selection Preview
    function updateTemplatePreview() {
        const tplName = templateSelect.value;
        if (tplName && templateMetadata[tplName]) {
            const meta = templateMetadata[tplName];
            headerPreviewText.textContent = meta.header;
            bodyPreviewText.textContent = meta.body;
            buttonsPreviewText.textContent = meta.buttons;
            tabContentPreview.style.display = 'block';

            // Also synchronize template type if not matching
            if (templateTypeSelect.value !== meta.type) {
                templateTypeSelect.value = meta.type;
            }
        }
    }

    templateSelect.addEventListener('change', updateTemplatePreview);

    // Segmented tab clicks
    tabItems.forEach(tab => {
        tab.addEventListener('click', function () {
            tabItems.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const target = this.getAttribute('data-target');
            tabContentPreview.style.display = 'block';

            paneHeader.style.display = (target === 'tab-header') ? 'block' : 'none';
            paneBody.style.display = (target === 'tab-body') ? 'block' : 'none';
            paneButtons.style.display = (target === 'tab-buttons') ? 'block' : 'none';
        });
    });

    // File Upload Handler
    btnUploadFiles.addEventListener('click', () => bulkFileInput.click());

    bulkFileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            const content = event.target.result;
            const extracted = parseNumbers(content);
            if (extracted.length === 0) {
                alert('No valid 10-digit mobile numbers found in this file.');
                return;
            }

            const existing = numbersInput.value.trim();
            const delimiter = existing ? ', ' : '';
            numbersInput.value = existing + delimiter + extracted.join(', ');

            if (removeDuplicateSwitch.checked) {
                const uniqueNumbers = [...new Set(parseNumbers(numbersInput.value))];
                numbersInput.value = uniqueNumbers.join(', ');
            }

            updateNumberCount();
            alert(`Successfully imported ${extracted.length} numbers from file!`);
        };
        reader.readAsText(file);
    });

    // Groups Modal Handler
    document.getElementById('btnApplyGroupNumbers').addEventListener('click', function () {
        const checkCust = document.getElementById('checkCustomers').checked;
        const checkLeads = document.getElementById('checkLeads').checked;

        let numbersToAdd = [];
        if (checkCust && Array.isArray(customerMobiles)) {
            numbersToAdd = numbersToAdd.concat(customerMobiles);
        }
        if (checkLeads && Array.isArray(leadMobiles)) {
            numbersToAdd = numbersToAdd.concat(leadMobiles);
        }

        const validNumbers = parseNumbers(numbersToAdd.join(', '));
        if (validNumbers.length === 0) {
            alert('Please select at least one group with contacts.');
            return;
        }

        const existing = numbersInput.value.trim();
        const delimiter = existing ? ', ' : '';
        numbersInput.value = existing + delimiter + validNumbers.join(', ');

        if (removeDuplicateSwitch.checked) {
            const uniqueNumbers = [...new Set(parseNumbers(numbersInput.value))];
            numbersInput.value = uniqueNumbers.join(', ');
        }

        updateNumberCount();

        // Close modal
        const modalEl = document.getElementById('groupsModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    });

    // Initial check
    updateNumberCount();
    updateTemplatePreview();
});
</script>
@endsection
