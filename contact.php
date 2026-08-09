<?php
require_once __DIR__ . '/includes/functions.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $mobile = isset($_POST['mobile']) ? sanitizeInput($_POST['mobile']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitizeInput($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

    if (empty($name) || empty($mobile) || empty($subject) || empty($message)) {
        $error_msg = 'Please fill in all required fields (Name, Mobile, Subject, and Message).';
    } else {
        if (saveContactMessage($name, $mobile, $email, $subject, $message)) {
            $success_msg = 'Thank you! Your message has been submitted successfully. Our team will get back to you shortly.';
        } else {
            $error_msg = 'Sorry, there was an error submitting your message. Please try again or reach out via phone/WhatsApp.';
        }
    }
}

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
                        <a href="mailto:info@saranindex.com" class="text-muted text-decoration-none small">info@saranindex.com</a>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-globe fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark mb-1">Website</strong>
                        <a href="https://saranindex.com" target="_blank" class="text-primary text-decoration-none small">saranindex.com <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                    </div>
                </div>

                <div class="border-top pt-4 mt-2">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-share text-primary me-2"></i>Social Handles (<span class="text-primary">@saranindex</span>)</h6>
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
                        <a href="<?php echo SOCIAL_TELEGRAM; ?>" target="_blank" class="btn btn-outline-info btn-sm rounded-pill font-body font-normal px-3">
                            <i class="bi bi-telegram me-1"></i>Telegram (t.me/saranindex)
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

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div><?php echo $success_msg; ?></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                        <div><?php echo $error_msg; ?></div>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Your Name</label>
                            <input type="text" name="name" class="form-control bg-light" placeholder="Full Name" value="<?php echo isset($_POST['name']) && empty($success_msg) ? sanitizeInput($_POST['name']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Mobile Number</label>
                            <input type="tel" name="mobile" class="form-control bg-light" placeholder="10-digit Mobile" value="<?php echo isset($_POST['mobile']) && empty($success_msg) ? sanitizeInput($_POST['mobile']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Email Address</label>
                            <input type="email" name="email" class="form-control bg-light" placeholder="name@example.com" value="<?php echo isset($_POST['email']) && empty($success_msg) ? sanitizeInput($_POST['email']) : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Subject</label>
                            <select name="subject" class="form-select bg-light" required>
                                <option value="" selected disabled>-- Select Subject --</option>
                                <option value="Add my business">Add my business</option>
                                <option value="General Support">General Support</option>
                                <option value="Other Query / Suggestion">Other Query / Suggestion</option>
                                <option value="Remove my profile (business)">Remove my profile (business)</option>
                                <option value="Remove my profile (people)">Remove my profile (people)</option>
                                <option value="Update Profile">Update Profile</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Message</label>
                            <textarea name="message" class="form-control bg-light" rows="4" placeholder="How can we help you?" required><?php echo isset($_POST['message']) && empty($success_msg) ? sanitizeInput($_POST['message']) : ''; ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Google Map Location -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 pb-2">
                    <h5 class="fw-bold font-heading text-dark mb-1"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Find Us On Google Maps</h5>
                    <p class="text-muted small mb-0">Visit Saran Index support office in Chapra, Saran District</p>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-sm" style="min-height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3592.302861840524!2d84.7475845!3d25.793580600000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3992bb00676748ab%3A0x7bda2775f02a9b78!2sSaran%20Index!5e0!3m2!1sen!2sin!4v1785588332046!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
