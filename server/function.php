<?php
/**
 * server/function.php
 *
 * Handles two incoming JSON payloads from the frontend:
 *   1. {quotation: true, email, location, ...attribution}  → quotation request
 *   2. {lead: true, fullname, email, subject, message, location, ...attribution} → contact form lead
 *
 * Saves the lead + attribution data to the MySQL `leads` table
 * (see server/migrations/001_attribution.sql), then sends an email
 * notification with the attribution metadata so we can see WHERE the
 * lead came from (landing page, UTM, search query, referrer).
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

// --------------------------------------------------------------------------
// Helpers
// --------------------------------------------------------------------------

function hlm_sanitize_str($v, $maxLen = 1000) {
    $v = is_string($v) ? trim($v) : '';
    $v = mb_substr($v, 0, $maxLen, 'UTF-8');
    return $v;
}

function hlm_extract_search_query($referrer) {
    if (!$referrer) return '';
    if (preg_match('/[?&]q=([^&]+)/i', $referrer, $m)) {
        return urldecode($m[1]);
    }
    return '';
}

function hlm_is_mobile($ua) {
    return preg_match('/(Mobile|Android|iPhone|iPad|iPod|Opera Mini|IEMobile)/i', $ua) ? 1 : 0;
}

/**
 * Inserts a lead into the `leads` table. Falls back to `customers` if the
 * `leads` table isn't there yet (graceful pre-migration behaviour).
 *
 * @param mysqli $con
 * @param array $data
 * @return int|false  lead id or false on failure
 */
function hlm_save_lead(mysqli $con, array $data) {
    // Attempt new `leads` table first.
    $check = $con->query("SHOW TABLES LIKE 'leads'");
    if ($check && $check->num_rows > 0) {
        $stmt = $con->prepare("
            INSERT INTO leads (
                lead_type, name, email, phone, subject, message,
                landing_page, original_referrer,
                utm_source, utm_medium, utm_campaign, utm_term, utm_content,
                search_query, location, user_agent, ip_address, is_mobile, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        if (!$stmt) {
            error_log('Leads prepare failed: ' . $con->error);
            return false;
        }
        // 17 strings + 1 int (is_mobile)
        $stmt->bind_param(
            'sssssssssssssssssi',
            $data['lead_type'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message'],
            $data['landing_page'],
            $data['original_referrer'],
            $data['utm_source'],
            $data['utm_medium'],
            $data['utm_campaign'],
            $data['utm_term'],
            $data['utm_content'],
            $data['search_query'],
            $data['location'],
            $data['user_agent'],
            $data['ip_address'],
            $data['is_mobile']
        );
        $ok = $stmt->execute();
        if (!$ok) {
            error_log('Leads insert failed: ' . $stmt->error);
            return false;
        }
        return $con->insert_id;
    }

    // Fallback: legacy `customers` table (pre-migration).
    $stmt = $con->prepare("INSERT INTO customers (email, location, date) VALUES (?, ?, NOW())");
    if (!$stmt) {
        error_log('Customers prepare failed: ' . $con->error);
        return false;
    }
    $stmt->bind_param('ss', $data['email'], $data['location']);
    return $stmt->execute() ? $con->insert_id : false;
}

// --------------------------------------------------------------------------
// Email
// --------------------------------------------------------------------------

function sendEmail($to, $subject, $message, $fromEmail = 'hlomedia.office@gmail.com', $fromName = 'הלו-מדיה') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hlomedia.office@gmail.com';
        $mail->Password   = 'REMOVED_GMAIL_APP_PASSWORD'; // app password (2FA)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->addCC('idanedri27@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);
        $mail->AltBody = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Email send error: ' . $mail->ErrorInfo);
        return false;
    }
}

// --------------------------------------------------------------------------
// Request handling
// --------------------------------------------------------------------------

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { $data = []; }

// Honeypot: bots fill this; abort silently with 200.
if (!empty($data['hp_website'])) {
    echo json_encode(['success' => true, 'note' => 'thanks']);
    exit;
}

// Common attribution fields, no matter which form.
$attribution = [
    'landing_page'      => hlm_sanitize_str($data['landing_page']      ?? '', 500),
    'original_referrer' => hlm_sanitize_str($data['original_referrer'] ?? '', 500),
    'utm_source'        => hlm_sanitize_str($data['utm_source']        ?? '', 150),
    'utm_medium'        => hlm_sanitize_str($data['utm_medium']        ?? '', 150),
    'utm_campaign'      => hlm_sanitize_str($data['utm_campaign']      ?? '', 150),
    'utm_term'          => hlm_sanitize_str($data['utm_term']          ?? '', 150),
    'utm_content'       => hlm_sanitize_str($data['utm_content']       ?? '', 150),
    'search_query'      => hlm_sanitize_str($data['search_query']      ?? '', 250),
    'location'          => hlm_sanitize_str($data['location']          ?? '', 100),
    'user_agent'        => hlm_sanitize_str($_SERVER['HTTP_USER_AGENT'] ?? '', 500),
    'ip_address'        => hlm_sanitize_str($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '', 100),
    'is_mobile'         => hlm_is_mobile($_SERVER['HTTP_USER_AGENT'] ?? ''),
];

// Backfill search query from referrer if empty.
if (!$attribution['search_query'] && $attribution['original_referrer']) {
    $attribution['search_query'] = hlm_extract_search_query($attribution['original_referrer']);
}

// ---- QUOTATION REQUEST (email only) -----------------------------------
if (!empty($data['quotation'])) {
    $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'invalid email']);
        exit;
    }

    $lead = array_merge($attribution, [
        'lead_type' => 'quotation',
        'name'      => '',
        'email'     => $email,
        'phone'     => '',
        'subject'   => 'Quotation request',
        'message'   => '',
    ]);

    $lead_id = hlm_save_lead($con, $lead);

    $subject = 'מייל חדש מהלו-מדיה (הצעת מחיר)';
    $message = "שלום, יש לך מייל חדש מהלו-מדיה שמעוניין לקבל הצעת מחיר.\n";
    $message .= "המייל שלו: \"$email\"\n\n";
    $message .= "--- ATTRIBUTION ---\n";
    $message .= "דף נחיתה: {$attribution['landing_page']}\n";
    $message .= "מקור: " . ($attribution['utm_source'] ?: 'organic') . "\n";
    $message .= "Referrer: {$attribution['original_referrer']}\n";
    if ($attribution['search_query']) $message .= "מילת חיפוש: {$attribution['search_query']}\n";
    if ($attribution['utm_campaign'])  $message .= "קמפיין: {$attribution['utm_campaign']}\n";
    $message .= "מובייל: " . ($attribution['is_mobile'] ? 'כן' : 'לא') . "\n";
    $message .= "מיקום: {$attribution['location']}\n";
    if ($lead_id) $message .= "Lead ID: $lead_id";

    sendEmail('hlomedia.office@gmail.com', $subject, $message);

    echo json_encode(['success' => true, 'lead_id' => $lead_id]);
    exit;
}

