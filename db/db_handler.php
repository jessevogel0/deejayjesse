<?php
// /db/db_handler.php

/**
 * Maakt een PDO-verbinding met de database.
 *
 * @return PDO|null Het PDO-object bij succes, anders null.
 */
function connectDB(): ?PDO {
    $dbHost = 'localhost';
    $dbName = 'djjesse'; // Zorg dat dit de juiste databasenaam is
    $dbUser = 'root';    // Zorg dat dit de juiste gebruikersnaam is
    $dbPass = '';        // Zorg dat dit het juiste wachtwoord is
    $charset = 'utf8mb4';
    
    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, $dbUser, $dbPass, $options);
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Database Connection Error in connectDB(): " . $e->getMessage());
        return null;
    }
}

/**
 * Voegt een nieuwe reservering toe aan de database met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param array $data De reserveringsdata, inclusief 'full_name', 'email', 'phone', 'reservation_date', 'reservation_time', 'package_type', 'remarks', 'status', 'terms_conditions'.
 * @return bool True bij succesvolle invoeging, anders false.
 */
function addReservationToDB(PDO $pdo, array $data): bool
{
    $sql = "INSERT INTO reservations (full_name, email, phone, reservation_date, reservation_time, package_type, remarks, status, terms_conditions)
            VALUES (:full_name, :email, :phone, :reservation_date, :reservation_time, :package_type, :remarks, :status, :terms_conditions)";
    
    try {
        $stmt = $pdo->prepare($sql);

        // Bind de waarden aan de parameters. Let op de correcte kolomnamen.
        $stmt->bindValue(':full_name', $data['full_name'] ?? null);
        $stmt->bindValue(':email', $data['email'] ?? null);
        $stmt->bindValue(':phone', $data['phone'] ?? null);
        $stmt->bindValue(':reservation_date', $data['reservation_date'] ?? null);
        $stmt->bindValue(':reservation_time', $data['reservation_time'] ?? null);
        $stmt->bindValue(':package_type', $data['package_type'] ?? null);
        $stmt->bindValue(':remarks', $data['remarks'] ?? null);
        $stmt->bindValue(':status', $data['status'] ?? 'Pending'); // Standaard 'Pending' indien niet aanwezig
        $stmt->bindValue(':terms_conditions', $data['terms_conditions'] ?? null);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Fout bij addReservationToDB: " . $e->getMessage());
        return false;
    }
}

/**
 * Haalt alle reserveringen op uit de database met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param string|null $searchTerm Optionele zoekterm om reserveringen te filteren.
 * @param string $sortOrder De sorteervolgorde (bijv. 'submission_timestamp_desc').
 * @return array Een array van reserveringen.
 */
function getReservationsFromDB(PDO $pdo, ?string $searchTerm, string $sortOrder): array
{
    // SELECT alle benodigde kolommen met de correcte namen
    $sql = "SELECT id, full_name, email, phone, reservation_date, reservation_time, package_type, remarks, status, submission_timestamp FROM reservations";
    $params = [];
    $whereClauses = [];

    if ($searchTerm) {
        $whereClauses[] = "(full_name LIKE :searchTerm OR email LIKE :searchTerm OR phone LIKE :searchTerm OR remarks LIKE :searchTerm)";
        $params[':searchTerm'] = '%' . $searchTerm . '%';
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    // Bepaal de sorteervolgorde (pas 'date_asc'/'date_desc' aan naar 'reservation_date'/'submission_timestamp')
    switch ($sortOrder) {
        case 'date_asc': // Sorteert op reservation_date
            $sql .= " ORDER BY reservation_date ASC, reservation_time ASC";
            break;
        case 'date_desc': // Sorteert op reservation_date
            $sql .= " ORDER BY reservation_date DESC, reservation_time DESC";
            break;
        case 'id_asc':
            $sql .= " ORDER BY id ASC";
            break;
        case 'id_desc':
            $sql .= " ORDER BY id DESC";
            break;
        case 'submission_timestamp_asc':
            $sql .= " ORDER BY submission_timestamp ASC";
            break;
        case 'submission_timestamp_desc':
        default:
            $sql .= " ORDER BY submission_timestamp DESC"; // Standaard sortering
            break;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Haalt één specifieke reservering op basis van ID met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param int $reservationId Het unieke ID van de reservering.
 * @return array|false De reserveringsdata, of false als niet gevonden.
 */
function getReservationById(PDO $pdo, int $reservationId)
{
    $sql = "SELECT id, full_name, email, phone, reservation_date, reservation_time, package_type, remarks, status, submission_timestamp FROM reservations WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $reservationId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}

/**
 * Werkt de status van een specifieke reservering bij in de database met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param int $reservationId Het ID van de reservering.
 * @param string $newStatus De nieuwe status.
 * @return bool True bij succesvolle update, anders false.
 */
function updateReservationStatusInDB(PDO $pdo, int $reservationId, string $newStatus): bool
{
    $sql = "UPDATE reservations SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $newStatus);
    $stmt->bindValue(':id', $reservationId, PDO::PARAM_INT);
    return $stmt->execute();
}
?>
