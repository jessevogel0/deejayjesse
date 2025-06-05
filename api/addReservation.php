<?php
// api/addReservation.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Voor ontwikkeling: staat CORS toe vanaf elke origin
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$dbHandlerPath = __DIR__ . '/../db/db_handler.php';

error_log("DEBUG: addReservation.php - Berekend pad voor db_handler.php: " . $dbHandlerPath);

if (!file_exists($dbHandlerPath)) {
    error_log("ERROR: addReservation.php - db_handler.php NIET GEVONDEN op pad: " . $dbHandlerPath);
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Serverfout: Database handler component niet gevonden. Controleer het pad: " . $dbHandlerPath
    ]);
    exit;
} else {
    error_log("DEBUG: addReservation.php - db_handler.php WEL GEVONDEN op pad: " . $dbHandlerPath);
}

require_once $dbHandlerPath; // Laadt de PDO functies

$data = [];
if (!empty($_POST)) {
    $data = $_POST;
    error_log("DEBUG: addReservation.php - Data ontvangen via \$_POST: " . print_r($data, true));
} else {
    $jsonPayload = file_get_contents('php://input');
    if (!empty($jsonPayload)) {
        $data = json_decode($jsonPayload, true);
        error_log("DEBUG: addReservation.php - Data ontvangen via JSON fallback: " . print_r($data, true));
    }
}

if ($data === null || !is_array($data) || empty($data)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Fout: Ongeldige of geen data ontvangen."
    ]);
    exit;
}

// Map de ontvangen keys naar de keys die de addReservationToDB functie verwacht.
// DIT IS DE CORRECTE VERSIE DIE OVEREENKOMT MET JE DATABASE:
$reservationDataForDB = [
    'full_name'        => $data['fullName'] ?? $data['name'] ?? null,
    'email'            => $data['email'] ?? null,
    'phone'            => $data['phone'] ?? null,
    'reservation_date' => $data['date'] ?? $data['reservation_date'] ?? null,
    'reservation_time' => $data['time'] ?? null, // Correct toegevoegd
    'package_type'     => $data['package'] ?? $data['event_type'] ?? null, // Correct: package_type
    'remarks'          => $data['remarks'] ?? $data['comments'] ?? null,   // Correct: remarks
    'status'           => 'Pending', // Standaardstatus bij nieuwe reservering
    'terms_conditions' => $data['termsConditions'] ?? null
];

// Basisvalidatie voor verplichte velden
if (empty($reservationDataForDB['full_name']) || empty($reservationDataForDB['email']) || empty($reservationDataForDB['reservation_date']) || empty($reservationDataForDB['terms_conditions'])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Fout: Verplichte velden (naam, e-mail, datum, algemene voorwaarden) ontbreken of zijn leeg."
    ]);
    exit;
}

try {
    $pdo = connectDB(); // Maak PDO-verbinding

    if ($pdo === null) {
        error_log("addReservation.php: Kan geen databaseverbinding maken via connectDB().");
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Serverfout: Kan geen databaseverbinding maken."
        ]);
        exit;
    }

    $success = addReservationToDB($pdo, $reservationDataForDB); // Roep de PDO functie aan

    if ($success) {
        echo json_encode([
            "success" => true,
            "message" => "Reservering succesvol toegevoegd!"
        ]);
    } else {
        error_log("WARNING: addReservation.php - addReservationToDB retourneerde FALSE. Data: " . print_r($reservationDataForDB, true));
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Serverfout: Reservering kon niet worden opgeslagen (onbekende databasefout na validatie)."
        ]);
    }

} catch (PDOException $e) {
    error_log("ERROR: API PDOException in addReservation.php: " . $e->getMessage() . " | Data: " . print_r($reservationDataForDB, true));
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Serverfout: Databasefout bij opslaan reservering. Details: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("ERROR: API Algemene Exception in addReservation.php: " . $e->getMessage() . " | Data: " . print_r($reservationDataForDB, true));
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Serverfout: Algemene fout bij verwerken reservering. Details: " . $e->getMessage()
    ]);
}
?>
