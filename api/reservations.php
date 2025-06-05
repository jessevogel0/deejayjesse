<?php
// api/reservations.php

// --- Security Headers ---
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

require_once '../db/db_handler.php'; // Zorg dat het pad correct is

// --- Globale Error en Exception Handling (basis) ---
ini_set('display_errors', 0); // Zet op 0 in productie
ini_set('log_errors', 1);
// error_log_file = "/path/to/your/php-error.log"; // Stel dit in php.ini

$response = ['success' => false, 'message' => 'Ongeldig verzoek.', 'reservations' => []];

// --- Alleen GET-verzoeken toestaan ---
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    $response['message'] = 'Methode niet toegestaan. Alleen GET-verzoeken worden geaccepteerd.';
    echo json_encode($response);
    exit;
}

// --- Authenticatie/Autorisatie (optioneel, maar aanbevolen voor admin data) ---
// session_start(); // Start sessie indien nodig
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     http_response_code(401); // Unauthorized
//     $response['message'] = 'Niet geautoriseerd om deze gegevens op te halen.';
//     echo json_encode($response);
//     exit;
// }

try {
    $pdo = connectDB(); // Gebruik de PDO connectDB functie

    if ($pdo === null) {
        error_log("reservations.php: Databaseverbinding mislukt via connectDB().");
        http_response_code(503); // Service Unavailable
        $response['message'] = 'De database is momenteel niet beschikbaar.';
        echo json_encode($response);
        exit;
    }

    // Haal alle reserveringen op met de PDO functie
    // De getReservationsFromDB functie in db_handler.php bevat al de correcte kolomselectie
    $reservations = getReservationsFromDB($pdo, null, 'submission_timestamp_desc'); 

    if ($reservations !== false) { // getReservationsFromDB retourneert een array, geen false bij succes
        $response = ['success' => true, 'reservations' => $reservations];
        http_response_code(200); // OK
    } else {
        // Dit pad wordt bereikt als getReservationsFromDB false retourneert, wat niet zou moeten
        // gebeuren als er geen PDOExceptions zijn.
        error_log("reservations.php: getReservationsFromDB retourneerde onverwacht false.");
        http_response_code(500); // Internal Server Error
        $response['message'] = 'Fout bij het ophalen van reserveringen uit de database.';
    }

} catch (PDOException $e) { // Specifieke PDO-foutafhandeling
    error_log("PDO Exception in reservations.php: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
    http_response_code(500);
    $response['message'] = 'Databasefout opgetreden bij het ophalen van reserveringen.';
} catch (Exception $e) { // Algemene foutafhandeling
    error_log("Algemene Exception in reservations.php: " . $e->getMessage());
    http_response_code(500);
    $response['message'] = 'Er is een onverwachte serverfout opgetreden.';
}

echo json_encode($response);
exit;
?>
    