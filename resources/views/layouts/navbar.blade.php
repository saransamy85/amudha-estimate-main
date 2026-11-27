@php
    $navUser = Auth::user();
    $myTodayAtt = null;
    $myMonthDays = 0;
    if ($navUser) {
        $todayDateStr = \Carbon\Carbon::today('Asia/Kolkata')->toDateString();
        $myTodayAtt = \App\Models\Attendance::where('user_id', $navUser->id)
            ->whereDate('attendance_date', $todayDateStr)
            ->first();
        $myMonthDays = \App\Models\Attendance::where('user_id', $navUser->id)
            ->whereMonth('attendance_date', \Carbon\Carbon::now('Asia/Kolkata')->month)
            ->whereYear('attendance_date', \Carbon\Carbon::now('Asia/Kolkata')->year)
            ->whereIn('status', ['Present', 'Late', 'Half Day', 'On Duty'])
            ->count();
    }
@endphp

<nav class="topbar d-flex align-items-center justify-content-between px-3 py-2 bg-white border-bottom">
    <div class="d-flex align-items-center gap-2">
        <h5 class="mb-0 fw-bold text-dark fs-6">@yield('title', 'Dashboard')</h5>
    </div>

    <div class="topbar-right d-flex align-items-center gap-2">

        {{-- Individual User Self Attendance Pill / Punch Button (Requires Location) --}}
        @if($navUser)
            @if($myTodayAtt && $myTodayAtt->login_time)
                <div class="d-flex align-items-center gap-2 me-2">
                    <div class="attendance-pill d-flex align-items-center gap-1.5 px-3 py-1.5 bg-success-subtle text-success border border-success-subtle rounded-pill small">
                        <span class="nav-pulse-dot"></span>
                        <span class="fw-semibold font-monospace" style="font-size: 0.78rem;">
                            In: {{ $myTodayAtt->formatted_login_time }}
                        </span>
                        @if($myTodayAtt->logout_time)
                            <span class="text-secondary font-monospace ms-1" style="font-size: 0.74rem;">
                                &bull; Out: {{ $myTodayAtt->formatted_logout_time }}
                            </span>
                        @endif
                    </div>

                    @if(!$myTodayAtt->logout_time)
                        <form id="topbarCheckOutForm" action="{{ route('attendance.checkout') }}" method="POST" class="d-inline m-0">
                            @csrf
                            <input type="hidden" name="latitude" id="topbarCheckOutLat">
                            <input type="hidden" name="longitude" id="topbarCheckOutLng">
                            <input type="hidden" name="address" id="topbarCheckOutAddress">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 d-flex align-items-center gap-1 fw-semibold shadow-2xs" style="font-size: 0.75rem;" onclick="requestGeoAttendance(this, 'topbarCheckOutForm', 'topbarCheckOutLat', 'topbarCheckOutLng', 'topbarCheckOutAddress', 'Check Out')" title="Manual Check Out (Requires GPS verification)">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Check Out</span>
                            </button>
                        </form>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2.5 py-1 small d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <i class="bi bi-check2-circle text-success"></i> Checked Out
                        </span>
                    @endif
                </div>
            @else
                <form id="topbarCheckInForm" action="{{ route('attendance.checkin') }}" method="POST" class="d-inline me-2 m-0">
                    @csrf
                    <input type="hidden" name="latitude" id="topbarCheckInLat">
                    <input type="hidden" name="longitude" id="topbarCheckInLng">
                    <input type="hidden" name="address" id="topbarCheckInAddress">
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 d-flex align-items-center gap-1.5 shadow-sm fw-semibold" style="font-size: 0.78rem;" onclick="requestGeoAttendance(this, 'topbarCheckInForm', 'topbarCheckInLat', 'topbarCheckInLng', 'topbarCheckInAddress', 'Check In')" title="Click to verify GPS location and Check In">
                        <i class="bi bi-geo-alt-fill fs-6"></i>
                        <span>Check In</span>
                    </button>
                </form>
            @endif
        @endif

        {{-- Notification Bell --}}
        <a href="#" class="btn btn-sm btn-light border-0 rounded-circle text-muted p-2 me-1" title="Notifications">
            <i class="bi bi-bell fs-6"></i>
        </a>

        {{-- User Dropdown Menu --}}
        <div class="dropdown">
            <a href="#"
               class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle px-2 py-1 rounded-3 hover-bg-light"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                <div class="user-avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; font-size: 0.75rem;">
                    {{ strtoupper(substr($navUser->name ?? session('username') ?? 'U', 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start lh-1">
                    <span class="fw-semibold small d-block">{{ $navUser->name ?? session('username') }}</span>
                    <small class="text-muted" style="font-size: 0.68rem;">{{ $navUser->Role ?? 'Staff' }}</small>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" style="min-width: 210px;">
                <li class="px-3 py-2 border-bottom bg-light rounded-top-3">
                    <div class="fw-bold small text-dark">{{ $navUser->name ?? session('username') }}</div>
                    <div class="small text-muted" style="font-size: 0.75rem;">{{ $navUser->email ?? '' }}</div>
                    <span class="badge bg-secondary-subtle text-secondary border mt-1" style="font-size: 0.65rem;">Role: {{ $navUser->Role ?? 'Staff' }}</span>
                </li>

                {{-- Attendance Dashboard: ONLY visible to Admin and Accounts --}}
                @if($navUser && in_array($navUser->Role, ['Admin', 'Accounts']))
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-dark" href="{{ route('accounts.attendance') }}">
                            <i class="bi bi-calendar2-check-fill text-success"></i>
                            <span>Attendance Dashboard</span>
                        </a>
                    </li>
                @endif

                {{-- My Personal Attendance: Available to ALL users (Sales, Reception, Purchase, etc.) --}}
                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-dark" href="#" data-bs-toggle="modal" data-bs-target="#myPersonalAttendanceModal">
                        <i class="bi bi-person-check text-primary"></i>
                        <span>My Attendance</span>
                    </a>
                </li>

                <li><hr class="dropdown-divider my-1"></li>

                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" href="{{ route('logout') }}">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

{{-- Individual User Personal Attendance Modal --}}
@if($navUser)
<div class="modal fade" id="myPersonalAttendanceModal" tabindex="-1" aria-labelledby="myPersonalAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                        <i class="bi bi-geo-alt-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="myPersonalAttendanceLabel">My Attendance</h5>
                        <small class="text-muted">{{ $navUser->name }} &bull; {{ \Carbon\Carbon::now('Asia/Kolkata')->format('l, d M Y') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Today's Status Banner --}}
                <div class="p-3 rounded-4 mb-3 {{ $myTodayAtt && $myTodayAtt->login_time ? 'bg-success-subtle border border-success-subtle' : 'bg-warning-subtle border border-warning-subtle' }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small text-uppercase fw-bold text-secondary d-block" style="font-size: 0.7rem;">Today's Attendance</span>
                            <h6 class="mb-0 fw-bold {{ $myTodayAtt && $myTodayAtt->login_time ? 'text-success' : 'text-warning-emphasis' }}">
                                @if($myTodayAtt && $myTodayAtt->login_time)
                                    <i class="bi bi-check-circle-fill me-1"></i> Marked Present ({{ $myTodayAtt->status }})
                                @else
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Not Yet Marked Today
                                @endif
                            </h6>
                        </div>
                        <div>
                            @if($myTodayAtt && $myTodayAtt->login_time)
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace">
                                    {{ $myTodayAtt->duration }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1">
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- In / Out Timestamps --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border text-center">
                            <span class="small text-muted d-block text-uppercase" style="font-size: 0.68rem;">First In (Check-In)</span>
                            <span class="fs-6 fw-bold text-dark font-monospace">
                                {{ $myTodayAtt && $myTodayAtt->login_time ? $myTodayAtt->formatted_login_time : '—' }}
                            </span>
                            @if($myTodayAtt && $myTodayAtt->checkin_latitude)
                                <div class="mt-1">
                                    <a href="{{ $myTodayAtt->google_maps_checkin_url }}" target="_blank" class="badge bg-primary-subtle text-primary border text-decoration-none" style="font-size: 0.65rem;">
                                        <i class="bi bi-geo-alt-fill"></i> GPS Verified
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border text-center">
                            <span class="small text-muted d-block text-uppercase" style="font-size: 0.68rem;">Last Out (Punch-Out)</span>
                            <span class="fs-6 fw-bold text-dark font-monospace">
                                {{ $myTodayAtt && $myTodayAtt->logout_time ? $myTodayAtt->formatted_logout_time : '—' }}
                            </span>
                            @if($myTodayAtt && $myTodayAtt->checkout_latitude)
                                <div class="mt-1">
                                    <a href="{{ $myTodayAtt->google_maps_checkout_url }}" target="_blank" class="badge bg-danger-subtle text-danger border text-decoration-none" style="font-size: 0.65rem;">
                                        <i class="bi bi-geo-alt-fill"></i> GPS Verified
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Monthly Summary --}}
                <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-check text-primary fs-5"></i>
                        <div>
                            <span class="small fw-semibold text-dark d-block">This Month ({{ \Carbon\Carbon::now('Asia/Kolkata')->format('F Y') }})</span>
                            <span class="text-muted small" style="font-size: 0.7rem;">Total working days present</span>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill font-monospace fw-bold fs-6 px-3 py-1">
                        {{ $myMonthDays }} days
                    </span>
                </div>

                {{-- Punch Action Buttons (With Location Enforcement) --}}
                <div class="d-grid gap-2">
                    @if(!$myTodayAtt || !$myTodayAtt->login_time)
                        <form id="modalCheckInForm" action="{{ route('attendance.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="latitude" id="modalCheckInLat">
                            <input type="hidden" name="longitude" id="modalCheckInLng">
                            <input type="hidden" name="address" id="modalCheckInAddress">
                            <button type="button" class="btn btn-success w-100 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm" onclick="requestGeoAttendance(this, 'modalCheckInForm', 'modalCheckInLat', 'modalCheckInLng', 'modalCheckInAddress', 'Check In')">
                                <i class="bi bi-geo-alt-fill fs-5"></i>
                                <span>Verify Location & Check In</span>
                            </button>
                        </form>
                        <small class="text-muted text-center d-block" style="font-size: 0.72rem;">
                            <i class="bi bi-shield-check me-1 text-success"></i>You will be prompted for location permission before marking attendance.
                        </small>
                    @elseif(!$myTodayAtt->logout_time)
                        <form id="modalCheckOutForm" action="{{ route('attendance.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="latitude" id="modalCheckOutLat">
                            <input type="hidden" name="longitude" id="modalCheckOutLng">
                            <input type="hidden" name="address" id="modalCheckOutAddress">
                            <button type="button" class="btn btn-danger w-100 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm" onclick="requestGeoAttendance(this, 'modalCheckOutForm', 'modalCheckOutLat', 'modalCheckOutLng', 'modalCheckOutAddress', 'Check Out')">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                                <span>Verify Location & Check Out</span>
                            </button>
                        </form>
                        <small class="text-muted text-center d-block" style="font-size: 0.72rem;">
                            <i class="bi bi-info-circle me-1"></i>Logging out of CRM does not check you out. Click Check Out to finish your workday.
                        </small>
                    @else
                        <div class="p-3 bg-success-subtle border border-success-subtle rounded-3 text-center">
                            <span class="text-success fw-bold small d-block mb-1">
                                <i class="bi bi-check2-all me-1"></i> You have completed check-in & check-out for today.
                            </span>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                Check-out: {{ $myTodayAtt->formatted_logout_time }} &bull; Total worked: {{ $myTodayAtt->duration }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Location Permission Required Notice Modal --}}
<div class="modal fade" id="locationRequiredNoticeModal" tabindex="-1" aria-labelledby="locationRequiredNoticeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-danger-subtle p-4 pb-2">
                <div class="d-flex align-items-center gap-2 text-danger">
                    <div class="p-2 rounded-3 bg-danger text-white">
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0 text-danger" id="locationNoticeTitle">Location Permission Required</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <p class="text-secondary small mt-2 mb-3" id="locationNoticeBody">
                    To mark your attendance, this system requires GPS location verification to confirm your physical presence. Your attendance <strong>cannot be marked</strong> without granting location access.
                </p>

                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="fw-semibold text-dark small mb-2">How to allow location access:</div>
                    <ol class="small text-muted ps-3 mb-0" style="line-height: 1.6;">
                        <li>Click the <strong>Tune / Lock / Permissions icon (🔒)</strong> in your browser address bar (left of the URL).</li>
                        <li>Find <strong>Location</strong> and switch it from <em>Block</em> to <strong>Allow</strong>.</li>
                        <li>Refresh the page or click <strong>Check In</strong> again.</li>
                    </ol>
                </div>

                <div class="d-grid">
                    <button type="button" class="btn btn-dark rounded-3 fw-bold" data-bs-dismiss="modal">
                        Understood, I will enable location
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-pulse-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background-color: #198754;
    animation: navPulse 1.8s infinite;
}
@keyframes navPulse {
    0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(25, 135, 84, 0); }
    100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}
.btn-xs {
    padding: 0.15rem 0.45rem;
    font-size: 0.7rem;
    line-height: 1.2;
}
</style>

<script>
function showLocationAlertModal(title, message) {
    const titleEl = document.getElementById('locationNoticeTitle');
    const bodyEl = document.getElementById('locationNoticeBody');
    if (titleEl) titleEl.innerText = title;
    if (bodyEl) bodyEl.innerHTML = message;

    const modalEl = document.getElementById('locationRequiredNoticeModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modalInstance.show();
    } else {
        alert(title + ':\n' + message);
    }
}

function requestGeoAttendance(buttonEl, formId, latInputId, lngInputId, addressInputId, actionLabel) {
    if (!navigator.geolocation) {
        showLocationAlertModal('Geolocation Not Supported', 'Your browser does not support GPS Geolocation. Please use Chrome, Edge, Safari, or a mobile browser.');
        return;
    }

    const originalContent = buttonEl.innerHTML;
    buttonEl.disabled = true;
    buttonEl.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Asking GPS Permission...`;

    const geoOptions = {
        enableHighAccuracy: true,
        timeout: 12000,
        maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition(
        function (position) {
            // Permission GRANTED by user
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById(latInputId).value = lat;
            document.getElementById(lngInputId).value = lng;

            buttonEl.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Recording ${actionLabel}...`;

            // Submit the attendance form
            document.getElementById(formId).submit();
        },
        function (error) {
            // Permission DENIED or Failed -> DO NOT MARK ATTENDANCE
            buttonEl.disabled = false;
            buttonEl.innerHTML = originalContent;

            let title = 'Location Permission Required';
            let message = 'Location permission is required to mark your attendance. You must allow location access to verify your attendance.';

            if (error.code === error.PERMISSION_DENIED) {
                message = '<strong>You denied location access.</strong><br>Attendance <strong>cannot be marked</strong> without verifying your GPS location.<br><br>Please click the site permissions / lock icon in your browser URL bar, enable <strong>Location: Allow</strong>, and try again.';
            } else if (error.code === error.POSITION_UNAVAILABLE) {
                message = 'Location information is currently unavailable from your device. Please ensure that GPS / Location Services is turned ON in your device settings and try again.';
            } else if (error.code === error.TIMEOUT) {
                message = 'The location request timed out while waiting for GPS satellite lock. Please ensure a stable network connection and try again.';
            }

            showLocationAlertModal(title, message);
        },
        geoOptions
    );
}
</script>
