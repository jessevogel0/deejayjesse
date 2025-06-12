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
            define('DEBUG_MODE', true); // TIJDELIJK AANZETTEN VOOR DEBUGGING
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
// api/addReservation.php

// --- Utility Functions (KOPIEER DE CODEBLOK VAN HET VORIGE ANTWOORD HIER) ---
// ...
// if (!function_exists('sendJsonResponseAndExit')) { ... }
// if (!function_exists('setSecurityHeaders')) { ... }
// if (!function_exists('initializeErrorHandling')) { ... }
// --- Einde Utility Functions ---

// Configuratie (eventueel in een apart config.php bestand en dan includen)
// define('DEBUG_MODE', true); // Zet op true voor development, false voor productie

initializeErrorHandling();
setSecurityHeaders();

header('Access-Control-Allow-Origin: *'); // Aanpassen voor productie!
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponseAndExit(405, false, 'Methode niet toegestaan.');
}

// **BELANGRIJK: CSRF Token Validatie**
// Zie vorige antwoord voor de volledige uitleg. Dit blijft een risico.
error_log("SECURITY WARNING: addReservation.php - CSRF token validatie is niet (correct) geïmplementeerd.");

require_once __DIR__ . '/../db/db_handler.php';

$inputData = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE || $inputData === null || !is_array($inputData)) {
    sendJsonResponseAndExit(400, false, 'Ongeldige JSON input ontvangen.');
}

// Validatie
$errors = [];
// `termsConditions` verwijderd uit $requiredFields als het niet meer opgeslagen wordt.
// Echter, je JavaScript vereist het nog wel.
// Als de checkbox moet blijven, maar de waarde niet opgeslagen, dan blijft de validatie hier.
$requiredFields = ['fullName', 'email', 'date', 'package', 'termsConditions', 'phone'];

foreach ($requiredFields as $field) {
    if (!isset($inputData[$field])) {
        $errors[$field] = "Veld '{$field}' is verplicht.";
        continue;
    }
    if (is_string($inputData[$field]) && trim($inputData[$field]) === '') {
        $errors[$field] = "Veld '{$field}' mag niet leeg zijn.";
    } elseif ($field === 'termsConditions' && $inputData[$field] !== 'agreed') {
        // Deze check is nog steeds relevant als je de frontend-eis wilt handhaven.
        $errors[$field] = "De algemene voorwaarden ('termsConditions') moeten geaccepteerd zijn.";
    }
}


if (isset($inputData['email']) && !empty(trim($inputData['email'])) && !filter_var(trim($inputData['email']), FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Ongeldig e-mailadres formaat.";
}

if (isset($inputData['date']) && !empty(trim($inputData['date']))) {
    try {
        $dateObj = new DateTime(trim($inputData['date']));
        if ($dateObj->format('Y-m-d') !== trim($inputData['date'])) {
            throw new Exception("Ongeldig datumformaat.");
        }
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        if ($dateObj < $today) {
            $errors['date'] = "De reserveringsdatum moet vandaag of in de toekomst liggen.";
        }
    } catch (Exception $e) {
        $errors['date'] = "Ongeldig datumformaat voor 'date'. Gebruik YYYY-MM-DD.";
    }
}

$reservationTime = null;
if (isset($inputData['time']) && !empty(trim($inputData['time']))) {
    if (!preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', trim($inputData['time']))) {
        $errors['time'] = "Ongeldig tijdformaat voor 'time'. Gebruik HH:MM.";
    } else {
        $reservationTime = trim($inputData['time']);
    }
}

if (isset($inputData['package']) && !empty(trim($inputData['package'])) && !in_array(trim($inputData['package']), ['standaard', 'all-inclusive', 'alleen-dj'])) {
    $errors['package'] = "Ongeldige waarde voor 'package'.";
}

if (!empty($errors)) {
    sendJsonResponseAndExit(400, false, 'Validatiefouten bij de input.', null, $errors);
}

// AANGEPAST: `terms_conditions` verwijderd uit de data die naar de DB gaat.
$reservationDataForDB = [
    'full_name'        => trim($inputData['fullName']),
    'email'            => trim($inputData['email']),
    'phone'            => trim($inputData['phone']),
    'reservation_date' => trim($inputData['date']),
    'reservation_time' => $reservationTime,
    'package_type'     => trim($inputData['package']),
    'remarks'          => isset($inputData['remarks']) && !empty(trim($inputData['remarks'])) ? trim($inputData['remarks']) : null,
    'status'           => 'Pending'
    // 'terms_conditions' is hier verwijderd
];

// De expliciete check voor `termsConditions` voordat de DB-call wordt gedaan, blijft relevant
// als de checkbox in het formulier een functionele eis is, ook al sla je het niet op.
if (!isset($inputData['termsConditions']) || $inputData['termsConditions'] !== 'agreed') {
    sendJsonResponseAndExit(400, false, 'De algemene voorwaarden moeten geaccepteerd zijn.', null, ['termsConditions' => 'Acceptatie van algemene voorwaarden is vereist.']);
}


try {
    $pdo = connectDB();
    if ($pdo === null) {
        sendJsonResponseAndExit(503, false, 'Database is momenteel niet beschikbaar.');
    }

    $reservationId = addReservationToDB($pdo, $reservationDataForDB); //

    if ($reservationId) {
        // Optioneel: e-mails versturen
        // require_once __DIR__ . '/../handlers/emailHandler.php';
        // ... (logica voor e-mail data)
        // sendReservationConfirmationEmail($emailData, $reservationId);
        // sendAdminNotificationEmail($emailData, $reservationId);

        sendJsonResponseAndExit(201, true, 'Reservering succesvol aangemaakt!', ['id' => $reservationId]);
    } else {
        sendJsonResponseAndExit(500, false, 'Reservering kon niet worden opgeslagen in de database.');
    }

} catch (PDOException $e) {
    error_log("API PDOException in addReservation.php: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
    sendJsonResponseAndExit(500, false, 'Databasefout bij het verwerken van uw aanvraag.');
}
?>