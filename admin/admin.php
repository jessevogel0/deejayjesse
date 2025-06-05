<?php
// admin/admin.php
// Zorg ervoor dat sessiebeheer en authenticatie hier correct zijn ingesteld
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // header('Location: login.php'); // Stuur naar login pagina indien niet ingelogd
    // exit;
    // Voor nu, voor ontwikkelingsdoeleinden, kun je dit uitcommentariëren
    // maar zorg voor beveiliging in een productieomgeving.
}

require_once '../db/db_handler.php'; // Voor het geval je DB direct nodig hebt, anders via API
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminpaneel - Reserveringen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OerSvFmgK2TiPtc3j7k1+uprrX1+n+A/Zqjhf" crossorigin="anonymous">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <div class="page-wrapper d-flex flex-column min-vh-100">
        <header class="main-header navbar navbar-expand-lg navbar-dark bg-dark sticky-top p-3 shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand site-title" href="#">Adminpaneel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Reserveringen</a>
                        </li>
                        <!-- Voeg hier eventueel meer menu-items toe voor andere beheersecties -->
                    </ul>
                    <nav class="main-nav">
                        <a href="logout.php" class="btn btn-sm btn-outline-light">Uitloggen</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="main-content container my-4 flex-grow-1">
            <section id="reservations-overview" class="admin-section card bg-dark text-white shadow">
                <div class="card-header text-center py-3 section-title-wrapper">
                    <h2 class="section-title mb-0">Reserveringen Beheren</h2>
                </div>

                <div class="card-body">
                    <ul class="nav nav-pills justify-content-center mb-4 p-2 rounded-pill category-nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-pending-tab" data-bs-toggle="pill" data-bs-target="#pills-pending" type="button" role="tab" aria-controls="pills-pending" aria-selected="true" data-category="Pending">Pending</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-accepted-tab" data-bs-toggle="pill" data-bs-target="#pills-accepted" type="button" role="tab" aria-controls="pills-accepted" aria-selected="false" data-category="Accepted">Geaccepteerd</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-declined-tab" data-bs-toggle="pill" data-bs-target="#pills-declined" type="button" role="tab" aria-controls="pills-declined" aria-selected="false" data-category="Declined">Geweigerd</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-expired-tab" data-bs-toggle="pill" data-bs-target="#pills-expired" type="button" role="tab" aria-controls="pills-expired" aria-selected="false" data-category="Verlopen">Verlopen</button>
                        </li>
                    </ul>

                    <div class="filter-sort-wrapper bg-dark p-3 rounded mb-4 border border-secondary">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="reservationSearch" class="form-label visually-hidden">Zoek reservering</label>
                                <input type="text" id="reservationSearch" class="form-control" placeholder="Zoek op naam, ID, email..." aria-label="Zoek reservering">
                            </div>
                            <div class="col-md-6">
                                <label for="reservationSort" class="form-label visually-hidden">Sorteer op</label>
                                <select id="reservationSort" class="form-select" aria-label="Sorteer reserveringen">
                                    <option value="date_desc">Datum (Nieuwste eerst)</option>
                                    <option value="date_asc">Datum (Oudste eerst)</option>
                                    <option value="id_asc">ID (Oplopend)</option>
                                    <option value="id_desc">ID (Aflopend)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active reservations-category-pane" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab" tabindex="0">
                            <h2 class="category-title mb-3 fw-bold text-center">Pending Reserveringen</h2>
                            <!-- Content for pending reservations will be dynamically loaded here by admin.js -->
                            <div id="pendingReservationsContainer" class="reservations-container"></div>
                        </div>

                        <div class="tab-pane fade reservations-category-pane" id="pills-accepted" role="tabpanel" aria-labelledby="pills-accepted-tab" tabindex="0">
                            <h3 class="category-title mb-3">Geaccepteerde Reserveringen</h3>
                            <div id="acceptedReservationsContainer" class="reservations-container"></div>
                        </div>

                        <div class="tab-pane fade reservations-category-pane" id="pills-declined" role="tabpanel" aria-labelledby="pills-declined-tab" tabindex="0">
                            <h3 class="category-title mb-3">Geweigerde Reserveringen</h3>
                            <div id="declinedReservationsContainer" class="reservations-container"></div>
                        </div>

                        <div class="tab-pane fade reservations-category-pane" id="pills-expired" role="tabpanel" aria-labelledby="pills-expired-tab" tabindex="0">
                            <h3 class="category-title mb-3">Verlopen Reserveringen</h3>
                            <div id="expiredReservationsContainer" class="reservations-container"></div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="main-footer bg-dark text-white text-center p-3 mt-4 shadow-sm">
            <div class="container">
                <p class="copyright-text mb-0">&copy; <?php echo date("Y"); ?> DJ Jesse Admin</p>
            </div>
        </footer>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
</body>
</html>
