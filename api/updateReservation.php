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
// api/updateReservation.php

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
header('Access-Control-Allow-Methods: POST, PUT, OPTIONS');
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendJsonResponseAndExit(405, false, 'Methode niet toegestaan.');
}

require_once __DIR__ . '/../db/db_handler.php';
require_once __DIR__ . '/../handlers/emailHandler.php'; // Voor e-mailnotificaties

$inputData = [];
if (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
    $inputData = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE || $inputData === null) {
        sendJsonResponseAndExit(400, false, 'Ongeldige JSON input.');
    }
} else {
    $inputData = $_POST; // Voor x-www-form-urlencoded, zoals admin.js gebruikt
}

$errors = [];
$reservationId = filter_var($inputData['id'] ?? null, FILTER_VALIDATE_INT);
$newFrontendStatus = isset($inputData['status']) ? trim(filter_var($inputData['status'], FILTER_SANITIZE_SPECIAL_CHARS)) : null;
$adminNotes = isset($inputData['admin_notes']) ? trim(filter_var($inputData['admin_notes'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES)) : '';

if ($reservationId === false || $reservationId <= 0) {
    $errors['id'] = 'Ongeldig of ontbrekend reserverings ID.';
}

// Statussen die de frontend (admin.js) kan sturen
$allowedFrontendStatuses = ['Pending', 'Accepted', 'Declined', 'Verlopen'];
// Mapping naar de ENUM waarden in de database
$statusMapToDB = [
    'Pending'    => 'Pending',
    'Accepted'   => 'Geaccepteerd',
    'Declined'   => 'Geweigerd',
    'Verlopen'   => 'Verlopen'
];

if (empty($newFrontendStatus)) {
    $errors['status'] = 'Status is verplicht.';
} elseif (!in_array($newFrontendStatus, $allowedFrontendStatuses)) {
    $errors['status'] = 'Ongeldige status ontvangen. Toegestaan: ' . implode(', ', $allowedFrontendStatuses) . '.';
}

if (strlen($adminNotes) > 1000) {
    $errors['admin_notes'] = 'Admin notities mogen maximaal 1000 tekens bevatten.';
}

if (!empty($errors)) {
    sendJsonResponseAndExit(400, false, 'Validatiefouten.', null, $errors);
}

$newDbStatus = $statusMapToDB[$newFrontendStatus]; // Vertaal naar DB enum waarde

try {
    $pdo = connectDB();
    if ($pdo === null) {
        sendJsonResponseAndExit(503, false, 'Database is momenteel niet beschikbaar.');
    }

    $pdo->beginTransaction();

    $currentReservation = getReservationById($pdo, $reservationId); //
    if (!$currentReservation) {
        $pdo->rollBack();
        sendJsonResponseAndExit(404, false, "Reservering met ID {$reservationId} niet gevonden.");
    }

    if ($currentReservation['status'] === $newDbStatus) {
        $pdo->commit();
        sendJsonResponseAndExit(200, true, "Status is niet gewijzigd (was al {$currentReservation['status']}).");
    }

    $updateSuccess = updateReservationStatusInDB($pdo, $reservationId, $newDbStatus); //

    if ($updateSuccess) {
        $pdo->commit();

        // Stuur e-mailnotificatie naar klant als status verandert naar Geaccepteerd of Geweigerd
        // sendReservationStatusUpdateEmail verwacht de frontend/Engelse status
        if (($newFrontendStatus === 'Accepted' || $newFrontendStatus === 'Declined')) {
             $customerEmailDetails = [
                'name' => $currentReservation['full_name'],
                'email' => $currentReservation['email']
            ];
            if (!sendReservationStatusUpdateEmail($customerEmailDetails, $reservationId, $newFrontendStatus, $adminNotes)) { //
                error_log("API updateReservation.php: E-mailnotificatie naar klant mislukt voor ID {$reservationId}.");
            }
        }
        sendJsonResponseAndExit(200, true, "Reservering #{$reservationId} succesvol bijgewerkt naar status: {$newDbStatus}.");
    } else {
        $pdo->rollBack();
        sendJsonResponseAndExit(500, false, "Fout bij het bijwerken van reservering #{$reservationId}. De status is mogelijk niet gewijzigd.");
    }

} catch (PDOException $e) {
    if ($pdo && $pdo->inTransaction()) $pdo->rollBack();
    error_log("API PDOException in updateReservation.php: " . $e->getMessage());
    sendJsonResponseAndExit(500, false, 'Databasefout bij het bijwerken.');
}
?>  