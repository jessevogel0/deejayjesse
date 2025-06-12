<?php
// /db/db_handler.php

/**
 * Maakt een PDO-verbinding met de database.
 *
 * @return PDO|null Het PDO-object bij succes, anders null.
 */
function connectDB(): ?PDO {
    $dbHost = 'localhost';
    $dbName = 'djjesse';
    $dbUser = 'root';
    $dbPass = '';
    $charset = 'utf8mb4';
    
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$charset}";
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
 * Aangepast om PDO::PARAM_NULL correct te gebruiken.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param array $data De reserveringsdata.
 * @return string|false Het ID van de laatst ingevoegde rij bij succes, anders false.
 */
function addReservationToDB(PDO $pdo, array $data) {
    $sql = "INSERT INTO reservations (full_name, email, phone, reservation_date, reservation_time, package_type, remarks, status)
            VALUES (:full_name, :email, :phone, :reservation_date, :reservation_time, :package_type, :remarks, :status)";
    
    try {
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':full_name', $data['full_name'] ?? null);
        $stmt->bindValue(':email', $data['email'] ?? null);
        $stmt->bindValue(':phone', $data['phone'] ?? null);
        $stmt->bindValue(':reservation_date', $data['reservation_date'] ?? null);
        
        // Correcte binding voor nullable velden: reservation_time
        $reservationTimeValue = !empty($data['reservation_time']) ? $data['reservation_time'] : null;
        $stmt->bindValue(':reservation_time', $reservationTimeValue, $reservationTimeValue === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        
        $stmt->bindValue(':package_type', $data['package_type'] ?? null);
        
        // Correcte binding voor nullable velden: remarks
        $remarksValue = !empty($data['remarks']) ? $data['remarks'] : null;
        $stmt->bindValue(':remarks', $remarksValue, $remarksValue === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        
        $stmt->bindValue(':status', $data['status'] ?? 'Pending');

        if ($stmt->execute()) {
            return $pdo->lastInsertId();
        }
        return false;
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Fout bij addReservationToDB: " . $e->getMessage() . " Data: " . print_r($data, true));
        return false;
    }
}

/**
 * Haalt reserveringen op uit de database met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param string|null $statusFilter Optionele status om op te filteren.
 * @param string|null $searchTerm Optionele zoekterm.
 * @param string $sortOrder Sorteervolgorde (bijv. 'submission_timestamp DESC').
 * @return array Een array van reserveringen.
 */
function getReservationsFromDB(PDO $pdo, ?string $statusFilter = null, ?string $searchTerm = null, string $sortOrder = 'submission_timestamp DESC'): array {
    $sql = "SELECT id, full_name, email, phone, reservation_date, reservation_time, package_type, remarks, status, submission_timestamp FROM reservations";
    $params = [];
    $whereClauses = [];

    if ($statusFilter !== null && $statusFilter !== '') {
        $whereClauses[] = "status = :status";
        $params[':status'] = $statusFilter;
    }

    if ($searchTerm !== null && $searchTerm !== '') {
        $searchPattern = '%' . $searchTerm . '%';
        $searchClauses = [];
        $searchableFields = ['id', 'full_name', 'email', 'phone', 'package_type', 'remarks'];
        foreach ($searchableFields as $field) {
            $searchClauses[] = "`{$field}` LIKE :searchTerm_{$field}";
            $params[":searchTerm_{$field}"] = $searchPattern;
        }
        if (!empty($searchClauses)) {
            $whereClauses[] = "(" . implode(" OR ", $searchClauses) . ")";
        }
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }
    
    $allowedSortOrders = [
        'reservation_date ASC' => 'reservation_date ASC, reservation_time ASC',
        'reservation_date DESC' => 'reservation_date DESC, reservation_time DESC',
        'id ASC' => 'id ASC',
        'id DESC' => 'id DESC',
        'submission_timestamp ASC' => 'submission_timestamp ASC',
        'submission_timestamp DESC' => 'submission_timestamp DESC'
    ];
    $orderBy = $allowedSortOrders[$sortOrder] ?? 'submission_timestamp DESC';
    $sql .= " ORDER BY " . $orderBy;

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Fout bij getReservationsFromDB: " . $e->getMessage());
        return [];
    }
}

/**
 * Haalt één specifieke reservering op basis van ID met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param int $reservationId Het unieke ID van de reservering.
 * @return array|false De reserveringsdata, of false als niet gevonden.
 */
function getReservationById(PDO $pdo, int $reservationId) {
    $sql = "SELECT id, full_name, email, phone, reservation_date, reservation_time, package_type, remarks, status, submission_timestamp FROM reservations WHERE id = :id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Fout bij getReservationById (ID: {$reservationId}): " . $e->getMessage());
        return false;
    }
}

/**
 * Werkt de status van een specifieke reservering bij in de database met PDO.
 *
 * @param PDO $pdo Het PDO-object voor de databaseverbinding.
 * @param int $reservationId Het ID van de reservering.
 * @param string $newStatus De nieuwe status (moet een van de ENUM waarden zijn).
 * @return bool True bij succesvolle update (als minstens 1 rij is aangepast), anders false.
 */
function updateReservationStatusInDB(PDO $pdo, int $reservationId, string $newStatus): bool {
    $sql = "UPDATE reservations SET status = :status WHERE id = :id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', $newStatus);
        $stmt->bindValue(':id', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Fout bij updateReservationStatusInDB (ID: {$reservationId}, Status: {$newStatus}): " . $e->getMessage());
        return false;
    }
}

/**
 * Update de status van 'Pending' reserveringen ouder dan X dagen naar 'Verlopen'.
 *
 * @param PDO $pdo Het PDO-object.
 * @param int $daysOld Minimum leeftijd in dagen voor een 'Pending' reservering om te verlopen.
 * @return int Aantal bijgewerkte rijen.
 */
function expireOldPendingReservations(PDO $pdo, int $daysOld): int {
    $sql = "UPDATE reservations
            SET status = 'Verlopen'
            WHERE status = 'Pending'
            AND submission_timestamp < DATE_SUB(NOW(), INTERVAL :daysOld DAY)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':daysOld', $daysOld, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("DATABASE_HANDLER_ERROR: Fout bij expireOldPendingReservations (daysOld: {$daysOld}): " . $e->getMessage());
        return 0;
    }
}
?>