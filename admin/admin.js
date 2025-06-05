// admin/admin.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialiseer Bootstrap Tabs (Pills)
    const tabEl = document.querySelector('#pills-tab');
    if (tabEl) {
        // Bootstrap Tab initialisatie kan direct op de tab elementen gebeuren,
        // de 'new bootstrap.Tab' is meer voor programmatische controle.
        // Voor het activeren van de eerste tab, zorgt Bootstrap's data-bs-toggle al voor de 'active' class.
        // We roepen direct fetchAndDisplayReservations() aan om de initiële data te laden.
        fetchAndDisplayReservations();

        tabEl.addEventListener('shown.bs.tab', event => {
            const activeTabButton = event.target; // De knop die geactiveerd is
            const category = activeTabButton.dataset.category;
            console.log(`Tab shown: ${category}`); // Debugging
            fetchAndDisplayReservations(); // Herlaad en toon de data voor de geselecteerde categorie
        });
    } else {
        console.error("Tab element #pills-tab not found.");
        // Fallback if tab system is not present, still try to fetch data
        fetchAndDisplayReservations();
    }

    // Selecteer de container elementen voor alle tabellen
    const reservationContainers = {
        'Pending': document.getElementById('pendingReservationsContainer'),
        'Accepted': document.getElementById('acceptedReservationsContainer'),
        'Declined': document.getElementById('declinedReservationsContainer'),
        'Verlopen': document.getElementById('expiredReservationsContainer')
    };

    let allReservations = []; // Hier slaan we alle opgehaalde reserveringen op

    // Event listeners voor zoeken en sorteren
    const searchInput = document.getElementById('reservationSearch');
    const sortSelect = document.getElementById('reservationSort');

    if (searchInput) {
        searchInput.addEventListener('keyup', fetchAndDisplayReservations);
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', fetchAndDisplayReservations);
    }

    // Hoofdfunctie om reserveringen op te halen, te filteren, te sorteren en weer te geven
    function fetchAndDisplayReservations() {
        fetch('../api/reservations.php') // Zorg dat dit pad correct is
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    allReservations = data.reservations; // Sla alle reserveringen op
                    processAndDisplayReservations();
                } else {
                    console.error('Fout bij ophalen reserveringen:', data.message);
                    showMessageModal('Fout', 'Kon reserveringen niet laden: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showMessageModal('Fout', 'Er is een fout opgetreden bij het ophalen van de reserveringen: ' + error.message);
            });
    }

    // Functie om reserveringen te filteren en sorteren en vervolgens weer te geven
    function processAndDisplayReservations() {
        const activeTabButton = document.querySelector('#pills-tab .nav-link.active');
        const activeCategory = activeTabButton ? activeTabButton.dataset.category : 'Pending';

        let filteredReservations = allReservations.filter(res => res.status === activeCategory);

        const searchTerm = document.getElementById('reservationSearch')?.value.toLowerCase() || '';
        if (searchTerm) {
            filteredReservations = filteredReservations.filter(res =>
                res.full_name.toLowerCase().includes(searchTerm) ||
                res.email.toLowerCase().includes(searchTerm) ||
                res.id.toString().includes(searchTerm) ||
                (res.remarks && res.remarks.toLowerCase().includes(searchTerm))
            );
        }

        const sortValue = document.getElementById('reservationSort')?.value || 'date_desc';
        filteredReservations.sort((a, b) => {
            if (sortValue === 'date_asc') {
                return new Date(a.reservation_date) - new Date(b.reservation_date);
            } else if (sortValue === 'date_desc') {
                return new Date(b.reservation_date) - new Date(a.reservation_date);
            } else if (sortValue === 'id_asc') {
                return a.id - b.id;
            } else if (sortValue === 'id_desc') {
                return b.id - a.id;
            }
            return 0;
        });

        // Leeg alle containers en vul alleen de actieve container opnieuw
        for (const cat in reservationContainers) {
            if (reservationContainers[cat]) reservationContainers[cat].innerHTML = '';
        }

        const targetContainer = reservationContainers[activeCategory];
        if (!targetContainer) {
            console.error(`Container voor categorie "${activeCategory}" niet gevonden.`);
            return;
        }

        if (filteredReservations.length === 0) {
            targetContainer.innerHTML = `<p class="text-center mt-3">Geen ${activeCategory} reserveringen gevonden.</p>`;
            return;
        }

        filteredReservations.forEach(reservation => {
            const card = createReservationCard(reservation, activeCategory);
            targetContainer.appendChild(card);
        });
    }

    // Functie om een modale boodschap weer te geven in plaats van alert()
    function showMessageModal(title, message, showConfirm = false, onConfirm = null) {
        let messageModal = document.getElementById('messageModal');
        if (!messageModal) {
            messageModal = document.createElement('div');
            messageModal.id = 'messageModal';
            messageModal.classList.add('modal', 'fade');
            messageModal.setAttribute('tabindex', '-1');
            messageModal.setAttribute('aria-labelledby', 'messageModalLabel');
            messageModal.setAttribute('aria-hidden', 'true');
            document.body.appendChild(messageModal);
        }

        messageModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="messageModalLabel">${title}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${message}
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        ${showConfirm ? '<button type="button" class="btn btn-primary" id="confirmActionButton">Bevestigen</button>' : ''}
                    </div>
                </div>
            </div>
        `;

        const bsModal = new bootstrap.Modal(messageModal);
        bsModal.show();

        if (showConfirm && onConfirm) {
            const confirmButton = messageModal.querySelector('#confirmActionButton');
            if (confirmButton) {
                confirmButton.onclick = () => {
                    bsModal.hide();
                    onConfirm();
                };
            }
        }
    }

    // Nieuwe functie om een reserveringskaart te maken
    function createReservationCard(reservation, category) {
        const card = document.createElement('div');
        card.classList.add('reservation-card', 'mb-3'); // Bootstrap styling voor kaarten

        // Unieke ID voor de collapse target
        const collapseId = `collapseReservation${reservation.id}`;

        // Controleer op "NIEUW!" label
        const isNew = checkIfNew(reservation.submission_timestamp);
        const newLabelHtml = isNew ? `<span class="new-label">NIEUW!</span>` : '';

        card.innerHTML = `
            <div class="reservation-card-header d-flex justify-content-between align-items-center"
                 data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}" style="cursor: pointer;">
                <h5 class="mb-0 card-title-text">#${reservation.id} - Reservering van ${reservation.full_name} ${newLabelHtml}</h5>
                <!-- Icoon voor in-/uitklappen kan hier geplaatst worden als je dat wilt -->
            </div>
            <div id="${collapseId}" class="collapse"> <!-- Standaard ingeklapt -->
                <div class="reservation-card-body">
                    <div class="reservation-card-section mb-4"> <!-- Extra ruimte -->
                        <h4 class="fw-bold section-title">Commentaar</h4>
                        <p><span class="comment-text section-value-text">${reservation.remarks || 'Geen opmerkingen'}</span></p>
                    </div>
                    <hr class="line-distinction">
                    <div class="reservation-card-section mb-4"> <!-- Extra ruimte -->
                        <h4 class="fw-bold section-title">Persoonlijke Gegevens</h4>
                        <p><span class="section-label-text">Email:</span> <a href="mailto:${reservation.email}" class="text-light-link section-value-text">${reservation.email}</a></p>
                        <p><span class="section-label-text">Telefoon:</span> <a href="tel:${reservation.phone}" class="text-light-link section-value-text">${reservation.phone}</a></p>
                    </div>
                    <hr class="line-distinction">
                    <div class="reservation-card-section mb-4"> <!-- Extra ruimte -->
                        <h4 class="fw-bold section-title">Details Feest</h4>
                        <p><span class="section-label-text">Datum:</span> <span class="section-value-text">${reservation.reservation_date}</span></p>
                        <p><span class="section-label-text">Tijd:</span> <span class="section-value-text">${reservation.reservation_time || 'N.V.T.'}</span></p>
                    </div>
                    <hr class="line-distinction">
                    <div class="reservation-card-section mb-4"> <!-- Extra ruimte -->
                        <h4 class="fw-bold section-title">Gekozen Pakket</h4>
                        <p><span class="section-label-text">Pakket Naam:</span> <span class="section-value-text">${reservation.package_type}</span></p>
                    </div>
                    <hr class="line-distinction">
                    <div class="reservation-card-section">
                        <h4 class="fw-bold section-title">Kies:</h4>
                        <div class="reservation-actions mt-2">
                            ${getActionButtonsHtml(reservation, category)}
                        </div>
                    </div>
                </div>
            </div>
            <div class="reservation-card-footer"> <!-- Deze div is nu apart van de collapse -->
                <p class="footer-text"><span class="section-label-text">Aangemaakt op:</span> <span class="section-value-text">${formatTimestamp(reservation.submission_timestamp)}</span></p>
            </div>
        `;

        return card;
    }

    // Helper functie om actieknoppen HTML te genereren
    function getActionButtonsHtml(reservation, category) {
        let buttonsHtml = '';
        if (category === 'Pending') {
            buttonsHtml += `<button type="button" class="btn btn-success btn-sm me-2" onclick="updateReservationStatus('${reservation.id}', 'Accepted')">Accepteren</button>`;
            buttonsHtml += `<button type="button" class="btn btn-danger btn-sm me-2" onclick="updateReservationStatus('${reservation.id}', 'Declined')">Weigeren</button>`;
        }
        // De "Verlopen Maken" knop is hier verwijderd, zoals gevraagd.
        // Als de status 'Verlopen' is, dan toon de "Herstellen" knop.
        else if (category === 'Verlopen') {
            buttonsHtml += `<button type="button" class="btn btn-info btn-sm" onclick="updateReservationStatus('${reservation.id}', 'Pending')">Herstellen naar Pending</button>`;
        }
        return buttonsHtml;
    }

    // Functie om de timestamp te formatteren voor weergave
    function formatTimestamp(timestamp) {
        if (!timestamp) return '-';
        const date = new Date(timestamp);
        return `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
    }

    // Functie om te controleren of een reservering "NIEUW!" is
    function checkIfNew(createdAt) {
        if (!createdAt) return false;
        const creationTime = new Date(createdAt).getTime();
        const currentTime = new Date().getTime();
        const fortyFiveMinutes = 45 * 60 * 1000;
        return (currentTime - creationTime) < fortyFiveMinutes;
    }

    // Maak de updateReservationStatus en softDeleteReservation functies globaal toegankelijk
    window.updateReservationStatus = function(reservationId, newStatus) {
        showMessageModal('Status wijzigen', `Weet je zeker dat je de status van reservering #${reservationId} wilt wijzigen naar "${newStatus}"?`, true, () => {
            fetch('../api/updateReservation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${reservationId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessageModal('Succes', `Reservering #${reservationId} succesvol bijgewerkt naar "${newStatus}".`);
                    fetchAndDisplayReservations();
                } else {
                    showMessageModal('Fout', 'Fout bij het bijwerken van de reservering: ' + (data.message || 'Onbekende fout'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessageModal('Fout', 'Er is een fout opgetreden bij het bijwerken van de status.');
            });
        });
    };

    window.softDeleteReservation = function(reservationId) {
        showMessageModal('Reservering markeren als verlopen', `Weet je zeker dat je reservering #${reservationId} wilt markeren als verlopen? Deze actie verplaatst de reservering naar de "Verlopen" lijst.`, true, () => {
            fetch('../api/updateReservation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${reservationId}&status=Verlopen`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessageModal('Succes', 'Reservering succesvol gemarkeerd als verlopen.');
                    fetchAndDisplayReservations();
                } else {
                    showMessageModal('Fout', 'Fout bij het markeren als verlopen: ' + (data.message || 'Onbekende fout'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessageModal('Fout', 'Er is een fout opgetreden bij het markeren als verlopen.');
            });
        });
    };
});
