<?php
/**
 * server/function.php
 *
 * Lead form handler - forwards to the central SEO Platform API.
 *
 * No DB access here. No local persistence. This is a pure pass-through
 * with input normalization (our form field names differ slightly from
 * the API spec).
 *
 * On API failure → fallback email goes out + we return 200 so the user
 * never sees an error.
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$config = require __DIR__ . '/seo-platform-config.php';

// -- Parse input (supports JSON and form-encoded) -------------------------
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);
    if (!is_array($input)) $input = [];
} else {
    $input = $_POST;
}

// -- Normalize: our frontend's field names → API spec ---------------------
// main.js sends `fullname` (legacy) - the API expects `name`.
// attribution-capture.php sends `original_referrer` - API expects `referrer`.
// honeypot field name in our form is `hp_website` - API expects `website`.
$name      = trim((string) ($input['name']     ?? $input['fullname'] ?? ''));
$email     = trim((string) ($input['email']    ?? ''));
$phone     = trim((string) ($input['phone']    ?? ''));
$message   = trim((string) ($input['message']  ?? ''));
$subject   = trim((string) ($input['subject']  ?? ''));
$referrer  = trim((string) ($input['referrer'] ?? $input['original_referrer'] ?? ($_SERVER['HTTP_REFERER'] ?? '')));
$honeypot  = trim((string) ($input['website']  ?? $input['hp_website'] ?? ''));

// Quotation form on the homepage sends only an email and no name.
// Without a name the API will 400-reject. Substitute a sensible default
// so we don't lose the lead.
if (!$name && !empty($input['quotation'])) {
    $name = 'בקשת הצעת מחיר';
    if (!$message) {
        $message = 'משתמש מילא את טופס "הצעת מחיר חינם" בעמוד הבית.';
    }
}

// Pull message subject in if it exists (our contact form has a subject input)
if ($subject && $message && stripos($message, $subject) === false) {
    $message = "[$subject]\n$message";
} elseif ($subject && !$message) {
    $message = $subject;
}

// -- Build payload for the central API ------------------------------------
$payload = [
    'api_key'      => $config['api_key'],
    'name'         => $name,
    'email'        => $email,
    'phone'        => $phone,
    'message'      => $message,
    'landing_page' => $input['landing_page'] ?? '',
    'current_page' => $input['current_page'] ?? ($input['landing_page'] ?? ($_SERVER['HTTP_REFERER'] ?? '')),
    'referrer'     => $referrer,
    'utm_source'   => $input['utm_source']   ?? '',
    'utm_medium'   => $input['utm_medium']   ?? '',
    'utm_campaign' => $input['utm_campaign'] ?? '',
    'utm_term'     => $input['utm_term']     ?? '',
    'utm_content'  => $input['utm_content']  ?? '',
    'website'      => $honeypot,
];

// -- Send to central API --------------------------------------------------
$ch = curl_init($config['api_url']);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-Forwarded-For: ' . ($_SERVER['REMOTE_ADDR'] ?? ''),
    ],
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT        => $config['timeout'],
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// -- Pass-through response on success -------------------------------------
if ($httpCode > 0 && $response !== false) {
    http_response_code($httpCode);
    echo $response;
    exit;
}

// -- Fallback: API unreachable. Email Idan directly so the lead isn't lost.
error_log("SEO Platform API unreachable (code=$httpCode err=$curlError)");

$fallbackBody  = "ליד חדש (fallback — ה-API המרכזי לא הגיב):\n\n";
$fallbackBody .= "שם: $name\n";
$fallbackBody .= "מייל: $email\n";
$fallbackBody .= "טלפון: $phone\n";
$fallbackBody .= "הודעה: $message\n\n";
$fallbackBody .= "-- attribution --\n";
$fallbackBody .= "Landing: " . ($payload['landing_page'] ?: '-') . "\n";
$fallbackBody .= "Referrer: " . ($payload['referrer']     ?: '-') . "\n";
$fallbackBody .= "UTM source: " . ($payload['utm_source'] ?: 'organic') . "\n";

$headers  = "From: noreply@hlo-media.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

@mail($config['fallback_email'], 'ליד fallback - HloMedia', $fallbackBody, $headers);

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'תודה! נחזור אליך בהקדם.',
], JSON_UNESCAPED_UNICODE);
