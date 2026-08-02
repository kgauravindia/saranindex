<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Official Reference Data Sources & Portals – Saran Index";
$meta_description = "Explore the official government portals, registries, and open public directories referenced by Saran Index for local administrative, educational, revenue, and business information.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">Transparency & Data Sources</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">Official Reference Data Sources</h1>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 720px;">
            <strong>Saran Index</strong> aggregates and organizes public directory information from trusted official government portals, open registries, and verified public databases for community convenience.
        </p>
    </div>
</div>

<div class="container py-5">
    
    <!-- Introductory Callout -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white border-start border-4 border-primary">
        <div class="d-flex align-items-start">
            <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0">
                <i class="bi bi-info-circle-fill fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark font-heading mb-1">Our Data Aggregation Philosophy</h5>
                <p class="text-secondary mb-0" style="line-height: 1.7;">
                    To ensure high data integrity, standardized codes, and accurate administrative mapping across all 20 blocks of Saran District (Chapra), our directory references open public data, official government portals, and public registry portals. Below is the curated list of key reference sources utilized across our platform.
                </p>
            </div>
        </div>
    </div>

    <!-- Data Sources Grid -->
    <div class="row g-4 mb-5">
        
        <!-- 1. AISHE Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-info-subtle text-info-emphasis fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-mortarboard-fill me-1"></i> Education & Colleges
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">Government of India</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">AISHE Portal</h4>
                <p class="text-muted small mb-3">All India Survey on Higher Education (Ministry of Education)</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Official catalog of higher educational institutions, colleges, and university affiliation directories across Saran District.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>aishe.gov.in</span>
                    <a href="https://aishe.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. GST Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-shop me-1"></i> Business & Tax Registrations
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">GST Council</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">GST Official Portal</h4>
                <p class="text-muted small mb-3">Goods and Services Tax Network (GSTN)</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Official portal providing public taxpayer entity names, trade names, business classifications, and active registration status for commercial enterprises in Saran.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>gst.gov.in</span>
                    <a href="https://gst.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. LGD Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-diagram-3-fill me-1"></i> Local Administrative Codes
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">Panchayati Raj Ministry</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">LGD Directory Portal</h4>
                <p class="text-muted small mb-3">Local Government Directory Portal</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Official standardized location codes for all 20 blocks, gram panchayats, and census villages in Saran District.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>lgdirectory.gov.in</span>
                    <a href="https://lgdirectory.gov.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Bihar Bhumi -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-map-fill me-1"></i> Revenue Circle & Mauja
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">Govt of Bihar</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">Bihar Bhumi Portal</h4>
                <p class="text-muted small mb-3">Revenue & Land Reforms Department, Government of Bihar</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Official portal for revenue circle details, Halka numbers, Mauja (Revenue Village) codes, and land reform administrative records in Saran District.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>biharbhumi.bihar.gov.in</span>
                    <a href="https://biharbhumi.bihar.gov.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Census of India -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-people-fill me-1"></i> Census & Demographics
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">Ministry of Home Affairs</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">Census of India Data Portal</h4>
                <p class="text-muted small mb-3">Office of the Registrar General & Census Commissioner</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Primary official source for Census 2011 demographics, village population, literacy statistics, household counts, and worker classifications.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>censusindia.gov.in</span>
                    <a href="https://censusindia.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 6. Saran NIC Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-building-fill me-1"></i> District Administration
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">NIC Bihar</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">Saran District Official Portal</h4>
                <p class="text-muted small mb-3">National Informatics Centre (NIC Saran, Chapra)</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Official administration website for Saran District providing key government office directories, public helplines, and officer listings.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>saran.nic.in</span>
                    <a href="https://saran.nic.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 7. Data.gov.in Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-database-fill me-1"></i> Open Government Data
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">MeitY & NIC</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">Open Government Data Platform</h4>
                <p class="text-muted small mb-3">Open Government Data (OGD) Platform India</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    National Open Data Portal providing public civic datasets, spatial data, and regional administrative statistics for Bihar and Saran District.
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>data.gov.in</span>
                    <a href="https://data.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        Visit Portal <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Non-Government Disclaimer Footer Box -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light text-dark">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-shield-exclamation text-warning me-2 fs-4"></i>
            <h5 class="fw-bold mb-0 font-heading">Data Disclaimer</h5>
        </div>
        <p class="small text-secondary mb-0" style="line-height: 1.6;">
            <strong>Saran Index</strong> is an independent digital directory platform created by <strong>OfferPlant Technologies Pvt. Ltd.</strong> References to government portals and public registries are provided for attribution, data transparency, and public reference. Saran Index is not affiliated with, endorsed by, or representing any government agency.
        </p>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
