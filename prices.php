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


        <!--Product designer start-->
        <section class="my-xl-9 my-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="text-center mb-xl-7 mb-5">
                            <h2 class="mb-3">
                                Products designed for
                                <span class="text-primary">all sizes
                                    businesses.</span>
                            </h2>
                            <p class="mb-0">
                                Enim sed parturient sem enim nunc sit erat velit
                                eget hac nulla nullam et id praesent nisi ornare
                                risus risus consequat nunc nisl pellentesque diam
                                neque.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-xl-5 col-md-6 col-12">
                        <div class="nav flex-column nav-pills mb-5 mb-lg-0"
                            id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a href="#"
                                class="nav-link active d-flex text-start align-items-center align-items-lg-start p-xl-4 p-3"
                                id="v-pills-small-business-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#v-pills-small-business" role="tab"
                                aria-controls="v-pills-small-business"
                                aria-selected="true">
                                <div class="d-flex">
                                    <div
                                        class="icon-md icon-shape rounded-circle bg-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="18" height="18"
                                            fill="currentColor"
                                            class="bi bi-bank2 text-primary"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M8.277.084a.5.5 0 0 0-.554 0l-7.5 5A.5.5 0 0 0 .5 6h1.875v7H1.5a.5.5 0 0 0 0 1h13a.5.5 0 1 0 0-1h-.875V6H15.5a.5.5 0 0 0 .277-.916l-7.5-5zM12.375 6v7h-1.25V6h1.25zm-2.5 0v7h-1.25V6h1.25zm-2.5 0v7h-1.25V6h1.25zm-2.5 0v7h-1.25V6h1.25zM8 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2zM.5 15a.5.5 0 0 0 0 1h15a.5.5 0 1 0 0-1H.5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h4 class="mb-0">For small business</h4>
                                    <p class="mb-0 mt-lg-3 d-none d-lg-block">
                                        Interdum et malesuad a fames ac ante ipsum
                                        primis in faucibus. Simple dummy content.
                                        Sed lacinia gsmod dui euismod id.
                                    </p>
                                </div>
                            </a>
                            <a href="#"
                                class="nav-link d-flex text-start align-items-center align-items-lg-start p-xl-4 p-3"
                                id="v-pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-profile" role="tab"
                                aria-controls="v-pills-profile"
                                aria-selected="false">
                                <div class="d-flex">
                                    <div
                                        class="icon-md icon-shape rounded-circle bg-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="18" height="18"
                                            fill="currentColor"
                                            class="bi bi-credit-card-2-front-fill text-primary"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h4 class="mb-0">For startups</h4>
                                    <p class="mb-0 mt-lg-3 d-none d-lg-block">
                                        Nullam sodales, libero ac dictum convallis,
                                        ipsum diam cursus stibulum lacinia ultricies
                                        eleifend. Simple dummy content.
                                    </p>
                                </div>
                            </a>
                            <a href="#"
                                class="nav-link d-flex text-start p-xl-4 p-3 align-items-center align-items-lg-start"
                                id="v-pills-enterprises-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-enterprises" role="tab"
                                aria-controls="v-pills-enterprises"
                                aria-selected="false">
                                <div class="d-flex">
                                    <div
                                        class="icon-md icon-shape rounded-circle bg-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="18" height="18"
                                            fill="currentColor"
                                            class="bi bi-cash-stack text-primary"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                            <path
                                                d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h4 class="mb-0">For enterprises</h4>
                                    <p class="mb-0 mt-lg-3 d-none d-lg-block">
                                        In a odio sit amet nisi tincidunt congue.
                                        Mauris cursus magna a vestibulum rutrum.
                                        Vivamus sit amet luctus leo. Simple dummy
                                        content.
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6 offset-xl-1 col-md-6 col-12">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active"
                                id="v-pills-small-business" role="tabpanel"
                                aria-labelledby="v-pills-small-business-tab"
                                tabindex="0">
                                <div class="position-relative scene"
                                    data-relative-input="true">
                                    <figure><img
                                            src="../assets/images/landings/finance/finance-tab-3.jpg"
                                            alt="finance"
                                            class="img-fluid rounded-3"></figure>

                                    <div class="position-relative"
                                        data-depth="0.05">
                                        <img src="../assets/images/landings/finance/card.svg"
                                            alt=""
                                            class="position-absolute bottom-0 end-0 px-4" />
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile"
                                role="tabpanel"
                                aria-labelledby="v-pills-profile-tab" tabindex="0">
                                <div class="position-relative scene"
                                    data-relative-input="true">
                                    <figure><img
                                            src="../assets/images/landings/finance/finance-tab-2.jpg"
                                            alt="finance"
                                            class="img-fluid rounded-3"></figure>

                                    <div class="position-relative"
                                        data-depth="0.05">
                                        <img src="../assets/images/landings/finance/card.svg"
                                            alt=""
                                            class="position-absolute bottom-0 start-0 px-4" />
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="v-pills-enterprises"
                                role="tabpanel"
                                aria-labelledby="v-pills-enterprises-tab"
                                tabindex="0">
                                <div class="position-relative scene"
                                    data-relative-input="true">
                                    <figure><img
                                            src="../assets/images/landings/finance/finance-tab-1.jpg"
                                            alt="finance"
                                            class="img-fluid rounded-3" /></figure>

                                    <div class="position-relative"
                                        data-depth="0.05">
                                        <img src="../assets/images/landings/finance/card.svg"
                                            alt=""
                                            class="position-absolute bottom-0 start-50 translate-middle-x" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Product designer end-->

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
