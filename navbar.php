<?php
    include_once 'controllers/PageController.php';
    $current_page = PageController::getCurrentPage();
  ?>

  <!-- Navbar -->
  <div id="navbarCustom" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div id="navbarContainer" class="container-fluid">
      <!-- Logo -->
      <a id="navbarLogo" class="navbar-brand" href="/deejayjesse/index.php">
        <img src="images/DJ Jesse Logo HD - Wit.png" alt="Logo" height="40">
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

  <!-- Contact Modal -->
  <div id="contactModal" class="modal fade" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div id="contactModalDialog" class="modal-dialog modal-xl modal-dialog-centered">
      <div id="contactModalContent" class="modal-content">
        <div id="contactModalBody" class="modal-body d-flex">
          <!-- Afbeelding sectie -->
          <div id="contactModalImageWrapper" class="col-md-5">
            <img id="contactModalImage" src="../assets/images/contact-image.jpg" alt="Contact" class="img-fluid rounded">
          </div>

          <div id="contactModalTextWrapper" class="col-md-7 p-3 d-flex flex-column justify-content-between">
            <!-- Sluitknop -->
            <div id="contactModalCloseContainer" class="close-container">
              <button id="contactModalCloseButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white !important; opacity: 1 !important;"></button>
            </div>

            <div id="contactModalFAQ" class="col-md-10 mb-3">
              <h5 class="fw-bold">Vragen?</h5>
              <p>Heeft u een vraag? Bekijk de <a href="/deejayjesse.nl/pages/FAQ.php" class="text-decoration-none">veelgestelde vragen</a>.</p>
            </div>
            <div id="contactModalContactInfo" class="col-md-10 mb-3">
              <h5 class="fw-bold">Contact</h5>
              <p>Zoekt u meer duidelijkheid, of vrijblijvend advies? Neem dan contact op via de volgende manieren:</p>
              <ul id="contactModalContactList" class="contact-list">
                <li><a href="tel:+31657695421">Tel: +31 6 57695421</a> // <a href="https://wa.me/31657695421">WhatsApp</a></li>
                <li><a href="mailto:info@deejayjesse.nl">Email: info@deejayjesse.nl</a></li>
                <li><a href="https://facebook.com/deejayjesse" target="_blank">Facebook</a></li>
                <li><a href="https://instagram.com/deejayjesse" target="_blank">Instagram</a></li>
              </ul>
            </div>
            <div id="contactModalTerms" class="col-md-10">
              <h5 class="fw-bold">Boekingsvoorwaarden</h5>
              <p>Wilt u een boeking reserveren? Dan gaat u automatisch akkoord met <a href="/deejayjesse.nl/assets/booking-terms.pdf" class="text-decoration-none">deze boekingsvoorwaarden</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap Bundle met Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom Script voor outside-click handling -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const navbarToggler = document.getElementById("navbarToggler");
      const navbarNav = document.getElementById("navbarNav");

      // Stop event bubbling voor de hamburger knop
      navbarToggler.addEventListener("click", function (event) {
        event.stopPropagation();
      });

      // Outside-click handler: sluit de navbar als deze open is en er buiten geklikt wordt
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