<?php
// --- Utility Functions (plaats dit bovenaan elk API script dat HTTP responses geeft) ---

/**
 * Stuurt een JSON response en stopt de scriptuitvoering.
 */
if (!function_exists('sendJsonResponseAndExit')) {
    function sendJsonResponseAndExit(int $httpStatusCode, bool $success, string $message, ?array $data = null, ?array $errors = null): void {
        http_response_code($httpStatusCode);
        $response = ['success' => $success, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($response);
        exit;
    }
}

/**
 * Stelt basis security headers in.
 */
if (!function_exists('setSecurityHeaders')) {
    function setSecurityHeaders(): void {
        if (headers_sent()) return;
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' https://cdnjs.cloudflare.com; object-src 'none'; frame-ancestors 'none';");
    }
}

/**
 * Initialiseert basis error en exception handling.
 */
if (!function_exists('initializeErrorHandling')) {
    function initializeErrorHandling(): void {
        if (!defined('DEBUG_MODE')) {
            define('DEBUG_MODE', false); // Default naar false (productie)
        }

        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        // error_reporting(E_ALL); // Uncomment voor development

        set_error_handler(function ($severity, $message, $file, $line) {
            error_log("PHP Error: [$severity] $message in $file on line $line");
            $displayMessage = (defined('DEBUG_MODE') && DEBUG_MODE === true)
                ? "Server Error: {$message} in {$file} on line {$line}"
                : 'Er is een interne serverfout opgetreden (Ref: E).';
            sendJsonResponseAndExit(500, false, $displayMessage);
        });

        set_exception_handler(function ($exception) {
            error_log("PHP Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\nStack trace:\n" . $exception->getTraceAsString());
            $displayMessage = (defined('DEBUG_MODE') && DEBUG_MODE === true)
                ? "Server Exception: " . $exception->getMessage()
                : 'Er is een kritieke serverfout opgetreden (Ref: X).';
            sendJsonResponseAndExit(500, false, $displayMessage);
        });
    }
}
// --- Einde Utility Functions ---
?>


<?php
// api/reservations.php

// --- Utility Functions (KOPIEER DE CODEBLOK HIERBOVEN IN DEZE SECTIE) ---
/**
 * Stuurt een JSON response en stopt de scriptuitvoering.
 */
if (!function_exists('sendJsonResponseAndExit')) {
    function sendJsonResponseAndExit(int $httpStatusCode, bool $success, string $message, ?array $data = null, ?array $errors = null): void {
        http_response_code($httpStatusCode);
        $response = ['success' => $success, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($response);
        exit;
    }
}
/**
 * Stelt basis security headers in.
 */
if (!function_exists('setSecurityHeaders')) {
    function setSecurityHeaders(): void {
        if (headers_sent()) return;
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
    }
}
/**
 * Initialiseert basis error en exception handling.
 */
if (!function_exists('initializeErrorHandling')) {
    function initializeErrorHandling(): void {
        if (!defined('DEBUG_MODE')) {
            define('DEBUG_MODE', false); 
        }
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        set_error_handler(function ($severity, $message, $file, $line) {
            error_log("PHP Error: [$severity] $message in $file on line $line");
            $displayMessage = (defined('DEBUG_MODE') && DEBUG_MODE === true)
                ? "Server Error: {$message} in {$file} on line {$line}"
                : 'Er is een interne serverfout opgetreden (Ref: E).';
            sendJsonResponseAndExit(500, false, $displayMessage);
        });
        set_exception_handler(function ($exception) {
            error_log("PHP Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\nStack trace:\n" . $exception->getTraceAsString());
            $displayMessage = (defined('DEBUG_MODE') && DEBUG_MODE === true)
                ? "Server Exception: " . $exception->getMessage()
                : 'Er is een kritieke serverfout opgetreden (Ref: X).';
            sendJsonResponseAndExit(500, false, $displayMessage);
        });
    }
}
// --- Einde Utility Functions ---

// define('DEBUG_MODE', true); // Voor development

initializeErrorHandling();
setSecurityHeaders();

header('Access-Control-Allow-Origin: *'); // Aanpassen voor productie!
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json');


// --- Authenticatie/Autorisatie (STERK AANBEVOLEN) ---
/*
if (!isset($_SESSION)) { session_start(); }
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    sendJsonResponseAndExit(401, false, 'Niet geautoriseerd.');
}
*/
// Voor nu: publiek toegankelijk, wat een risico is.

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponseAndExit(405, false, 'Methode niet toegestaan.');
}

require_once __DIR__ . '/../db/db_handler.php';

$statusFilter = isset($_GET['status']) ? filter_var(trim($_GET['status']), FILTER_SANITIZE_SPECIAL_CHARS) : null;
$searchTerm = isset($_GET['search']) ? filter_var(trim($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS) : null;
$sortOrder = isset($_GET['sort']) ? filter_var(trim($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS) : 'submission_timestamp DESC';

// Valideer status (optioneel maar goed)
$allowedStatuses = ['Pending', 'Accepted', 'Declined', 'Verlopen', 'Geaccepteerd', 'Geweigerd', null, ''];
if (!in_array($statusFilter, $allowedStatuses, true)) {
    sendJsonResponseAndExit(400, false, "Ongeldige statusfilter waarde.");
}
if ($statusFilter === '') $statusFilter = null;

// Valideer sorteerorde
$allowedSorts = [
    'reservation_date ASC', 'reservation_date DESC', 
    'id ASC', 'id DESC', 
    'submission_timestamp ASC', 'submission_timestamp DESC'
];
if (!in_array($sortOrder, $allowedSorts, true)) {
    $sortOrder = 'submission_timestamp DESC'; // Veilige default
}

try {
    $pdo = connectDB();
    if ($pdo === null) {
        sendJsonResponseAndExit(503, false, 'Database is momenteel niet beschikbaar.');
    }

    $reservations = getReservationsFromDB($pdo, $statusFilter, $searchTerm, $sortOrder); //

    sendJsonResponseAndExit(200, true, 'Reserveringen succesvol opgehaald.', ['reservations' => $reservations]);

} catch (PDOException $e) {
    error_log("API PDOException in reservations.php: " . $e->getMessage());
    sendJsonResponseAndExit(500, false, 'Databasefout bij het ophalen van reserveringen.');
}
?>