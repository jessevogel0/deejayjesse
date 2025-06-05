<?php
// api/deleteReservation.php
header('Content-Type: application/json');
require_once '../db/db_handler.php'; // Zorg dat het pad correct is

// Start de sessie indien nodig (als je admin authenticatie gebruikt)
// session_start();
// if (!isset($_SESSION['admin_logged_in'])) {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized']);
//     exit;
// }

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reservation_id'])) {
        $reservationId = $_POST['reservation_id'];
        $newStatus = 'Verlopen'; // De status voor soft-delete

        // Maak verbinding met de database
        $db = new DbHandler(); // db_handler.php moet de klasse DbHandler bevatten
        $connection = $db->connect(); // Zorg dat connect() de mysqli verbinding retourneert

        if ($connection) {
            // Gebruik prepared statements om SQL-injectie te voorkomen
            $stmt = $connection->prepare("UPDATE reservations SET status = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("si", $newStatus, $reservationId);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response = ['success' => true, 'message' => 'Reservering succesvol gemarkeerd als verlopen.'];
                    } else {
                        $response = ['success' => false, 'message' => 'Reservering niet gevonden of status is al verlopen.'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'Fout bij het bijwerken van de reservering: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['success' => false, 'message' => 'Fout bij het voorbereiden van de query: ' . $connection->error];
            }
            $db->disconnect(); // Sluit de verbinding
        } else {
            $response = ['success' => false, 'message' => 'Databaseverbinding mislukt.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Reservation ID niet meegegeven.'];
    }
}

echo json_encode($response);
?>
