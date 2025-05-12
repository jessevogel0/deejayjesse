<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijzentabel - Deejay Jesse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="prices.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>
    <?php include_once("navbar.php"); ?>
    <main>
        <section class="hero d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <h1 class="display-3 fw-bold"></h1>
                <p class="lead">Benieuwd wat DJ Jesse aan te bieden heeft? Bekijk hieronder de mogelijkheden.</p>
                <a href="#prijzen" class="btn btn-primary btn-lg mt-3">Bekijk "Mogelijkheden"</a>
            </div>
        </section>


        <section>
        <div class="container">
        <h1 class="head-title text-center">Kies uit:</h1>

        <div class="row g-5">
            <div class="col-md-4 d-flex justify-content-center">
                <div class="pricing-card standard mx-auto">
                    <h2>STANDAARD</h2>
                    <div class="subtitle">DRIVE-IN SHOW</div>
                    <ul class="features list-unstyled">
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> DJ service - 5 uur</li>
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> Geluidset (tot 150 personen)</li>
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> Sfeerverlichting (2x2 stuks)</li>
                        <li><i class="fa-regular fa-circle-xmark icon text-danger"></i> Compacte Lichtset</li>
                        <li><i class="fa-regular fa-circle-xmark icon text-danger"></i> Moving Heads (2 stuks)</li>
                    </ul>
                    <a class="btn open-modal w-100" id="standard"><span>MAAK NU EEN RESERVERING</span></a>
                </div>
            </div>

            <!-- ALL-INCLUSIVE -->
            <div class="col-md-4 d-flex justify-content-center">
                <div class="pricing-card all-inclusive mx-auto">
                    <h2>ALL-INCLUSIVE</h2>
                    <div class="subtitle">DRIVE-IN SHOW</div>
                    <span class="recommended-label d-block">AANBEVOLEN</span>
                    <ul class="features list-unstyled">
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> DJ service - 5 uur</li>
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> Geluidset (tot 150 personen)</li>
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> Sfeerverlichting (2x2 stuks)</li>
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> Compacte Lichtset</li>
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> Moving Heads (2 stuks)</li>
                    </ul>
                    <a class="btn open-modal w-100" id="all-inclusive"><span>MAAK NU EEN RESERVERING</span></a>
                </div>
            </div>

            <!-- ALLEEN DJ -->
            <div class="col-md-4 d-flex justify-content-center">
                <div class="pricing-card only-dj mx-auto">
                    <h2>ALLEEN DJ</h2>
                    <div class="subtitle">EXCL. LICHT & GELUID</div>
                    <ul class="features list-unstyled">
                        <li><i class="fa-regular fa-circle-check icon text-success"></i> DJ service - 5 uur</li>
                        <li><i class="fa-regular fa-circle-xmark icon text-danger"></i> Geluidset (tot 150 personen)</li>
                        <li><i class="fa-regular fa-circle-xmark icon text-danger"></i> Sfeerverlichting (2x2 stuks)</li>
                        <li><i class="fa-regular fa-circle-xmark icon text-danger"></i> Compacte Lichtset</li>
                        <li><i class="fa-regular fa-circle-xmark icon text-danger"></i> Moving Heads (2 stuks)</li>
                    </ul>
                    <a class="btn open-modal w-100" id="alleen-dj"><span>MAAK NU EEN RESERVERING</span></a>
                </div>
            </div>
        </div>

        <p class="mt-4 text-center">Vragen over de keuzes? Ga naar de <a href="#" class="text-info">veelgestelde vragen</a></p>
    </div>
</section>

<!-- Modal HTML
<div id="reservation-modal" class="modal fade" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Maak je reservering</h2>
            <form id="reservation-form">
                <label for="name">Naam:</label>
                <input type="text" id="name" name="name" placeholder="Je naam" required>

                <label for="phone">Telefoonnummer:</label>
                <input type="tel" id="phone" name="phone" placeholder="Je telefoonnummer" required>

                <label for="date">Datum Feest:</label>
                <input type="date" id="date" name="date" required>

                <label for="package">Pakket:</label>
                <select id="package" name="package" required>
                    <option value="standard">Standaard</option>
                    <option value="all-inclusive">All-Inclusive</option>
                    <option value="alleen-dj">Alleen DJ</option>
                </select>

                <label for="message">Bericht:</label>
                <textarea id="message" name="message" placeholder="Bericht voor de DJ" rows="4"></textarea>

                <button type="submit">Reserveren</button>
            </form>
        </div>
    </div>
</div> -->

<?php include_once("footer.php"); ?>
</main>
</body>
</html>