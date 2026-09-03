<?php
// contact.php — Public Contact page.
// The AJAX mail-sending logic lives right here at the top of this file
// (runs and exits before any HTML is generated), so there is no separate
// handler file and no cross-file path issues.

require_once __DIR__ . '/includes/environment.php';
$publicEmail = (string)swc_env('SWC_PUBLIC_EMAIL', 'hello@example.com');
$publicPhone = (string)swc_env('SWC_PUBLIC_PHONE', '+000 00 000 000');
$publicPhoneLink = (string)swc_env('SWC_PUBLIC_PHONE_LINK', '+00000000000');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_request'])) {

    // Buffer output so any stray notice/warning from an include never
    // corrupts the JSON response.
    ob_start();
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    header('Content-Type: application/json');

    // Never expose SMTP credentials/conversation details to public visitors.
    // Errors are still written to the server error log for diagnostics.
    $DEBUG_MODE = false;

    function respond($success, $message, $debug = null) {
        ob_clean();
        $payload = ['success' => $success, 'message' => $message];
        if ($debug !== null) {
            $payload['debug'] = $debug;
        }
        echo json_encode($payload);
        exit();
    }

    // Honeypot spam trap. Never report a fake success: password managers or
    // browser autofill can occasionally populate hidden fields on real visits.
    if (!empty($_POST['website'])) {
        error_log('Contact form blocked by honeypot.');
        respond(false, 'Spam protection was triggered. Please refresh the page and try again.');
    }

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        respond(false, 'Please fill in your name, email, and message.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, 'Please enter a valid email address.');
    }
    // mbstring is common on XAMPP/hosting, but the form should still work if it is disabled.
    $textLength = static function ($value) {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    };
    if ($textLength($name) > 100 || $textLength($subject) > 150 || $textLength($message) > 5000) {
        respond(false, 'One of the fields is too long. Please shorten your message.');
    }

    // Keep mail headers clean even if a bot submits CR/LF characters.
    $name = str_replace(["\r", "\n"], ' ', $name);
    $subject = str_replace(["\r", "\n"], ' ', $subject);

    require_once __DIR__ . '/mail-config.php';

    if (SMTP_HOST === '' || MAIL_FROM_EMAIL === '' || MAIL_TO_ADDRESSES === [] || (SMTP_AUTH && (SMTP_USERNAME === '' || SMTP_PASSWORD === ''))) {
        error_log('Contact form mail settings are incomplete.');
        respond(false, 'Mail system is not configured yet.');
    }

    $phpmailerPath = __DIR__ . '/vendor/PHPMailer/src/';
    if (!file_exists($phpmailerPath . 'PHPMailer.php')) {
        respond(false, 'Mail system is not configured yet.', 'PHPMailer files not found at: ' . $phpmailerPath);
    }

    require_once $phpmailerPath . 'Exception.php';
    require_once $phpmailerPath . 'PHPMailer.php';
    require_once $phpmailerPath . 'SMTP.php';

    $displaySubject = $subject !== '' ? $subject : 'New message from the SoftWebCo website';
    $safeName       = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeEmail      = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $safeSubject    = htmlspecialchars($displaySubject, ENT_QUOTES, 'UTF-8');
    $safeMessage    = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
    $nameParts      = explode(' ', trim($name));
    $firstName      = htmlspecialchars($nameParts[0], ENT_QUOTES, 'UTF-8');
    $submittedAt    = date('l, F j, Y \a\t g:i A');

    // Shared email chrome. Keep it text-only so Gmail never exposes Logo.png as
    // an attachment. Explicit light color-scheme values also keep the brand
    // readable when the device/mail app is in dark mode.
    function emailShell($bodyHtml, $preheader) {
        $safePreheader = htmlspecialchars($preheader, ENT_QUOTES, 'UTF-8');
        return '<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
:root { color-scheme: light only !important; supported-color-schemes: light !important; }
body, .swc-email-bg { background:#eef2f3 !important; color:#0B202D !important; }
.swc-email-card, .swc-email-body { background:#ffffff !important; color:#0B202D !important; }
.swc-email-head { background:#0B202D !important; color:#ffffff !important; }
.swc-email-foot { background:#f7fafa !important; color:#7c8a90 !important; }
@media (prefers-color-scheme: dark) {
  body, .swc-email-bg { background:#eef2f3 !important; color:#0B202D !important; }
  .swc-email-card, .swc-email-body { background:#ffffff !important; color:#0B202D !important; }
  .swc-email-head { background:#0B202D !important; color:#ffffff !important; }
  .swc-email-foot { background:#f7fafa !important; color:#7c8a90 !important; }
}
</style>
</head>
<body style="margin:0;padding:0;background:#eef2f3;color:#0B202D;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">' . $safePreheader . '</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="swc-email-bg" style="width:100%;background:#eef2f3;padding:32px 16px;font-family:Arial,Helvetica,sans-serif;color:#0B202D;">
<tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" class="swc-email-card" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #dfe8ea;">
<tr>
<td class="swc-email-head" style="background:#0B202D;padding:30px 40px;text-align:center;color:#ffffff;">
<div style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:.02em;">Soft<span style="color:#20bca9;">Web</span>Co</div>
<div style="margin-top:6px;color:#b9c9cf;font-size:12px;letter-spacing:.08em;text-transform:uppercase;">Digital Solutions</div>
</td>
</tr>
<tr><td class="swc-email-body" style="padding:40px;background:#ffffff;color:#0B202D;">' . $bodyHtml . '</td></tr>
<tr>
<td class="swc-email-foot" style="background:#f7fafa;padding:24px 40px;text-align:center;border-top:1px solid #eaeef0;color:#7c8a90;">
<p style="margin:0 0 10px;font-size:13px;color:#7c8a90;">Crafting digital experiences that inspire.</p>
<div style="margin-bottom:10px;">
<a href="https://www.facebook.com/profile.php?id=61578804990939" style="display:inline-block;margin:0 6px;color:#0B202D;text-decoration:none;font-size:13px;">Facebook</a>
<span style="color:#c7d0d3;">&bull;</span>
<a href="https://www.instagram.com/softwebco/" style="display:inline-block;margin:0 6px;color:#0B202D;text-decoration:none;font-size:13px;">Instagram</a>
<span style="color:#c7d0d3;">&bull;</span>
<a href="https://www.tiktok.com/@softwebco" style="display:inline-block;margin:0 6px;color:#0B202D;text-decoration:none;font-size:13px;">TikTok</a>
</div>
<p style="margin:0;font-size:12px;color:#aab5b9;">&copy; ' . date('Y') . ' SoftWebCo. All rights reserved.</p>
</td>
</tr>
</table>
</td></tr>
</table>
</body>
</html>';
    }

    $teamBody = '
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
            <tr><td>
                <span style="display:inline-block;background:#e7f7f5;color:#178f7f;font-size:12px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;padding:6px 14px;border-radius:9999px;">
                    New Contact Form Submission
                </span>
            </td></tr>
        </table>
        <h1 style="margin:0 0 8px;font-size:22px;color:#0B202D;font-weight:700;">' . $safeSubject . '</h1>
        <p style="margin:0 0 28px;font-size:13px;color:#7c8a90;">Received ' . $submittedAt . '</p>

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafa;border:1px solid #eaeef0;border-radius:12px;margin-bottom:24px;">
            <tr><td style="padding:20px 24px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="90" style="padding:6px 0;font-size:13px;color:#7c8a90;vertical-align:top;">Name</td>
                        <td style="padding:6px 0;font-size:14px;color:#0B202D;font-weight:600;">' . $safeName . '</td>
                    </tr>
                    <tr>
                        <td width="90" style="padding:6px 0;font-size:13px;color:#7c8a90;vertical-align:top;">Email</td>
                        <td style="padding:6px 0;font-size:14px;"><a href="mailto:' . $safeEmail . '" style="color:#178f7f;text-decoration:none;font-weight:600;">' . $safeEmail . '</a></td>
                    </tr>
                </table>
            </td></tr>
        </table>

        <p style="margin:0 0 10px;font-size:13px;color:#7c8a90;font-weight:600;">MESSAGE</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #eaeef0;border-left:4px solid #20bca9;border-radius:8px;margin-bottom:28px;">
            <tr><td style="padding:18px 22px;font-size:14px;line-height:1.7;color:#2D3945;">
                ' . $safeMessage . '
            </td></tr>
        </table>

        <table role="presentation" cellpadding="0" cellspacing="0">
            <tr><td style="border-radius:10px;background:#20bca9;">
                <a href="mailto:' . $safeEmail . '?subject=Re: ' . rawurlencode($displaySubject) . '"
                   style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:600;color:#0B202D;text-decoration:none;">
                   Reply to ' . $firstName . ' &rarr;
                </a>
            </td></tr>
        </table>
    ';

    $senderBody = '
        <div style="text-align:center;margin-bottom:28px;">
            <table role="presentation" width="64" height="64" cellpadding="0" cellspacing="0" border="0" align="center" style="width:64px;height:64px;margin:0 auto 16px;border-collapse:separate;">
                <tr>
                    <td width="64" height="64" align="center" valign="middle" bgcolor="#e7f7f5" style="width:64px;height:64px;background:#e7f7f5;border-radius:32px;text-align:center;vertical-align:middle;font-family:Arial,Segoe UI Symbol,sans-serif;font-size:28px;line-height:64px;color:#0B202D;mso-line-height-rule:exactly;">
                        <span style="display:block;width:64px;height:64px;line-height:64px;text-align:center;font-size:28px;color:#0B202D;">&#9993;</span>
                    </td>
                </tr>
            </table>
            <h1 style="margin:0 0 10px;font-size:24px;color:#0B202D;font-weight:700;">Thanks for reaching out, ' . $firstName . '!</h1>
            <p style="margin:0;font-size:15px;color:#5b6a70;line-height:1.6;">We received your message and a member of the SoftWebCo team will get back to you within 24 hours.</p>
        </div>

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f7fafa;border:1px solid #eaeef0;border-radius:12px;margin-bottom:28px;">
            <tr><td style="padding:22px 24px;">
                <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#7c8a90;">Your message</p>
                <p style="margin:0 0 14px;font-size:14px;font-weight:600;color:#0B202D;">' . $safeSubject . '</p>
                <div style="font-size:14px;line-height:1.7;color:#2D3945;border-top:1px solid #e5e9ea;padding-top:14px;">
                    ' . $safeMessage . '
                </div>
            </td></tr>
        </table>

        <p style="margin:0 0 24px;font-size:14px;line-height:1.7;color:#5b6a70;">
            In the meantime, feel free to explore our recent work or reach us directly if anything is urgent.
        </p>

        <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
            <tr><td style="border-radius:10px;background:#0B202D;">
                <a href="https://softwebco.com/programs" style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;">
                    Explore Programs &rarr;
                </a>
            </td></tr>
        </table>

        <p style="margin:28px 0 0;font-size:13px;color:#9aa6ab;text-align:center;">
            Need something faster? Call us at <a href="tel:' . htmlspecialchars($publicPhoneLink, ENT_QUOTES, 'UTF-8') . '" style="color:#178f7f;text-decoration:none;font-weight:600;">' . htmlspecialchars($publicPhone, ENT_QUOTES, 'UTF-8') . '</a>
        </p>
    ';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $smtpLog = '';

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = defined('SMTP_AUTH') ? (bool) SMTP_AUTH : true;
        $mail->Username   = trim(SMTP_USERNAME);
        $mail->Password   = str_replace(' ', '', SMTP_PASSWORD); // strip spaces Google adds for readability
        if (SMTP_ENCRYPTION === 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } elseif (SMTP_ENCRYPTION === 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            // Useful for trusted local SMTP relays and automated local testing.
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
        }
        $mail->Port     = SMTP_PORT;
        $mail->CharSet  = 'UTF-8';
        $mail->Timeout  = 30;

        if ($DEBUG_MODE) {
            $mail->SMTPDebug   = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function ($str, $level) use (&$smtpLog) {
                $smtpLog .= $str . "\n";
            };
        }

        // ---- Email 1: notification to the SoftWebCo team ----
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addReplyTo($email, $name);
        foreach (MAIL_TO_ADDRESSES as $to) {
            $mail->addAddress($to);
        }
        foreach (MAIL_BCC_ADDRESSES as $bcc) {
            $mail->addBCC($bcc);
        }

        $mail->isHTML(true);
        $mail->Subject = '[Contact Form] ' . $displaySubject;
        $mail->Body    = emailShell($teamBody, 'New message from ' . $name . ' via the SoftWebCo contact form');
        $mail->AltBody = "New contact form submission\n\nName: {$name}\nEmail: {$email}\nSubject: {$displaySubject}\n\nMessage:\n{$message}";

        $mail->send();
        $teamMessageDelivered = true;
        error_log('Contact form delivered successfully.');

        // ---- Email 2: confirmation auto-reply to the sender ----
        // The visitor's message is already safely delivered at this point. If the
        // optional confirmation email fails, do NOT report the whole submission as
        // failed (that caused users to retry and could create duplicate enquiries).
        $confirmationSent = false;
        try {
            $mail->clearAddresses();
            $mail->clearReplyTos();
            $mail->clearBCCs();
            $mail->clearAttachments();

            $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
            $mail->addAddress($email, $name);
            if (!empty(MAIL_TO_ADDRESSES[0])) {
                $mail->addReplyTo(MAIL_TO_ADDRESSES[0], MAIL_FROM_NAME);
            }

            $mail->Subject = "We've received your message, {$firstName}!";
            $mail->Body    = emailShell($senderBody, "Thanks for contacting SoftWebCo — we'll be in touch within 24 hours.");
            $mail->AltBody = "Hi {$name},\n\nThanks for reaching out! We received your message and will get back to you within 24 hours.\n\nYour message:\n{$message}\n\n- The SoftWebCo Team";

            $mail->send();
            $confirmationSent = true;
        } catch (Throwable $confirmationError) {
            error_log('Contact confirmation email error: ' . $mail->ErrorInfo);
        }

        $successMessage = $confirmationSent
            ? "Thanks {$name}! Your message has been sent — check your inbox for a confirmation email."
            : "Thanks {$name}! Your message has been sent successfully. We'll get back to you shortly.";
        respond(true, $successMessage, null);

    } catch (PHPMailer\PHPMailer\Exception $e) {
        error_log('Contact form mail error: ' . $mail->ErrorInfo);
        error_log($smtpLog);
        respond(
            false,
            'Sorry, your message could not be sent right now. Please try again later or email us directly.',
            $DEBUG_MODE ? ($mail->ErrorInfo . "\n\n---- SMTP LOG ----\n" . $smtpLog) : null
        );
    }
}

// =====================================================================
// Normal page render (only reached when the block above didn't exit)
// =====================================================================
include("WebDesign.php");

$design = new WebDesign();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php
    $design->GenerateHeadTag1();
?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        .gradient-bg {
            background: linear-gradient(135deg, #0B202D 0%, #20bca9 100%);
        }
        .contact-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }
        .glow-text {
            text-shadow: 0 0 10px rgba(32, 188, 169, 0.5);
        }
    </style>

    </head>
<body class="min-h-screen gradient-bg text-white font-sans">

    <!-- Cursor Follower -->
    <div class="cursor-follower"></div>
    
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loading-text">Loading..</div>
    </div>

    
    <!-- Navigation -->

  <?php 
$design->ShowNavbar1();
?>


    <div class="container mx-auto px-4 py-20">
        <div class="max-w-4xl mx-auto text-center mb-16">
            <h1 style="padding-top: 30px;" class="text-4xl md:text-5xl font-bold mb-6 glow-text">Get In Touch</h1>
            <p class="text-lg opacity-80 max-w-2xl mx-auto">We'd love to hear from you. Reach out through any of these channels.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Phone Card -->
            <div class="contact-card rounded-2xl p-8 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-[#20bca9] bg-opacity-20 flex items-center justify-center mb-6">
                    <i class="fas fa-phone-alt text-3xl text-[#20bca9]"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Call Us</h3>
                <p class="opacity-70 mb-4">Available 24/7 for your convenience</p>
                <a href="tel:<?php echo htmlspecialchars($publicPhoneLink, ENT_QUOTES, 'UTF-8'); ?>" class="text-2xl font-bold hover:text-[#20bca9] transition-colors">
                    <?php echo htmlspecialchars($publicPhone, ENT_QUOTES, 'UTF-8'); ?></a>
            </div>

            <!-- Primary Email Card -->
            <div class="contact-card rounded-2xl p-8 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-[#20bca9] bg-opacity-20 flex items-center justify-center mb-6">
                    <i class="fas fa-envelope text-3xl text-[#20bca9]"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">General Inquiries</h3>
                <p class="opacity-70 mb-4">For all general questions</p>
                <a href="mailto:<?php echo htmlspecialchars($publicEmail, ENT_QUOTES, 'UTF-8'); ?>" class="text-lg font-medium hover:text-[#20bca9] transition-colors break-all">
                    <?php echo htmlspecialchars($publicEmail, ENT_QUOTES, 'UTF-8'); ?></a>
            </div>
        </div>

        <!-- Message Form -->
        <div class="max-w-3xl mx-auto mt-12">
            <div class="contact-card rounded-2xl p-8 md:p-10">
                <div class="text-center mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2">Send Us a Message</h2>
                    <p class="opacity-70">Fill out the form below and we'll get back to you shortly.</p>
                </div>

                <form id="contactForm" novalidate autocomplete="on">
                    <!-- Honeypot field (hidden from real users) -->
                    <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                        <label for="cf-website">Website</label>
                        <input type="text" id="cf-website" name="website" tabindex="-1" autocomplete="new-password" data-lpignore="true" data-1p-ignore="true">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="cf-name" class="block text-sm font-medium mb-2 opacity-80">Your Name</label>
                            <input type="text" id="cf-name" name="name" required maxlength="100"
                                class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 focus:border-[#20bca9] focus:outline-none transition placeholder-white/40"
                                placeholder="John Doe">
                        </div>
                        <div>
                            <label for="cf-email" class="block text-sm font-medium mb-2 opacity-80">Your Email</label>
                            <input type="email" id="cf-email" name="email" required maxlength="150"
                                class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 focus:border-[#20bca9] focus:outline-none transition placeholder-white/40"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="cf-subject" class="block text-sm font-medium mb-2 opacity-80">Subject</label>
                        <input type="text" id="cf-subject" name="subject" maxlength="150"
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 focus:border-[#20bca9] focus:outline-none transition placeholder-white/40"
                            placeholder="How can we help?">
                    </div>

                    <div class="mb-6">
                        <label for="cf-message" class="block text-sm font-medium mb-2 opacity-80">Message</label>
                        <textarea id="cf-message" name="message" rows="5" required maxlength="5000"
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 focus:border-[#20bca9] focus:outline-none transition resize-none placeholder-white/40"
                            placeholder="Tell us about your project..."></textarea>
                    </div>

                    <div id="cfFeedback" class="hidden mb-5 px-4 py-3 rounded-lg text-sm font-medium"></div>

                    <button type="submit" id="cfSubmitBtn"
                        class="w-full md:w-auto px-10 py-3 bg-[#20bca9] hover:bg-[#1a9c8c] text-[#0B202D] font-semibold rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                        <span id="cfSubmitText">Send Message</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-2xl mx-auto mt-20 text-center opacity-80">
            <p class="mb-4">Our team typically responds within 24 hours during business days.</p>
            <div class="contact-socials" aria-label="Softwebco social media">
                <a href="https://www.facebook.com/profile.php?id=61578804990939" target="_blank" rel="noopener noreferrer" class="contact-social-link" aria-label="Softwebco on Facebook" title="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="contact-social-svg">
                        <path fill="currentColor" d="M13.55 22v-8.55h2.87l.43-3.33h-3.3V8c0-.96.27-1.62 1.66-1.62H17V3.4c-.31-.04-1.38-.13-2.62-.13-2.59 0-4.36 1.58-4.36 4.49v2.36H7.1v3.33h2.92V22h3.53Z"/>
                    </svg>
                </a>
                <a href="https://www.tiktok.com/@softwebco" target="_blank" rel="noopener noreferrer" class="contact-social-link" aria-label="Softwebco on TikTok" title="TikTok">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="contact-social-svg">
                        <path fill="currentColor" d="M14.2 3c.18 1.6 1.06 2.94 2.48 3.78.76.45 1.55.7 2.32.75v3.22a8.17 8.17 0 0 1-4.8-1.5v6.18A5.57 5.57 0 1 1 9.4 9.9c.37-.03.75-.02 1.12.04v3.3a2.33 2.33 0 1 0 .42 3.95c.44-.43.66-1 .66-1.72V3h2.6Z"/>
                    </svg>
                </a>
                <a href="https://www.instagram.com/softwebco/" target="_blank" rel="noopener noreferrer" class="contact-social-link" aria-label="Softwebco on Instagram" title="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="contact-social-svg">
                        <rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5.1" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="4.15" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="17.35" cy="6.75" r="1.15" fill="currentColor"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>




   <!-- Footer -->
<?php
    $design->showfooter();
?>

    
    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 bg-teal-600 text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-teal-700">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo htmlspecialchars($design->PublicUrl('assets/js/site.js'), ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo rawurlencode(trim((string)@file_get_contents(__DIR__ . '/VERSION'))); ?>"></script>


    <script>
        // Add subtle animation to cards on page load
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.contact-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 150 * index);
            });
        });

        // Contact form AJAX submission — posts back to this same page
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            const submitBtn = document.getElementById('cfSubmitBtn');
            const submitText = document.getElementById('cfSubmitText');
            const feedback = document.getElementById('cfFeedback');

            contactForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Use native browser validation before starting an SMTP request.
                if (!contactForm.checkValidity()) {
                    contactForm.reportValidity();
                    return;
                }

                feedback.classList.add('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                submitText.textContent = 'Sending...';

                try {
                    const formData = new FormData(contactForm);
                    formData.append('ajax_request', '1');

                    const response = await fetch(window.location.pathname || 'contact.php', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        throw new Error('Contact endpoint returned HTTP ' + response.status);
                    }

                    const contentType = response.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        throw new Error('Contact endpoint did not return JSON');
                    }
                    const result = await response.json();

                    feedback.textContent = result.message;
                    feedback.classList.remove('hidden');

                    if (result.success) {
                        feedback.classList.remove('bg-red-500/20', 'text-red-200', 'border', 'border-red-400/30');
                        feedback.classList.add('bg-[#20bca9]/20', 'text-[#7ee8d8]', 'border', 'border-[#20bca9]/40');
                        contactForm.reset();
                    } else {
                        feedback.classList.remove('bg-[#20bca9]/20', 'text-[#7ee8d8]', 'border-[#20bca9]/40');
                        feedback.classList.add('bg-red-500/20', 'text-red-200', 'border', 'border-red-400/30');
                    }
                } catch (err) {
                    feedback.textContent = 'Something went wrong. Please check your connection and try again.';
                    feedback.classList.remove('hidden', 'bg-[#20bca9]/20', 'text-[#7ee8d8]', 'border-[#20bca9]/40');
                    feedback.classList.add('bg-red-500/20', 'text-red-200', 'border', 'border-red-400/30');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                    submitText.textContent = 'Send Message';
                }
            });
        }
    </script>
</body>
</html>
