<!-- <!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserveringsformulier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script> 
</head>
<body>
    <div class="modal fade" id="reserveringsModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light shadow-lg">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="reservationModalLabel">Reserveer Nu</h5>
                    <button type="button" class="btn-close btn-close-white" id="reservationModalCloseButton" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="successMessageContainer" class="d-none text-center py-5">
                        <canvas id="confettiCanvas" class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: 1;"></canvas>
                        <h2 id="successText" class="display-4 fw-bold mb-3" style="transform: scale(0); opacity: 0; position: relative; z-index: 2;">Gelukt! 🎉</h2>
                        <p id="successSubText" class="lead" style="position: relative; z-index: 2;">Je reservering wordt verwerkt.</p>
                    </div>

                    <form id="reservationForm">
                        <input type="hidden" name="csrf_token" value="STATIC_CSRF_TOKEN_PLACEHOLDER_NO_CHATBOT">
                        <div id="step1" class="form-step active-step">
                            <h3 class="mb-4 text-center">Stap 1: Jouw Gegevens</h3>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Volledige naam</label>
                                <input type="text" name="fullName" id="fullName" required class="form-control form-control-custom" placeholder="Jan Jansen">
                                <div class="text-danger small mt-1 error-message" id="error-fullName"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mailadres</label>
                                <input type="email" name="email" id="email" required class="form-control form-control-custom" placeholder="jouw@email.com">
                                <div class="text-danger small mt-1 error-message" id="error-email"></div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefoonnummer</label>
                                <input type="tel" name="phone" id="phone" required class="form-control form-control-custom" placeholder="0612345678">
                                <div class="text-danger small mt-1 error-message" id="error-phone"></div>
                            </div>
                            <button type="button" id="nextStep1to2Button" class="btn btn-next-custom w-100 mt-4 py-2 fw-semibold">Volgende</button>
                        </div>
                        <div id="step2" class="form-step d-none">
                            <h3 class="mb-4 text-center">Stap 2: Datum en Tijd</h3>
                            <div class="mb-3">
                                <label for="date" class="form-label">Datum</label>
                                <input type="date" name="date" id="date" required class="form-control form-control-custom" style="color-scheme: dark;">
                                <div class="text-danger small mt-1 error-message" id="error-date"></div>
                            </div>
                            <div class="mb-3">
                                <label for="time" class="form-label">Tijd (Optioneel)</label>
                                <input type="time" name="time" id="time" class="form-control form-control-custom" style="color-scheme: dark;">
                                <div class="text-danger small mt-1 error-message" id="error-time"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" id="prevStep2to1Button" class="btn btn-secondary w-50 py-2 me-1 fw-semibold">Vorige</button>
                                <button type="button" id="nextStep2to3Button" class="btn btn-next-custom w-50 py-2 ms-1 fw-semibold">Volgende</button>
                            </div>
                        </div>
                        <div id="step3" class="form-step d-none">
                            <h3 class="mb-4 text-center">Stap 3: Pakket & Opmerkingen</h3>
                            <div class="mb-3">
                                <label for="package" class="form-label">Pakket</label>
                                <select name="package" id="package" required class="form-select form-control-custom">
                                    <option value="" class="text-dark">Kies een pakket</option>
                                    <option value="basis" class="text-dark">Basis Pakket</option>
                                    <option value="standaard" class="text-dark">Standaard Pakket</option>
                                    <option value="premium" class="text-dark">Premium Pakket</option>
                                </select>
                                <div class="text-danger small mt-1 error-message" id="error-package"></div>
                            </div>
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Overige opmerkingen (Optioneel)</label>
                                <textarea name="remarks" id="remarks" rows="3" class="form-control form-control-custom" placeholder="Bijv. speciale verzoeken, locatie details..."></textarea>
                            </div>
                            <div id="formResponseMessage" class="mt-3 text-center small"></div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" id="prevStep3to2Button" class="btn btn-secondary w-50 py-2 me-1 fw-semibold">Vorige</button>
                                <button type="submit" class="btn btn-submit-custom w-50 py-2 ms-1 fw-semibold">Reservering Plaatsen</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Modal Variabelen ---
            const reservationModalElement = document.getElementById('reservationModal');
            const reservationModal = new bootstrap.Modal(reservationModalElement);
            const modalTitleElement = document.getElementById('reservationModalLabel');
            const form = document.getElementById('reservationForm');
            const steps = document.querySelectorAll('.form-step');
            const successMessageContainer = document.getElementById('successMessageContainer');
            const successTextElement = document.getElementById('successText'); // De "Gelukt! 🎉" titel
            const successSubTextElement = document.getElementById('successSubText'); // De beschrijvende tekst
            const confettiCanvas = document.getElementById('confettiCanvas');
            const formResponseMessage = document.getElementById('formResponseMessage');
            let currentStep = 1;
            let myConfetti; // Variabele voor de confetti instantie

            // Modal knoppen
            const openModalButton = document.getElementById('openReservationModalButton'); // Knop om modal te openen
            const closeModalButton = document.getElementById('reservationModalCloseButton');
            const nextStep1to2Button = document.getElementById('nextStep1to2Button');
            const prevStep2to1Button = document.getElementById('prevStep2to1Button');
            const nextStep2to3Button = document.getElementById('nextStep2to3Button');
            const prevStep3to2Button = document.getElementById('prevStep3to2Button');

            // --- Modal Functies ---
            function openModalLocal() {
                resetFormAndSteps();
                reservationModal.show();
            }

            function closeModalLocal() {
                reservationModal.hide();
                if (myConfetti) {
                    myConfetti.reset(); // Stop confetti als die nog bezig is
                }
                // Wacht tot modal animatie klaar is voordat formulier gereset wordt
                setTimeout(() => {
                    successMessageContainer.classList.add('d-none');
                    form.classList.remove('d-none');
                    // Zorg dat stap 1 altijd actief is bij heropenen na sluiten
                    document.getElementById('step1').classList.add('active-step');
                    document.getElementById('step1').classList.remove('d-none');
                    if(document.getElementById('step2')) {
                        document.getElementById('step2').classList.add('d-none');
                        document.getElementById('step2').classList.remove('active-step');
                    }
                    if(document.getElementById('step3')) {
                        document.getElementById('step3').classList.add('d-none');
                        document.getElementById('step3').classList.remove('active-step');
                    }
                    if (modalTitleElement) {
                        modalTitleElement.textContent = "Reserveer Nu"; // Reset modal titel
                    }
                }, 300); // Bootstrap modal fade duurt 0.15s, dus 300ms is veilig
            }

            if (openModalButton) { // Event listener voor de nieuwe open knop
                openModalButton.addEventListener('click', openModalLocal);
            }
            if (closeModalButton) {
                closeModalButton.addEventListener('click', closeModalLocal);
            }

            function navigateStep(fromStep, toStep, isNext) {
                if (isNext && !validateStep(fromStep)) {
                    return;
                }
                document.getElementById(`step${fromStep}`).classList.add('d-none');
                document.getElementById(`step${fromStep}`).classList.remove('active-step');
                document.getElementById(`step${toStep}`).classList.remove('d-none');
                document.getElementById(`step${toStep}`).classList.add('active-step');
                currentStep = toStep;
            }

            if (nextStep1to2Button) nextStep1to2Button.addEventListener('click', () => navigateStep(1, 2, true));
            if (prevStep2to1Button) prevStep2to1Button.addEventListener('click', () => navigateStep(2, 1, false));
            if (nextStep2to3Button) nextStep2to3Button.addEventListener('click', () => navigateStep(2, 3, true));
            if (prevStep3to2Button) prevStep3to2Button.addEventListener('click', () => navigateStep(3, 2, false));

            function validateStep(stepNumber) {
                let isValid = true;
                clearErrorMessages(stepNumber);
                const stepElement = document.getElementById(`step${stepNumber}`);
                const inputs = stepElement.querySelectorAll('input[required], select[required]');
                inputs.forEach(input => {
                    let hasError = false;
                    if ((input.type === 'checkbox' && !input.checked) || (input.value.trim() === '')) {
                        hasError = true;
                    }
                    if (input.type === 'email' && !isValidEmail(input.value.trim())) {
                        hasError = true;
                    }
                    if (input.type === 'tel' && !isValidPhone(input.value.trim())) {
                        hasError = true;
                    }
                    if (input.type === 'date') {
                        const selectedDate = new Date(input.value + "T00:00:00"); // Zorgt voor correcte datum parsing
                        const today = new Date();
                        today.setHours(0, 0, 0, 0); // Vergelijk alleen de datum
                        if (!input.value || selectedDate < today) { // Check ook of de datum leeg is
                            displayErrorMessage(input.id, 'Datum kan niet in het verleden liggen of is ongeldig.');
                            isValid = false;
                            hasError = true; // Markeer als error, zelfs als isValid al false is
                        }
                    }
                    if (hasError && isValid) { // Toon alleen generieke fout als er nog geen specifieke was
                        const label = input.previousElementSibling;
                        const fieldName = label && label.tagName === 'LABEL' ? label.innerText.toLowerCase() : input.name;
                        displayErrorMessage(input.id, `Vul a.u.b. ${fieldName} correct in.`);
                        isValid = false;
                    }
                });
                return isValid;
            }

            function isValidEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
            function isValidPhone(phone) { return /^[+]?[(]?[0-9]{1,4}[)]?[-\s./0-9]*$/.test(phone) && phone.replace(/\D/g, '').length >= 10; }

            function displayErrorMessage(inputId, message) {
                const errorElement = document.getElementById(`error-${inputId}`);
                if (errorElement) errorElement.textContent = message;
                const inputElement = document.getElementById(inputId);
                if (inputElement) inputElement.classList.add('is-invalid');
            }

            function clearErrorMessages(stepNumber) {
                const stepElement = document.getElementById(`step${stepNumber}`);
                stepElement.querySelectorAll('.error-message').forEach(msg => msg.textContent = '');
                stepElement.querySelectorAll('input, select').forEach(input => input.classList.remove('is-invalid'));
            }

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                if (!validateStep(currentStep)) return;
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Bezig...';
                formResponseMessage.textContent = 'Reservering wordt verwerkt (simulatie)...';
                formResponseMessage.classList.remove('text-danger', 'text-success');
                formResponseMessage.classList.add('text-warning');

                setTimeout(() => {
                    showSuccessAnimationAndConfetti();
                    submitButton.disabled = false;
                    submitButton.textContent = 'Reservering Plaatsen';
                    formResponseMessage.textContent = '';
                    setTimeout(() => {
                        closeModalLocal();
                    }, 5000); // Modal sluit na 5 seconden
                }, 2000); // Simuleer verwerkingstijd
            });

            function showSuccessAnimationAndConfetti() {
                form.classList.add('d-none'); // Verberg het formulier
                successMessageContainer.classList.remove('d-none'); // Toon succesbericht container

                if (modalTitleElement) { // Update modal header titel
                    modalTitleElement.textContent = "Reserveer Nu - Gelukt!";
                }
                // De titel "Gelukt! 🎉" staat nu al in de body via #successTextElement
                if (successSubTextElement) { // Update beschrijvende tekst
                    successSubTextElement.textContent = "Je reservering wordt behandeld. Verwacht binnen 24 uur een reactie";
                }
                successTextElement.classList.add('animate-popup'); // Start de "Gelukt! 🎉" animatie

                if (typeof confetti === 'function') {
                    myConfetti = confetti.create(confettiCanvas, { resize: true, useWorker: true });
                    const colors = ['#00ffaa', '#6060ff'];
                    const duration = 3.2 * 1000; // Confetti duur: 3.2 seconden
                    const animationEnd = Date.now() + duration;

                    function randomInRange(min, max) { return Math.random() * (max - min) + min; }

                    // Confetti "shot" functie
                    function fireConfettiShot() {
                        myConfetti({
                            particleCount: 100, // Aantal particles
                            angle: 90,          // Richting (90 is recht omhoog)
                            spread: 60,         // Spreiding van de "shot"
                            origin: { y: 0.6 }, // Startpunt van de confetti (0.5 is midden)
                            colors: colors,
                            scalar: randomInRange(0.8, 1.5), // Grootte variatie
                            drift: randomInRange(-0.1, 0.1) // Kleine zijwaartse beweging
                        });
                    }

                    // Vuur de eerste shot af
                    fireConfettiShot();

                    // Optioneel: herhaal de shot een paar keer binnen de duratie voor een voller effect
                    let interval = setInterval(() => {
                        if (Date.now() < animationEnd - 500) { // Stop iets eerder om abrupte stop te voorkomen
                           // fireConfettiShot(); // Haal commentaar weg voor herhaalde shots
                        } else {
                            clearInterval(interval);
                        }
                    }, 700); // Interval voor herhaalde shots (bijv. elke 700ms)

                    // Zorg dat confetti stopt na de ingestelde duratie, zelfs als interval nog loopt
                    setTimeout(() => {
                        if (myConfetti) {
                            myConfetti.reset();
                        }
                        clearInterval(interval);
                    }, duration);


                } else { console.warn('Confetti library not loaded.'); }
            }

            function resetFormAndSteps() {
                form.reset();
                currentStep = 1;
                steps.forEach((step, index) => {
                    step.classList.toggle('d-none', index !== 0);
                    step.classList.toggle('active-step', index === 0);
                });
                document.querySelectorAll('.error-message').forEach(msg => msg.textContent = '');
                document.querySelectorAll('input.is-invalid, select.is-invalid').forEach(input => input.classList.remove('is-invalid'));
                formResponseMessage.textContent = '';
                formResponseMessage.className = 'mt-3 text-center small'; // Reset classes
                successMessageContainer.classList.add('d-none');
                form.classList.remove('d-none'); // Toon het formulier weer
                successTextElement.classList.remove('animate-popup'); // Reset animatie klasse
                successTextElement.style.transform = 'scale(0) translateY(50px)'; // Reset animatie direct
                successTextElement.style.opacity = '0';
                if (modalTitleElement) { // Reset modal header titel
                    modalTitleElement.textContent = "Reserveer Nu";
                }
                if (myConfetti) { // Reset confetti als het bestaat
                    myConfetti.reset();
                }
            }
        });
    </script>
</body>
</html> -->