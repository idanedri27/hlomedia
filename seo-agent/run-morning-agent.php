<?php
/**
 * /opt/seo-platform/api/run-morning-agent.php
 *
 * HTTP endpoint that triggers the existing Node-based morning-email-agent
 * (at /var/www/morning-email-agent/) without touching its code.
 *
 * Called by an n8n workflow on a schedule. The endpoint sudo-execs a
 * root-owned wrapper script (/usr/local/bin/run-morning-agent.sh) which
 * runs the Node script under nvm.
 *
 * URL:    POST or GET /run-morning-agent.php?script=<morning|evening>
 * Auth:   X-API-Key header (ADMIN_API_KEY)
 *
 * Response (JSON):
 *   {
 *     "script": "morning",
 *     "exit_code": 0,
 *     "duration_ms": 4321,
 *     "ran_at": "2026-05-18T22:00:00+00:00",
 *     "stdout": "...full agent output...",
 *     "stderr": "...errors if any...",
 *     "summary": "extracted DAILY BRIEF section (when present)"
 *   }
 */

define('ENV_PATH', '/opt/seo-platform/config/.env');

function load_env($path) {
    $env = [];
    if (!file_exists($path)) return $env;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
    return $env;
}

header('Content-Type: application/json; charset=utf-8');

$ENV = load_env(ENV_PATH);
$ADMIN_KEY = $ENV['ADMIN_API_KEY'] ?? '';

if (!$ADMIN_KEY || ($_SERVER['HTTP_X_API_KEY'] ?? '') !== $ADMIN_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Script selection (morning = index.js, evening = endOfDayScan.js)
$script = $_GET['script'] ?? $_POST['script'] ?? '';
if ($script === '') {
    $raw = file_get_contents('php://input');
    if ($raw) {
        $body = json_decode($raw, true);
        $script = $body['script'] ?? '';
    }
}
$script = $script ?: 'morning';
if (!in_array($script, ['morning', 'evening'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'script must be "morning" or "evening"']);
    exit;
}

// Spawn the wrapper. proc_open is preferred over shell_exec so we get
// both stdout + stderr separately and the exit code.
$start = microtime(true);
$descriptors = [
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];
$cmd = ['sudo', '-n', '/usr/local/bin/run-morning-agent.sh', $script];
$proc = proc_open($cmd, $descriptors, $pipes);

if (!is_resource($proc)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to spawn wrapper script']);
    exit;
}

stream_set_blocking($pipes[1], true);
stream_set_blocking($pipes[2], true);
$stdout = stream_get_contents($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$exit_code = proc_close($proc);

$duration_ms = (int) ((microtime(true) - $start) * 1000);

// Best-effort: extract the human-readable DAILY BRIEF section
// (the Node agent prints it between "📝 DAILY BRIEF:" and "📲 Telegram")
$summary = '';
if (preg_match('/📝 DAILY BRIEF:\s*\n+(.+?)(?:📲|$)/su', $stdout, $m)) {
    $summary = trim($m[1]);
}

http_response_code($exit_code === 0 ? 200 : 500);
echo json_encode([
    'script'      => $script,
    'exit_code'   => $exit_code,
    'duration_ms' => $duration_ms,
    'ran_at'      => date('c'),
    'stdout'      => $stdout,
    'stderr'      => $stderr,
    'summary'     => $summary,
], JSON_UNESCAPED_UNICODE);
