<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "24/7 Emergency Numbers Saran (Chapra) – Saran Index";
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-danger text-white py-5 text-center">
    <div class="container">
        <div class="d-inline-flex align-items-center bg-white text-danger fw-bold px-3 py-1 rounded-pill mb-3 shadow-sm">
            <i class="bi bi-shield-exclamation me-1"></i>24x7 Public Helpline
        </div>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">Emergency Contacts in Saran</h1>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 600px;">Direct phone numbers for Police Stations, Hospitals, Blood Banks, Ambulance, and District Helpline in Chapra.</p>
    </div>
</div>

<div class="container py-5">
    <!-- National Emergency Banner -->
    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="bg-danger-subtle border border-danger text-danger p-3 rounded-4 text-center">
                <div class="fs-2 fw-bold font-heading">112</div>
                <div class="fw-semibold small">National Emergency (Police/Fire/Medical)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-primary-subtle border border-primary text-primary p-3 rounded-4 text-center">
                <div class="fs-2 fw-bold font-heading">102 / 108</div>
                <div class="fw-semibold small">24/7 Government Ambulance</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-warning-subtle border border-warning text-dark p-3 rounded-4 text-center">
                <div class="fs-2 fw-bold font-heading">181</div>
                <div class="fw-semibold small">Women Helpline</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-success-subtle border border-success text-success p-3 rounded-4 text-center">
                <div class="fs-2 fw-bold font-heading">1098</div>
                <div class="fw-semibold small">Child Helpline</div>
            </div>
        </div>
    </div>

    <!-- Police & Administration -->
    <h3 class="fw-bold font-heading text-dark mb-4"><i class="bi bi-shield-fill text-danger me-2"></i>Police Stations & District Officials</h3>
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-dark font-heading mb-1">Town Police Station (Chapra Town Thana)</h5>
                <p class="text-muted small mb-3">Near Thanachowk, Main Road, Chapra Sadar</p>
                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <div class="fw-bold text-dark">06152-243202</div>
                    <a href="tel:06152243202" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"><i class="bi bi-telephone-fill me-1"></i>Call Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-dark font-heading mb-1">District Magistrate Control Room (DM Saran)</h5>
                <p class="text-muted small mb-3">Collectorate Campus, Katchahry Chowk, Chapra</p>
                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <div class="fw-bold text-dark">06152-245001</div>
                    <a href="tel:06152245001" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold"><i class="bi bi-telephone-fill me-1"></i>Call Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-dark font-heading mb-1">Sadar Hospital Chapra (Emergency Room)</h5>
                <p class="text-muted small mb-3">Hospital Road, Municipal Chowk, Chapra</p>
                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <div class="fw-bold text-dark">06152-243405</div>
                    <a href="tel:06152243405" class="btn btn-success btn-sm rounded-pill px-3 fw-bold"><i class="bi bi-telephone-fill me-1"></i>Call Emergency Room</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-dark font-heading mb-1">Fire Brigade Station Chapra</h5>
                <p class="text-muted small mb-3">Fire Station Road, Chapra</p>
                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <div class="fw-bold text-dark">06152-242200 / 101</div>
                    <a href="tel:101" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"><i class="bi bi-telephone-fill me-1"></i>Call Fire Station</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
