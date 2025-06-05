<?php
// reservationForm.php
// Bevat de HTML-structuur voor de reserveringsmodal.
?>
<div class="modal fade" id="reserveringsModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="reservationModalLabel">Reserveer Nu</h5>
                <button type="button" class="btn-close btn-close-white" id="reservationModalCloseButton" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="successMessageContainer" class="d-none text-center position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.1); z-index: 10; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <canvas id="confettiCanvas" class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: 1;"></canvas>
                    <div class="p-3" style="position: relative; z-index: 2;">
                        <h2 id="successText" class="display-3 fw-bold mb-3" style="transform: scale(0); opacity: 0;">Gelukt! &#x1F389;</h2>
                        <p id="successSubText" class="lead"></p>
                    </div>
                </div>

                <form id="reservationForm" novalidate class="p-4">
                    <input type="hidden" name="csrf_token" value="STATIC_CSRF_TOKEN_PLACEHOLDER_REPLACE_IN_PRODUCTION">

                    <div id="step1" class="form-step active-step">
                        <h3 class="mb-4 text-center">Stap 1: Jouw Gegevens</h3>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Volledige naam:</label>
                            <input type="text" name="fullName" id="fullName" required class="form-control form-control-custom" placeholder="Jan Jansen">
                            <div class="text-danger small mt-1 error-message" id="error-fullName"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres:</label>
                            <input type="email" name="email" id="email" required class="form-control form-control-custom" placeholder="jouw@email.com">
                            <div class="text-danger small mt-1 error-message" id="error-email"></div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefoonnummer:</label>
                            <input type="tel" name="phone" id="phone" required class="form-control form-control-custom" placeholder="0612345678">
                            <div class="text-danger small mt-1 error-message" id="error-phone"></div>
                        </div>
                        <button type="button" id="nextStep1to2Button" class="btn btn-next-custom w-100 mt-4 py-2 fw-semibold">Volgende</button>
                    </div>

                    <div id="step2" class="form-step d-none">
                        <h3 class="mb-4 text-center">Stap 2: Datum en Tijd</h3>
                        <div class="mb-3">
                            <label for="date" class="form-label">Datum:</label>
                            <input type="date" name="date" id="date" required class="form-control form-control-custom" style="color-scheme: dark;">
                            <div class="text-danger small mt-1 error-message" id="error-date"></div>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Tijd (Optioneel):</label>
                            <input type="time" name="time" id="time" class="form-control form-control-custom" style="color-scheme: dark;">
                            <div class="text-danger small mt-1 error-message" id="error-time"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" id="prevStep2to1Button" class="btn btn-prev-custom w-50 py-2 me-1 fw-semibold">Vorige</button>
                            <button type="button" id="nextStep2to3Button" class="btn btn-next-custom w-50 py-2 ms-1 fw-semibold">Volgende</button>
                        </div>
                    </div>

                    <div id="step3" class="form-step d-none">
                        <h3 class="mb-4 text-center">Stap 3: Pakket & Opmerkingen</h3>
                        <div class="mb-3">
                            <label for="package" class="form-label">Pakket:</label>
                            <select name="package" id="package" required class="form-select form-select-custom form-control-custom">
                                <option value="" class="text-dark">Kies een pakket</option>
                                <option value="standaard" class="text-dark">Standaard Pakket</option>
                                <option value="all-inclusive" class="text-dark">All-Inclusive Pakket</option>
                                <option value="alleen-dj" class="text-dark">Alleen DJ</option>
                            </select>
                            <div class="text-danger small mt-1 error-message" id="error-package"></div>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Overige opmerkingen (Optioneel):</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control form-control-custom" placeholder="Bijv. speciale verzoeken, locatie details..."></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="termsConditions" name="termsConditions" value="agreed" required>
                            <label class="form-check-label small" for="termsConditions">
                                Ik heb de <a href="boekingsvoorwaarden.php" target="_blank" class="text-info">algemene voorwaarden</a> gelezen en ga hiermee akkoord.
                            </label>
                            <div class="text-danger small mt-1 error-message" id="error-termsConditions"></div>
                        </div>

                        <div id="formResponseMessage" class="mt-3 text-center small"></div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" id="prevStep3to2Button" class="btn btn-prev-custom w-50 py-2 me-1 fw-semibold">Vorige</button>
                            <button type="submit" id="submitReservationButton" class="btn btn-submit-custom w-50 py-2 ms-1 fw-semibold">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                <span class="button-text">Voltooien</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
