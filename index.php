<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom - DJ Jesse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <!-- <link rel="stylesheet" href="forms/reservation.css"> -->
</head>
<body>
<?php include_once("navbar.php"); ?>

<main>
<section class="hero d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h1 class="display-3 fw-bold">De Beat van Jouw Feest</h1>
        <p class="lead fs-4">Laat de muziek spreken en beleef een avond die je nooit zult vergeten.</p>
    </div>
</section>

<section class="services">
  <div class="container">
    <!-- Linkerzijde met tekst -->
    <div class="services-text">
      <h2 class="display-4 fw-bold mb-2">Één groot feest met DJ Jesse!</h2>
      <p class="mb-5">
        Of het nu gaat om een verjaardagsfeest, jubileum of een ander soort feest... hier ben je op het juiste adres!
      </p>
    </div>

    <!-- Rechterzijde met kaarten -->
    <div class="card-list">
      <!-- Verjaardag -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-cake-candles"></i>
            </div>
            <h3 class="card-title">Verjaardag</h3>
            </div>
            <p class="card-caption">
                Een 
            </p>
        </div>
      </div>

      <!-- Jubileum -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-gem"></i>
            </div>
            <h3 class="card-title">Jubileum</h3>
            </div>
            <p class="card-caption">
              Vier een mijlpaal met een feest dat nog jarenlang herinnerd zal worden!
            </p>
        </div>
      </div>

      <!-- Voetbalclub -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-futbol"></i>
            </div>
            <h3 class="card-title">Voetbalclub</h3>
            </div>
            <p class="card-caption">
              Breng je team samen en vier de overwinning of het seizoen in stijl!
            </p>
        </div>
      </div>

      <!-- Themafeest -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-masks-theater"></i>
            </div>
            <h3 class="card-title">Themafeest</h3>
            </div>
            <p class="card-caption">
              Kies jouw favoriete thema en maak er een spetterend feest van!
            </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="booking-section">
  <h2 class="display-4 fw-bold mb-2">Plaats je reservering snel!</h2>
  <p>Écht zin in een feestje? Door het makkelijke reserveringssysteem plaats jij je reservering binnen 5 minuten!</p>

  <div class="timeline">
    <div class="step">
      <div class="circle">1</div>
      <p class="description">Kies een pakket voor jouw evenement.</p>
    </div>
    <div class="step">
      <div class="circle">2</div>
      <p class="description">Vul het reserveringsformulier in.</p>
    </div>
    <div class="step">
      <div class="circle">3</div>
      <p class="description">Krijg binnen 24 uur antwoord!</p>
    </div>
  </div>

  <button class="btn btn-reserveer" data-bs-toggle="modal" data-bs-target="#reserveringsModal">MAAK NU EEN RESERVERING</button>
</section>

<!-- <section>
<div class="container text-end">
    <div class="row align-items-center">
        <div class="col-md-6">
           
        </div>
        <div class="col-md-6">
            <h2 class="display-4 fw-bold mb-2">Benieuwd naar de mogelijkheden?</h2>
            <p>
                
            </p>
        </div>
    </div>
</div>
</section> -->

<section>
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h2 class="display-4 fw-bold mb-2">Toch nog een vraag? Of wil je advies?</h2>
                <p>
                    Neem gerust contact op via de volgende manieren:
                </p>
            </div>
        <div class="col-md-5">
          <div class="contact-div">
            <form action="#" method="post">
              <label for="name">Naam</label>
              <input type="text" id="name" name="name" placeholder="Voer je naam in" required>

              <label for="email">E-mail</label>
              <input type="email" id="email" name="email" placeholder="Voer je e-mail in" required>

              <label for="message">Bericht</label>
              <textarea id="message" name="message" placeholder="Je bericht..." required></textarea>

              <button type="submit">Verstuur</button>
            </form>
            <br>

            <p class="mb-1" style="text-size: 11px;">Of neem contact op via de volgende manieren:</p>

            <div class="contact-buttons">
              <a href="https://wa.me/31657695421" class="whatsapp-btn" target="_blank">
                <i class="fab fa-whatsapp"></i> WhatsApp
              </a>
              <a href="tel:+31657695421" class="phone-btn">
                <i class="fas fa-phone"></i> Bellen
              </a>
            </div>
          </div>
        </div>
    </div>
</div>
</section>

<?php include_once("footer.php"); ?>

<script>
document.querySelector(".card-list").onmousemove = e => {
  for (const card of document.getElementsByClassName("card")) {
    const rect = card.getBoundingClientRect(),
          x = e.clientX - rect.left,
          y = e.clientY - rect.top;
    card.style.setProperty("--mouse-x", `${x}px`);
    card.style.setProperty("--mouse-y", `${y}px`);
  }
};
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0x/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".hidden");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, { threshold: 0.1 });

    elements.forEach((el) => observer.observe(el));
  });
</script>
</main>
</body>
</html>