// ---- LEAD (full contact form) -----------------------------------------
if (!empty($data['lead'])) {
    $name    = hlm_sanitize_str($data['fullname'] ?? '', 200);
    $email   = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone   = hlm_sanitize_str($data['phone']    ?? '', 50);
    $subject = hlm_sanitize_str($data['subject']  ?? '', 200);
    $message = hlm_sanitize_str($data['message']  ?? '', 5000);

    if (empty($name) || (empty($email) && empty($phone))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'name + (email or phone) required']);
        exit;
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'invalid email']);
        exit;
    }

    $lead = array_merge($attribution, [
        'lead_type' => 'contact',
        'name'      => $name,
        'email'     => $email ?: '',
        'phone'     => $phone,
        'subject'   => $subject ?: 'New contact form lead',
        'message'   => $message,
    ]);

    $lead_id = hlm_save_lead($con, $lead);

    $emailSubject = "🔥 ליד חדש מהלו-מדיה: " . ($subject ?: $name);

    $emailContent  = "שלום, יש לך פנייה חדשה מהאתר.\n\n";
    $emailContent .= "שם מלא: $name\n";
    if ($email)   $emailContent .= "מייל: $email\n";
    if ($phone)   $emailContent .= "טלפון: $phone\n";
    if ($subject) $emailContent .= "נושא: $subject\n";
    if ($message) $emailContent .= "הודעה: $message\n";

    $emailContent .= "\n--- ATTRIBUTION ---\n";
    $emailContent .= "דף נחיתה: {$attribution['landing_page']}\n";
    $emailContent .= "מקור: " . ($attribution['utm_source'] ?: 'organic') . "\n";
    $emailContent .= "Referrer: {$attribution['original_referrer']}\n";
    if ($attribution['search_query']) $emailContent .= "מילת חיפוש: {$attribution['search_query']}\n";
    if ($attribution['utm_campaign']) $emailContent .= "קמפיין: {$attribution['utm_campaign']}\n";
    $emailContent .= "מובייל: " . ($attribution['is_mobile'] ? 'כן' : 'לא') . "\n";
    $emailContent .= "מיקום: {$attribution['location']}\n";
    if ($lead_id) $emailContent .= "\nLead ID: $lead_id";

    sendEmail('hlomedia.office@gmail.com', $emailSubject, $emailContent);

    echo json_encode(['success' => true, 'lead_id' => $lead_id]);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'unknown payload']);
