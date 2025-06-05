<?php
// api/updateReservation.php

// --- Security Headers ---
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

require_once '../db/db_handler.php';
require_once '../handlers/emailHandler.php'; // Voor e-mailnotificaties

// --- Globale Error en Exception Handling (basis) ---
ini_set('display_errors', 0); // Zet op 0 in productie
ini_set('log_errors', 1);
// error_log_file = "/path/to/your/php-error.log"; // Stel dit in php.ini

$response = ['success' => false, 'message' => 'Ongeldig verzoek.'];

// --- Alleen POST-verzoeken toestaan ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    $response['message'] = 'Methode niet toegestaan. Alleen POST-verzoeken worden geaccepteerd.';
    echo json_encode($response);
    exit;
}

// --- Authenticatie/Autorisatie (ZEER AANBEVOLEN voor update acties) ---
// session_start(); // Start sessie indien nodig
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     http_response_code(401); // Unauthorized
//     $response['message'] = 'Niet geautoriseerd om deze actie uit te voeren.';
//     echo json_encode($response);
//     exit;
// }

// --- Input Validatie en Sanitization ---
$reservationId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$newStatus = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
$adminNotes = filter_input(INPUT_POST, 'admin_notes', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

$newStatus = $newStatus ? trim($newStatus) : null;
$adminNotes = $adminNotes ? trim($adminNotes) : '';

$errors = [];
if ($reservationId === false || $reservationId <= 0) {
    $errors[] = 'Ongeldig of ontbrekend reserverings ID.';
}
if (empty($newStatus)) {
    $errors[] = 'Status is verplicht.';
} else {
    $allowedStatuses = ['Pending', 'Accepted', 'Declined', 'Verlopen'];
    if (!in_array($newStatus, $allowedStatuses)) {
        $errors[] = 'Ongeldige status opgegeven. Toegestane statussen: ' . implode(', ', $allowedStatuses) . '.';
    }
}
if (strlen($adminNotes) > 1000) {
    $errors[] = 'Admin notities mogen maximaal 1000 tekens bevatten.';
}

if (!empty($errors)) {
    http_response_code(400); // Bad Request
    $response = ['success' => false, 'message' => 'Validatiefouten:', 'errors' => $errors];
    echo json_encode($response);
    exit;
}

try {
    $db = new DbHandler();
    $connection = $db->connect();

    if (!$connection) {
        error_log("updateReservation.php: Databaseverbinding mislukt in DbHandler.");
        http_response_code(503); // Service Unavailable
        $response['message'] = 'De database is momenteel niet beschikbaar.';
        echo json_encode($response);
        exit;
    }

    $connection->begin_transaction();

    // Stap 1: Haal de huidige reserveringsdetails op (voor e-mail en om te checken of reservering bestaat)
    $customerDetails = null;
    $stmt_select = $connection->prepare("SELECT name, email, status AS old_status FROM reservations WHERE id = ?");
    if (!$stmt_select) {
        error_log("SQL Prepare Error (select) in updateReservation.php: " . $connection->error);
        http_response_code(500);
        $response['message'] = 'Serverfout bij voorbereiden van database query (select).';
        $connection->rollback();
        echo json_encode($response);
        exit;
    }
    $stmt_select->bind_param("i", $reservationId);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404); // Not Found
        $response['message'] = 'Reservering met ID ' . $reservationId . ' niet gevonden.';
        $connection->rollback();
        $stmt_select->close();
        echo json_encode($response);
        exit;
    }
    $customerDetails = $result->fetch_assoc();
    $oldStatus = $customerDetails['old_status'];
    $stmt_select->close();

    // Stap 2: Update de status in de database als de status daadwerkelijk verandert
    if ($oldStatus === $newStatus) {
        $response = ['success' => true, 'message' => 'Reserveringsstatus is niet gewijzigd (was al ' . $newStatus . ').'];
        http_response_code(200); // OK, but no change
        $connection->commit(); // Commit, want er is geen fout, alleen geen wijziging.
    } else {
        $stmt_update = $connection->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        if (!$stmt_update) {
            error_log("SQL Prepare Error (update) in updateReservation.php: " . $connection->error);
            http_response_code(500);
            $response['message'] = 'Serverfout bij voorbereiden van database query (update).';
            $connection->rollback();
            echo json_encode($response);
            exit;
        }
        $stmt_update->bind_param("si", $newStatus, $reservationId);

        if ($stmt_update->execute()) {
            if ($stmt_update->affected_rows > 0) {
                $response = ['success' => true, 'message' => 'Reserveringsstatus succesvol bijgewerkt naar ' . $newStatus . '.'];
                http_response_code(200); // OK

                // Stap 3: Verstuur e-mail naar klant indien status Accepted of Declined is
                // en de status daadwerkelijk is veranderd.
                if (($newStatus === 'Accepted' || $newStatus === 'Declined')) {
                    if (!sendReservationStatusUpdateEmail($customerDetails, $reservationId, $newStatus, $adminNotes)) {
                        error_log("updateReservation.php: Kon status update e-mail niet versturen naar klant voor reservering ID " . $reservationId);
                        // $response['email_warning'] = 'Status bijgewerkt, maar e-mailnotificatie naar klant mislukt.';
                    }
                }
                $connection->commit();
            } else {
                // Dit zou niet moeten gebeuren als de eerdere check op num_rows > 0 slaagde en oldStatus != newStatus
                http_response_code(404); // Not Found or no change
                $response = ['success' => false, 'message' => 'Reservering niet gevonden of status kon niet worden bijgewerkt.'];
                $connection->rollback();
            }
        } else {
            error_log("SQL Execute Error (update) in updateReservation.php: " . $stmt_update->error);
            http_response_code(500);
            $response['message'] = 'Fout bij het bijwerken van de status in de database.';
            $connection->rollback();
        }
        $stmt_update->close();
    }

} catch (mysqli_sql_exception $e) {
    error_log("MySQLi SQL Exception in updateReservation.php: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
    http_response_code(500);
    $response['message'] = 'Databasefout opgetreden bij het bijwerken van de reservering.';
    if (isset($connection) && $connection->ping()) { try { $connection->rollback(); } catch (Exception $re) { error_log("Rollback exception: " . $re->getMessage());} }
} catch (Exception $e) {
    error_log("Algemene Exception in updateReservation.php: " . $e->getMessage());
    http_response_code(500);
    $response['message'] = 'Er is een onverwachte serverfout opgetreden.';
    if (isset($connection) && $connection->ping()) { try { $connection->rollback(); } catch (Exception $re) { error_log("Rollback exception: " . $re->getMessage());} }
} finally {
    if (isset($db) && method_exists($db, 'disconnect')) {
        $db->disconnect();
    }
}

echo json_encode($response);
exit;
?>
