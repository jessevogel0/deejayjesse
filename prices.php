<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijzentabel - Deejay Jesse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/prices.css"> 
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="forms/reservations/reservation.css">

    <link rel="stylesheet" href="effects/animations.css">
</head>
<body>
    <?php include_once("navbar.php"); ?>
    <main>
        <!-- <section class="hero d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <h1 class="display-3 fw-bold">Onze Pakketten</h1>
                <p class="lead">Benieuwd wat DJ Jesse aan te bieden heeft? Bekijk hieronder de mogelijkheden.</p>
                <a href="#prijzen-sectie" class="btn btn-primary btn-lg mt-3">Bekijk "Mogelijkheden"</a>
            </div>
        </section> -->

        <section id="prijzen-sectie">
            <div class="container">
                <h2 class="display-3 fw-bold mb-5 text-center animate-on-scroll zoom-in">Pakketten</h2>

                <div class="row g-5 justify-content-center mb-5">
                    <div class="col-md-4 d-flex justify-content-center animate-on-scroll zoom-in">
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
                            <button class="btn w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#reserveringsModal" 
                                    data-package-value="standaard">
                                <span>MAAK NU EEN RESERVERING</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex justify-content-center animate-on-scroll zoom-in">
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
                            <button class="btn w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#reserveringsModal" 
                                    data-package-value="all-inclusive">
                                <span>MAAK NU EEN RESERVERING</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex justify-content-center animate-on-scroll zoom-in">
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
                            <button class="btn w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#reserveringsModal" 
                                    data-package-value="alleen-dj">
                                <span>MAAK NU EEN RESERVERING</span>
                            </button>
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-center animate-on-scroll zoom-in">Vragen over de keuzes? Ga naar de <a href="#" class="text-info">veelgestelde vragen</a></p>
            </div>
        </section>

        <section>
        <div class="animate-on-scroll fade-in">
            <div class="title-360-wrapper">
                <div class="container">
                    <h2 class="section-title-360 animate-on-scroll zoom-in display-3">Feest in 360°
                        <span class="highlight">Licht - Geluid - Show</span>
                    </h2>
                    <p class="intro-360 animate-on-scroll fade-in mb-4" style="transition-delay: 0.2s;">
                        Tijdens uw feest is het een complete beleving. Deze 3 aspecten zorgen voor uw feest, en deze perfect samensmelten voor maximale impact:
                    </p>

                <div class="feature-blocks-container">
                        <div class="feature-block-360 animate-on-scroll slide-in-left" style="transition-delay: 0.4s;">
                            <div class="icon-wrapper-360">
                                <i class="fas fa-lightbulb icon-360"></i> 
                            </div>
                            <div class="text-content-360">
                                <h3>Lichtshow op Maat</h3>
                                <p>Licht is pure energie. Ervaar hoe een perfect getimede lichtshow uw feest transformeert en elke ruimte tot leven brengt. Doordat het licht <strong>live wordt aangestuurd</strong>, wordt de sfeer continu aangepast aan de muziek en de vibe. Een dynamische show die impact garandeert. Hoe vet is dat?!</p>
                            </div>
                        </div>

                        <div class="feature-block-360 animate-on-scroll slide-in-right" style="transition-delay: 0.5s;">
                            <div class="icon-wrapper-360">
                                <i class="fas fa-volume-up icon-360"></i>
                            </div>
                            <div class="text-content-360">
                                <h3>Ideaal geluidssysteem</h3>
                                <p>Ervaar muziek die echt tot leven komt. Het professionele geluid brengt elke track tot in detail, van de diepste bas tot de helderste zang. Een dynamische en energieke sound die uw feest onvergetelijk maakt.</p>
                           </div>
                        </div>

                        <div class="feature-block-360 animate-on-scroll slide-in-left" style="transition-delay: 0.6s;">
                        <div class="icon-wrapper-360">
                            <i class="fas fa-headphones-alt icon-360"></i>
                        </div>
                            <div class="text-content-360">
                                <h3>Energieke DJ Performance</h3>
                                <p>DJ Jesse brengt de show die uw feest tot leven wekt. Verwacht pure energie, aanstekelijk enthousiasme en een DJ die de dansvloer verenigt. Met vloeiende overgangen en een perfecte publieksinteractie wordt uw evenement onvergetelijk.</p>                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

    <?php 
        // De modal HTML wordt hier ingevoegd, VOORDAT de scripts die het mogelijk gebruiken.
        include_once 'forms/reservations/reservationForm.php'; 
    ?>

    <?php 
        // footer.php bevat jQuery en Bootstrap JS.
        // Het is belangrijk dat deze scripts geladen worden VOORDAT reservation.js.
        include_once("footer.php"); 
    ?>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    
    <script src="forms/reservations/reservation.js" defer></script>

    <script src="effects/animate-on-scroll.js"></script>
</body>
</html>
