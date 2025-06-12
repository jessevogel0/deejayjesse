<?php
// api/deleteReservation.php (CRON SCRIPT)
// Doel: Zet 'Pending' reserveringen ouder dan X dagen op 'Verlopen'.

// Basis CRON setup
set_time_limit(300); // 5 minuten, pas aan indien nodig
ini_set('display_errors', 0);
ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/../logs/cron_delete_reservations.log'); // Optioneel: apart logbestand
error_reporting(E_ALL);

require_once __DIR__ . '/../db/db_handler.php';

echo "CRON Job (api/deleteReservation.php): Gestart - " . date('Y-m-d H:i:s') . "\n";

$daysThreshold = 14; // Reserveringen ouder dan 14 dagen met status 'Pending'

try {
    $pdo = connectDB(); //

    if ($pdo === null) {
        $errorMessage = "CRON ERROR: Databaseverbinding mislukt.\n";
        error_log($errorMessage);
        echo $errorMessage;
        exit(1);
    }

    echo "INFO: Verbonden met de database.\n";
    echo "INFO: Controle van 'Pending' reserveringen ouder dan {$daysThreshold} dagen...\n";

    $updatedRows = expireOldPendingReservations($pdo, $daysThreshold); //

    if ($updatedRows > 0) {
        $successMessage = "CRON SUCCESS: {$updatedRows} reservering(en) bijgewerkt naar 'Verlopen'.\n";
        error_log($successMessage);
        echo $successMessage;
    } else {
        $infoMessage = "CRON INFO: Geen 'Pending' reserveringen gevonden om bij te werken, of er was een probleem.\n";
        echo $infoMessage;
    }

} catch (PDOException $e) {
    $errorMessage = "CRON PDOException: " . $e->getMessage() . "\n";
    error_log($errorMessage);
    echo $errorMessage;
    exit(1);
} catch (Exception $e) {
    $errorMessage = "CRON Algemene Exception: " . $e->getMessage() . "\n";
    error_log($errorMessage);
    echo $errorMessage;
    exit(1);
}

echo "CRON Job (api/deleteReservation.php): Voltooid - " . date('Y-m-d H:i:s') . "\n";
exit(0);
?>