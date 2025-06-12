<?php
    include_once 'controllers/PageController.php';
    $current_page = PageController::getCurrentPage();
  ?>

  <!-- Navbar -->
  <div id="navbarCustom" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div id="navbarContainer" class="container-fluid">
      <!-- Logo -->
      <a id="navbarLogo" class="navbar-brand" href="/deejayjesse/index.php">
        <img src="images/LOGO ZONDER STREEP.png" alt="Logo" height="40">
      </a>

      <!-- Hamburger menu knop met data-bs attributes zodat Bootstrap het standaardgedrag hanteert -->
      <button id="navbarToggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigatiemenu -->
      <div id="navbarNav" class="collapse navbar-collapse justify-content-end">
        <ul id="navbarNavList" class="navbar-nav">
          <li class="nav-item">
            <a href="/deejayjesse/index.php" class="nav-link <?php echo PageController::getActiveClass('index'); ?>">Home</a>
          </li>
          <li class="nav-item">
            <a href="/deejayjesse/biography.php" class="nav-link <?php echo PageController::getActiveClass('biography'); ?>">Biografie</a>
          </li>
          <li class="nav-item">
            <a href="/deejayjesse/prices.php" class="nav-link <?php echo PageController::getActiveClass('prices'); ?>">Mogelijkheden</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo PageController::getActiveClass('contact'); ?>" data-bs-toggle="modal" data-bs-target="#contactModal">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div id="contactModal" class="modal fade modal-dark" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div id="contactModalDialog" class="modal-dialog modal-lg modal-dialog-centered">
    <div id="contactModalContent" class="modal-content d-flex flex-column" style="background-color: #222;">

      <div class="modal-header border-0 d-flex justify-content-end align-items-start p-3 pb-0 pe-3" style="background-color: transparent !important; z-index: 4; position: absolute; top: 0; right: 0; width: 100%;">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div id="contactModalImageWrapper" class="d-none d-md-block">
        <img id="contactModalImage" src="../assets/images/DSC03582.jpg" alt="Contact" class="img-fluid rounded">
      </div>

      <div id="contactModalBody" class="modal-body flex-grow-1 d-flex flex-column justify-content-center p-0">
        <div id="contactModalTextWrapper" class="p-4 d-flex flex-column justify-content-center">
          <div id="contactModalFAQ" class="mb-3">
            <h5 class="fw-bold">Vragen, of op zoek naar advies?</h5>
            <p>Heeft u een vraag of opzoek naar advies? Bekijk de <a href="FAQ.php" class="text-decoration-none custom-link-hover">veelgestelde vragen</a> of neem contact op.</p>
          </div>
          <div id="contactModalContactInfo" class="mb-3">
            <h5 class="fw-bold">Contact</h5>
            <p>Zoekt u meer duidelijkheid, of vrijblijvend advies? Neem dan contact op via de volgende manieren:</p>
            <ul id="contactModalContactList" class="contact-list">
              <li><a href="tel:+31657695421">Tel: +31 6 57695421</a> // <a href="https://wa.me/31657695421">WhatsApp</a></li>
              <li><a href="mailto:info@deejayjesse.nl">Email: info@deejayjesse.nl</a></li>
              <li><a href="https://facebook.com/deejayjesse" target="_blank">Facebook</a></li>
              <li><a href="https://instagram.com/deejayjesse" target="_blank">Instagram</a></li>
            </ul>
          </div>
          <div id="contactModalTerms">
            <h5 class="fw-bold">Algemene  voorwaarden</h5>
            <p>Een reservering maken voor jouw feest? Dan gaat u automatisch akkoord met <a href="boekingsvoorwaarden.php" class="text-decoration-none custom-link-hover">de algemene voorwaarden</a>.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const navbarToggler = document.getElementById("navbarToggler");
    const navbarNav = document.getElementById("navbarNav");

    navbarToggler.addEventListener("click", function (event) {
      event.stopPropagation();
    });

    document.addEventListener("click", function (event) {
      if (navbarNav.classList.contains("show") && !navbarNav.contains(event.target)) {
        const collapseInstance = bootstrap.Collapse.getInstance(navbarNav);
        if (collapseInstance) {
          collapseInstance.hide();
        }
      }
    });
  });
</script>