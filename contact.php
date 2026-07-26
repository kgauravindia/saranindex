<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "Contact Us – Saran Index";
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">Contact Saran Index</h1>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 550px;">Have questions, listing updates, or feedback? Get in touch with our team.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h4 class="fw-bold font-heading text-dark mb-4">OfferPlant Support Office</h4>

                <div class="d-flex mb-4">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-building fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark mb-1">Company</strong>
                        <span class="text-muted small">OfferPlant Technologies Private Limited</span>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-telephone-fill fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark mb-1">Phone / WhatsApp</strong>
                        <a href="tel:9431426600" class="text-primary fw-bold text-decoration-none">9431426600</a>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-envelope-fill fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark mb-1">Email Support</strong>
                        <a href="mailto:ask@offerplant.com" class="text-muted text-decoration-none small">ask@offerplant.com</a>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-globe fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark mb-1">Website</strong>
                        <a href="http://offerplant.com" target="_blank" class="text-primary text-decoration-none small">www.offerplant.com <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                    </div>
                </div>

                <div class="border-top pt-4 mt-2">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-share text-primary me-2"></i>Official Social Handles (<span class="text-primary">@saranindex</span>)</h6>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill font-body font-normal px-3">
                            <i class="bi bi-facebook me-1"></i>Facebook
                        </a>
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill font-body font-normal px-3">
                            <i class="bi bi-instagram me-1"></i>Instagram
                        </a>
                        <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill font-body font-normal px-3">
                            <i class="bi bi-twitter-x me-1"></i>Twitter/X
                        </a>
                        <a href="<?php echo SOCIAL_THREADS; ?>" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill font-body font-normal px-3">
                            <i class="bi bi-threads me-1"></i>Threads
                        </a>
                        <a href="<?php echo SOCIAL_YOUTUBE; ?>" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill font-body font-normal px-3">
                            <i class="bi bi-youtube me-1"></i>YouTube
                        </a>
                        <a href="<?php echo SOCIAL_WHATSAPP; ?>" target="_blank" class="btn btn-success btn-sm rounded-pill font-body font-normal px-3 text-white">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp Channel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h4 class="fw-bold font-heading text-dark mb-3">Send Us a Message</h4>
                <form action="#" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Your Name</label>
                            <input type="text" class="form-control bg-light" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Mobile Number</label>
                            <input type="tel" class="form-control bg-light" placeholder="10-digit Mobile" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Subject</label>
                            <input type="text" class="form-control bg-light" placeholder="Listing Query / Feedback" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Message</label>
                            <textarea class="form-control bg-light" rows="4" placeholder="How can we help you?" required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
