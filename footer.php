<footer id="customFooter" class="custom-footer mt-auto py-4 text-white">
    <div class="container footer-main-container">
        <div class="footer-content-container">
            <div class="row justify-content-center justify-content-md-between"> 
                <div id="footerContact" class="col-12 col-md-4 footer-column mb-4 mb-md-0"> 
                    <div class="footer-column-content"> 
                        <h5 class="text-uppercase">Contact</h5>
                        <ul class="list-unstyled p-0">
                            <li><a href="tel:+31657695421">+31 6 57695421</a></li>
                            <li><a href="mailto:info@deejayjesse.nl">info@deejayjesse.nl</a></li>
                            <p class="mt-2">KvK-nummer: 88906663</p>
                        </ul>
                    </div>
                </div>
                <div id="footerSocials" class="col-12 col-md-4 footer-column mb-4 mb-md-0">
                    <div class="footer-column-content">
                        <h5 class="text-uppercase">Volg Op</h5>
                        <div class="d-flex justify-content-center">
                            <a href="https://facebook.com/deejayjessenl" target="_blank" class="me-3 footer-social-icon"><i class="fab fa-facebook fa-lg"></i></a>
                            <a href="https://instagram.com/deejayjesse" target="_blank" class="footer-social-icon"><i class="fab fa-instagram fa-lg"></i></a>
                        </div>
                    </div>
                </div>
                <div id="footerLinks" class="col-12 col-md-4 footer-column">
                    <div class="footer-column-content">
                        <h5 class="text-uppercase">Pagina's</h5>
                        <ul class="list-unstyled p-0">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="biography.php">Biografie</a></li>
                            <li><a href="prices.php">Mogelijkheden</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#contactModal" style="cursor:pointer;">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <hr id="footerDivider" class="footer-divider my-3">

        <div id="footerCopyrightWrapper" class="text-center">
            <p id="footerCopyright" class="mb-0">&copy; <span id="currentYear"></span> DJ Jesse - Alle rechten voorbehouden.</p>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() { 
        const currentYearEl = document.getElementById('currentYear');
        if (currentYearEl) {
            currentYearEl.textContent = new Date().getFullYear();
        }
    });
</script>
