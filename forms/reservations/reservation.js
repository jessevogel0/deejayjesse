// reservation.js - Voorbeeld van hoe je de validatie kunt toevoegen

document.addEventListener('DOMContentLoaded', function () {
    // DOM-elementen
    const reservationModalElement = document.getElementById('reserveringsModal');
    const modalTitleElement = document.getElementById('reservationModalLabel'); // Toegevoegd voor titelwijziging
    const form = document.getElementById('reservationForm');
    const steps = Array.from(document.querySelectorAll('.form-step'));
    const nextButtons = document.querySelectorAll('.btn-next-custom');
    const prevButtons = document.querySelectorAll('.btn-prev-custom');
    const submitButton = document.getElementById('submitReservationButton');
    const submitButtonText = submitButton ? submitButton.querySelector('.button-text') : null;
    const submitButtonSpinner = submitButton ? submitButton.querySelector('.spinner-border') : null;
    const formResponseMessage = document.getElementById('formResponseMessage');
    const successMessageContainer = document.getElementById('successMessageContainer');
    const successTextElement = document.getElementById('successText'); 
    const successSubTextElement = document.getElementById('successSubText'); 
    const confettiCanvas = document.getElementById('confettiCanvas'); // Toegevoegd voor confetti

    // State variabelen
    let currentStep = 0; // Start bij stap 0 (index-gebaseerd)
    let confettiEffect = null; // Voor de confetti instantie
    let confettiTimeoutId = null; // Om de confetti timeout te beheren
    let reservationModalInstance = null; // Voor de Bootstrap modal instantie

    // --- Functie om een NIEUWE Bootstrap modal instantie aan te maken ---
    function createNewModalInstance() {
        if (reservationModalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                // Verwijder eventuele bestaande instantie om conflicten te voorkomen
                const existingModal = bootstrap.Modal.getInstance(reservationModalElement);
                if (existingModal) {
                    existingModal.dispose();
                }
                return new bootstrap.Modal(reservationModalElement, {
                    // backdrop: 'static', // Al in HTML
                    // keyboard: false    // Al in HTML
                });
            } catch (e) {
                console.error('[Modal] Fout bij aanmaken nieuwe Bootstrap Modal instantie:', e);
                return null;
            }
        } else {
            console.error('[Modal] Bootstrap Modal is niet beschikbaar of element #reserveringsModal niet gevonden.');
            return null;
        }
    }
    
    // --- Functie om confetti te stoppen en resetten ---
    function stopAndResetConfetti() {
        if (confettiTimeoutId) {
            clearTimeout(confettiTimeoutId);
            confettiTimeoutId = null;
        }
        if (confettiEffect && typeof confettiEffect.reset === 'function') {
            try {
                confettiEffect.reset();
            } catch(e) { console.warn("Confetti reset error:", e); }
            confettiEffect = null;
        }
    }

    // --- Functie voor succesanimatie en confetti ---
    function showSuccessAnimationAndConfetti() {
        if (form) {
            form.classList.add('d-none');
            form.style.display = 'none'; // Extra zekerheid
        }
        if (successMessageContainer) {
            successMessageContainer.classList.remove('d-none');
            successMessageContainer.style.display = 'flex'; // Zorg dat het flex is voor centreren
        }
        if (modalTitleElement) modalTitleElement.textContent = "Reservering Gelukt!"; // Titel aanpassen
        
        // Haal naam op uit formulierdata (als het nog beschikbaar is, anders moet je het opslaan)
        const formData = new FormData(form); // Haal opnieuw op of sla op in een globale var
        const fullName = formData.get('fullName');

        if (successSubTextElement) {
            successSubTextElement.textContent = fullName ? 
                `Bedankt, ${fullName}! Je reservering is succesvol ontvangen.` :
                "Bedankt! Je reservering is succesvol ontvangen.";
        }
        
        if (successTextElement) {
            successTextElement.style.opacity = '0';
            successTextElement.style.transform = 'scale(0.5) translateY(30px)';
            // Force reflow voor animatie reset
            void successTextElement.offsetWidth; 
            // CSS class voor animatie (zorg dat deze bestaat in je CSS)
            // .animate-popup { animation: popup 0.5s ease-out forwards; }
            // @keyframes popup { 0% { transform: scale(0.5) translateY(30px); opacity: 0; } 100% { transform: scale(1) translateY(0); opacity: 1; } }
            successTextElement.classList.add('animate-popup'); 
            successTextElement.style.opacity = '1'; // Zet opacity na toevoegen class
            successTextElement.style.transform = 'scale(1) translateY(0)';
        }

        if (typeof confetti === 'function' && confettiCanvas) {
            stopAndResetConfetti(); 
            try {
                confettiEffect = confetti.create(confettiCanvas, { resize: true, useWorker: true });
                confettiEffect({
                    particleCount: 150, // Meer confetti
                    angle: 90, 
                    spread: 70, // Iets wijdere spreiding
                    origin: { y: 0.55 }, // Start iets lager
                    colors: ['#00ffaa', '#6060ff', '#ffffff'], // Voeg wit toe
                    scalar: 1.2, 
                    gravity: 0.8, 
                    ticks: 350 // Iets langer zichtbaar
                });
                confettiTimeoutId = setTimeout(stopAndResetConfetti, 4000); // Iets langer
            } catch (e) {
                console.error("Confetti error:", e);
            }
        } else {
            console.warn('Confetti library of canvas (#confettiCanvas) niet gevonden.');
        }
    }
    
    // --- Functie om de modal UI te resetten naar de beginstaat ---
    function resetModalUIToInitialState() { 
        if (successMessageContainer) {
            successMessageContainer.classList.add('d-none');
            successMessageContainer.style.display = 'none';
        }
        if (form) {
            form.classList.remove('d-none');
            form.style.display = ''; // Herstel display
            form.reset(); 
        }
        steps.forEach((step, index) => {
            if (step) {
                step.classList.toggle('d-none', index !== 0);
                step.classList.toggle('active-step', index === 0);
                // Verwijder validatie klassen
                step.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                    el.classList.remove('is-invalid', 'is-valid');
                });
                // Verwijder error messages
                step.querySelectorAll('.error-message').forEach(el => {
                    el.textContent = '';
                });
            }
        });
        currentStep = 0; // Reset naar stap 0 (index-gebaseerd)
        if (modalTitleElement) modalTitleElement.textContent = "Reserveer Nu";
        if (successTextElement) {
            successTextElement.classList.remove('animate-popup'); // Verwijder animatie class
            successTextElement.style.transform = 'scale(0) translateY(50px)';
            successTextElement.style.opacity = '0';
        }
        if (successSubTextElement) successSubTextElement.textContent = '';
        if (submitButton) submitButton.disabled = false;
        if (submitButtonText) submitButtonText.textContent = 'Voltooien';
        if (submitButtonSpinner) submitButtonSpinner.classList.add('d-none');
        if (formResponseMessage) {
            formResponseMessage.textContent = '';
            formResponseMessage.className = 'mt-3 text-center small';
        }
    }

    // --- Functie om de modal te sluiten en de pagina te herladen ---
    function closeModalAndReloadPage() {
        if (reservationModalInstance && typeof reservationModalInstance.hide === 'function') {
            try {
                reservationModalInstance.hide(); 
            } catch (e) { console.warn("Modal hide error:", e); }
        } else if (reservationModalElement) { // Fallback als instantie weg is
            const bsModal = bootstrap.Modal.getInstance(reservationModalElement);
            if (bsModal) {
                try { bsModal.hide(); } catch(e) { console.warn("Modal hide error (fallback):", e); }
            }
        }
        stopAndResetConfetti(); 
        // Wacht even tot modal animatie klaar is voor herladen
        setTimeout(() => {
            window.location.reload(); 
        }, 500);
    }

    // --- Functie om een specifieke input te valideren en een error message te tonen ---
    function validateInput(inputElement, errorMessageElement, validationFn, message) {
        if (!inputElement) return true; 
        if (!validationFn(inputElement.value.trim())) {
            inputElement.classList.add('is-invalid');
            if (errorMessageElement) errorMessageElement.textContent = message;
            return false;
        }
        inputElement.classList.remove('is-invalid');
        inputElement.classList.add('is-valid'); 
        if (errorMessageElement) errorMessageElement.textContent = '';
        return true;
    }

    // --- Functie om een checkbox te valideren ---
    function validateCheckbox(checkboxElement, errorMessageElement, message) {
        if (!checkboxElement) return true; 
        if (!checkboxElement.checked) {
            checkboxElement.classList.add('is-invalid');
            if (errorMessageElement) {
                errorMessageElement.textContent = message;
                errorMessageElement.style.display = 'block'; 
            }
            return false;
        }
        checkboxElement.classList.remove('is-invalid');
        checkboxElement.classList.add('is-valid'); 
        if (errorMessageElement) {
            errorMessageElement.textContent = '';
            errorMessageElement.style.display = 'none'; 
        }
        return true;
    }

    // --- Functie om de huidige stap te valideren ---
    function validateStep(stepIndex) {
        let isValid = true;
        if (stepIndex < 0 || stepIndex >= steps.length) return true; 

        const currentStepDiv = steps[stepIndex];
        if (!currentStepDiv) return true;

        currentStepDiv.querySelectorAll('.form-control-custom, .form-select-custom, .form-check-input').forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
            const errorMsgEl = document.getElementById(`error-${input.id}`);
            if (errorMsgEl) {
                errorMsgEl.textContent = '';
            }
        });

        if (stepIndex === 0) { 
            const fullName = document.getElementById('fullName');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            if (!validateInput(fullName, document.getElementById('error-fullName'), val => val.length >= 2, 'Vul een geldige naam in (min. 2 tekens).')) isValid = false;
            if (!validateInput(email, document.getElementById('error-email'), val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val), 'Vul een geldig e-mailadres in.')) isValid = false;
            if (!validateInput(phone, document.getElementById('error-phone'), val => /^[0-9\s\-\+\(\)]{7,}$/.test(val), 'Vul een geldig telefoonnummer in.')) isValid = false;
        } else if (stepIndex === 1) { 
            const date = document.getElementById('date');
            const today = new Date();
            today.setHours(0,0,0,0); 

            if (!validateInput(date, document.getElementById('error-date'), val => {
                if (!val) return false; 
                const selectedDate = new Date(val);
                selectedDate.setHours(0,0,0,0); 
                return selectedDate >= today;
            }, 'Kies een datum in de toekomst.')) isValid = false;
            
            const timeInput = document.getElementById('time');
            if (timeInput && timeInput.value) { 
                 timeInput.classList.add('is-valid');
            }

        } else if (stepIndex === 2) { 
            const packageSelect = document.getElementById('package');
            if (!validateInput(packageSelect, document.getElementById('error-package'), val => val !== '', 'Kies een pakket.')) isValid = false;
            
            const termsCheckbox = document.getElementById('termsConditions');
            if (!validateCheckbox(termsCheckbox, document.getElementById('error-termsConditions'), 'U dient akkoord te gaan met de algemene voorwaarden.')) isValid = false;
        }
        return isValid;
    }

    // --- Functie om naar een stap te gaan ---
    function goToStep(stepIndex) {
        if (stepIndex < 0 || stepIndex >= steps.length) return; 

        steps.forEach((step, index) => {
            step.classList.toggle('active-step', index === stepIndex);
            step.classList.toggle('d-none', index !== stepIndex);
            // Zorg dat display ook correct is voor animaties
            step.style.display = (index === stepIndex) ? 'block' : 'none';
        });
        currentStep = stepIndex; 
    }

    // Event listeners voor "Volgende" knoppen
    nextButtons.forEach(button => { 
        button.addEventListener('click', () => {
            if (validateStep(currentStep)) { 
                if (currentStep < steps.length - 1) { 
                     goToStep(currentStep + 1);
                }
            }
        });
    });

    // Event listeners voor "Vorige" knoppen
    prevButtons.forEach(button => { 
        button.addEventListener('click', () => {
            if (currentStep > 0) { 
                goToStep(currentStep - 1);
            }
        });
    });

    // Event listener voor het verzenden van het formulier (API CALL)
    if (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            if (!validateStep(2)) { 
                console.log("Validatie stap 3 (voorwaarden) mislukt bij submit");
                return;
            }

            if (submitButton) submitButton.disabled = true;
            if (submitButtonText) submitButtonText.textContent = 'Bezig...'; 
            if (submitButtonSpinner) submitButtonSpinner.classList.remove('d-none');

            if (formResponseMessage) {
                formResponseMessage.textContent = 'Reservering wordt verwerkt...';
                formResponseMessage.className = 'mt-3 text-center small text-info';
            }

            const formData = new FormData(form);
            const reservationData = {};
            formData.forEach((value, key) => {
                if (key.endsWith('[]')) {
                    const cleanKey = key.slice(0, -2);
                    if (!reservationData[cleanKey]) {
                        reservationData[cleanKey] = [];
                    }
                    reservationData[cleanKey].push(value);
                } else {
                    reservationData[key] = value;
                }
            });
            
            console.log("Te verzenden data:", reservationData); 

            let responseTextForDebug = ''; 
            try {
                const response = await fetch('api/addReservation.php', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(reservationData)
                });

                responseTextForDebug = await response.text(); 
                
                let result = null;
                if (!response.ok) { 
                    throw new Error(`Serverfout (status: ${response.status} ${response.statusText}). Pad: ${response.url}. Response: ${responseTextForDebug}`);
                }

                if (responseTextForDebug.trim() === '') {
                     throw new Error(`Server gaf een leeg antwoord (status: ${response.status}).`);
                }
                try {
                    result = JSON.parse(responseTextForDebug);
                } catch (parseError) {
                    console.error('JSON Parse Fout:', parseError);
                    console.error('Ontvangen response tekst (geen valide JSON):', responseTextForDebug);
                    throw new Error(`Server gaf een onverwacht antwoord (geen geldige JSON). Details: ${parseError.message}. Ontvangen tekst: ${responseTextForDebug}`);
                }

                if (result && result.success) { 
                    if (formResponseMessage) formResponseMessage.textContent = '';
                    showSuccessAnimationAndConfetti(); // Functie voor succes animatie
                    setTimeout(closeModalAndReloadPage, 7000); // Iets langer voor confetti & lezen
                } else {
                    const serverMessage = result && result.message ? result.message : `Onbekende serverfout na succesvolle verbinding, maar success was niet true.`;
                    throw new Error(serverMessage);
                }
            } catch (error) {
                console.error('API of Fetch Fout:', error);
                let errorMessage = `Fout: ${error.message || 'Kon reservering niet versturen. Controleer je verbinding.'}`;
                
                if (formResponseMessage) {
                    formResponseMessage.textContent = errorMessage;
                    formResponseMessage.className = 'mt-3 text-center small text-danger';
                }
                if (submitButton) submitButton.disabled = false;
                if (submitButtonText) submitButtonText.textContent = 'Voltooien';
                if (submitButtonSpinner) submitButtonSpinner.classList.add('d-none');
            }
        });
    }

    // Initialiseer de eerste stap (index 0)
    if (steps.length > 0) {
        goToStep(0);
    }

    // Event listener voor het sluiten van de modal (X knop of buiten klikken)
    if (reservationModalElement && form) { 
        reservationModalElement.addEventListener('hidden.bs.modal', function () {
            resetModalUIToInitialState(); 
            stopAndResetConfetti();
            // Verwijder de modal instantie om te zorgen dat het opnieuw gemaakt wordt bij volgende opening
            if (reservationModalInstance && typeof reservationModalInstance.dispose === 'function') {
                try { reservationModalInstance.dispose(); } catch(e) { /* negeer */ }
            }
            reservationModalInstance = null; // Belangrijk!
        });
    }
    
    // Event listener voor de custom sluitknop in de header van de modal
    const customCloseButton = document.getElementById('reservationModalCloseButton');
    if (customCloseButton) {
        customCloseButton.addEventListener('click', closeModalAndReloadPage);
    }


    // Automatisch selecteren van pakket als data-package-value aanwezig is
    // en modal instantie correct beheren bij openen
    document.querySelectorAll('[data-bs-target="#reserveringsModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const packageValue = this.getAttribute('data-package-value') || '';
            
            // Reset de UI en confetti
            resetModalUIToInitialState();
            stopAndResetConfetti();

            // Stel het pakket in als het is meegegeven
            const packageSelect = document.getElementById('package');
            if (packageValue && packageSelect) {
                packageSelect.value = packageValue;
                // Optioneel: trigger validatie of 'is-valid' class
                validateInput(packageSelect, document.getElementById('error-package'), val => val !== '', 'Kies een pakket.');
            }
            
            // Zorg voor een schone modal instantie
            if (reservationModalInstance && typeof reservationModalInstance.dispose === 'function') {
                try { reservationModalInstance.dispose(); } catch(e) { /* negeer */ }
            }
            reservationModalInstance = createNewModalInstance(); // Maak nieuwe instantie
            
            if (reservationModalInstance && typeof reservationModalInstance.show === 'function') {
                reservationModalInstance.show();
            } else if (reservationModalElement) { // Fallback als createNewModalInstance faalt maar element bestaat
                 const fallbackModal = new bootstrap.Modal(reservationModalElement);
                 fallbackModal.show();
                 reservationModalInstance = fallbackModal; // Sla op voor later
            } else {
                 console.warn("[Modal] Kan modal niet tonen: instantie of element niet beschikbaar.");
            }
        });
    });
});
