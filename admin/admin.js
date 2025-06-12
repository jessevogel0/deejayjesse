// admin/admin.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS Loaded and DOM fully parsed.');

    const tabEl = document.querySelector('#pills-tab');
    const searchInput = document.getElementById('reservationSearch');
    const sortSelect = document.getElementById('reservationSort');

    const reservationContainers = {
        'Pending': document.getElementById('pendingReservationsContainer'),
        'Accepted': document.getElementById('acceptedReservationsContainer'),
        'Declined': document.getElementById('declinedReservationsContainer'),
        'Verlopen': document.getElementById('expiredReservationsContainer')
    };

    let allReservations = [];

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            fetchAndDisplayReservations();
        });
    } else {
        console.error("Element with ID 'reservationSearch' not found!");
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            fetchAndDisplayReservations();
        });
    } else {
        console.error("Element with ID 'reservationSort' not found!");
    }

    function fetchAndDisplayReservations() {
        // console.log('fetchAndDisplayReservations CALLED'); // Kan aan voor debuggen
        const activeTabButton = document.querySelector('#pills-tab .nav-link.active');
        const activeFrontendCategory = activeTabButton ? activeTabButton.dataset.category : 'Pending';
        let apiUrl = '../api/reservations.php';
        const params = new URLSearchParams();
        if (activeFrontendCategory) params.append('status', activeFrontendCategory);
        const currentSearchTerm = searchInput ? searchInput.value : '';
        if (currentSearchTerm) params.append('search', currentSearchTerm);
        const currentSortValue = sortSelect ? sortSelect.value : 'submission_timestamp DESC';
        if (currentSortValue) params.append('sort', currentSortValue);
        if (params.toString()) apiUrl += `?${params.toString()}`;
        // console.log('API URL to be fetched:', apiUrl);

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(`HTTP error! status: ${response.status}, message: ${text}`); });
                }
                return response.json();
            })
            .then(data => {
                const conditionEvaluation = (data && data.success && data.data && typeof data.data === 'object' && Array.isArray(data.data.reservations));

                if (conditionEvaluation) {
                    allReservations = data.data.reservations;
                    processAndDisplayReservations(activeFrontendCategory);
                } else {
                    let errorMsg = 'Onbekende fout bij ophalen reserveringen.';
                    if (data && data.message) {
                        errorMsg = `Fout bij ophalen reserveringen: ${data.message}`;
                    } else if (data) {
                        errorMsg = 'Fout bij ophalen reserveringen: Ongeldige data structuur in API response.';
                    } else {
                        errorMsg = 'Fout bij ophalen reserveringen: Geen data ontvangen van API.';
                    }
                    console.error(errorMsg, data);
                    showMessageModal('Fout', errorMsg);
                    clearAllReservationContainers(true, errorMsg);
                    allReservations = [];
                }
            })
            .catch(error => {
                console.error('Fetch error in fetchAndDisplayReservations:', error);
                const errorMsg = `Er is een fout opgetreden bij het ophalen van de reserveringen: ${error.message}`;
                showMessageModal('Fout', errorMsg);
                clearAllReservationContainers(true, errorMsg);
                allReservations = [];
            });
    }
    
    function clearAllReservationContainers(showError = false, errorMessage = "Laden van reserveringen mislukt.") {
        for (const cat in reservationContainers) {
            if (reservationContainers[cat]) {
                reservationContainers[cat].innerHTML = showError ? `<p class="text-center mt-3 text-danger">${errorMessage}</p>` : '';
            }
        }
    }

    function processAndDisplayReservations(activeFrontendCategory) {
        let reservationsToDisplay = allReservations; 
        clearAllReservationContainers();
        const targetContainer = reservationContainers[activeFrontendCategory];
        if (!targetContainer) {
            console.error(`Container voor categorie "${activeFrontendCategory}" niet gevonden.`);
            return;
        }
        if (!Array.isArray(reservationsToDisplay)) {
            console.error('Error: reservationsToDisplay is not an array. Value:', reservationsToDisplay);
            targetContainer.innerHTML = `<p class="text-center mt-3 text-danger">Fout: Kon reserveringen niet correct verwerken.</p>`;
            return;
        }
        if (reservationsToDisplay.length === 0) {
            targetContainer.innerHTML = `<p class="text-center mt-3">Geen ${activeFrontendCategory.toLowerCase()} reserveringen gevonden die voldoen aan de criteria.</p>`;
            return;
        }
        reservationsToDisplay.forEach(reservation => {
            const card = createReservationCard(reservation, activeFrontendCategory);
            targetContainer.appendChild(card);
        });
    }

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
                    <div class="modal-body"><p>${message}</p></div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        ${showConfirm ? '<button type="button" class="btn btn-primary" id="confirmActionButtonModal">Bevestigen</button>' : ''}
                    </div>
                </div>
            </div>`;
        const bsModal = new bootstrap.Modal(messageModal);
        bsModal.show();
        if (showConfirm && onConfirm) {
            const confirmButton = messageModal.querySelector('#confirmActionButtonModal');
            if (confirmButton) {
                const newConfirmButton = confirmButton.cloneNode(true);
                confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
                newConfirmButton.addEventListener('click', () => { bsModal.hide(); onConfirm(); });
            }
        }
    }

    // --- GEWIJZIGDE createReservationCard functie ---
    function createReservationCard(reservation, frontendCategory) {
        const card = document.createElement('div');
        card.classList.add('reservation-card', 'mb-3');
        const collapseId = `collapseReservation${reservation.id}_${Math.random().toString(36).substr(2, 9)}`;
        const isNew = checkIfNew(reservation.submission_timestamp);
        const newLabelHtml = isNew ? `<span class="new-label">NIEUW!</span>` : '';
        
        const displayDate = reservation.reservation_date ? new Date(reservation.reservation_date).toLocaleDateString('nl-NL', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N.V.T.';
        const displayTime = reservation.reservation_time || 'N.V.T.';
        const displaySubmissionTimestamp = reservation.submission_timestamp ? new Date(reservation.submission_timestamp).toLocaleString('nl-NL') : 'N.V.T.';

        card.innerHTML = `
            <div class="reservation-card-header d-flex justify-content-between align-items-center"
                 data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}" style="cursor: pointer;">
                <h5 class="mb-0 card-title-text">Reservering van #${reservation.id} - ${reservation.full_name} ${newLabelHtml}</h5>
                <span class="badge bg-secondary me-2">${reservation.status}</span>
            </div>
            <div id="${collapseId}" class="collapse">
                <div class="reservation-card-body">
                    <div class="reservation-card-section mb-3">
                        <h4 class="section-title">Opmerkingen</h4> 
                        <p><span class="comment-text section-value-text">${reservation.remarks || 'Geen opmerkingen'}</span></p>
                    </div>
                    <hr class="line-distinction">

                    <div class="reservation-card-section mb-3">
                        <h4 class="section-title">Contactgegevens</h4> 
                        <p><span class="section-label-text">Email:</span> <a href="mailto:${reservation.email}" class="text-light-link section-value-text">${reservation.email}</a></p>
                        <p><span class="section-label-text">Telefoon:</span> <a href="tel:${reservation.phone}" class="text-light-link section-value-text">${reservation.phone}</a></p>
                    </div>
                    <hr class="line-distinction">

                    <div class="reservation-card-section mb-3">
                        <h4 class="section-title">Reserveringsdetails</h4> 
                        <p><span class="section-label-text">Datum:</span> <span class="section-value-text">${displayDate}</span></p>
                        <p><span class="section-label-text">Tijd:</span> <span class="section-value-text">${displayTime}</span></p>
                    </div>
                    <hr class="line-distinction">
                    
                    <div class="reservation-card-section mb-3">
                        <h4 class="section-title">Pakket</h4>
                        <p><span class="section-label-text">Type:</span> <span class="section-value-text">${reservation.package_type}</span></p>
                    </div>
                    <hr class="line-distinction">

                    <div class="reservation-card-section">
                        <h4 class="section-title">Acties</h4>
                        <div class="reservation-actions mt-2">
                            ${getActionButtonsHtml(reservation, frontendCategory)}
                        </div>
                    </div>
                </div>
            </div>
            <div class="reservation-card-footer">
                <p class="footer-text mb-0"><span class="section-label-text">Aanvraag ontvangen op:</span> <span class="section-value-text">${displaySubmissionTimestamp}</span></p>
            </div>`;
        return card;
    }
    // --- EINDE GEWIJZIGDE createReservationCard functie ---

    function getActionButtonsHtml(reservation, frontendCategory) {
        let buttonsHtml = '';
        if (frontendCategory === 'Pending') {
            buttonsHtml += `<button type="button" class="btn btn-success btn-sm me-2 mb-1" onclick="updateReservationStatus('${reservation.id}', 'Accepted', 'Wilt u deze reservering accepteren?')">Accepteren</button>`;
            buttonsHtml += `<button type="button" class="btn btn-danger btn-sm me-2 mb-1" onclick="updateReservationStatus('${reservation.id}', 'Declined', 'Wilt u deze reservering weigeren?')">Weigeren</button>`;
        } else if (frontendCategory === 'Accepted') {
            buttonsHtml += `<button type="button" class="btn btn-secondary btn-sm me-2 mb-1" onclick="updateReservationStatus('${reservation.id}', 'Pending', 'Reservering terugzetten naar Pending?')">Naar Pending</button>`;
        } else if (frontendCategory === 'Declined') {
            buttonsHtml += `<button type="button" class="btn btn-secondary btn-sm me-2 mb-1" onclick="updateReservationStatus('${reservation.id}', 'Pending', 'Reservering terugzetten naar Pending?')">Naar Pending</button>`;
        } else if (frontendCategory === 'Verlopen') {
            buttonsHtml += `<button type="button" class="btn btn-info btn-sm mb-1" onclick="updateReservationStatus('${reservation.id}', 'Pending', 'Wilt u deze verlopen reservering herstellen naar Pending?')">Herstellen naar Pending</button>`;
        }
        return buttonsHtml;
    }

    function checkIfNew(createdAt) {
        if (!createdAt) return false;
        const creationTime = new Date(createdAt).getTime();
        const currentTime = new Date().getTime();
        const oneHour = 60 * 60 * 1000;
        return (currentTime - creationTime) < oneHour;
    }

    window.updateReservationStatus = function(reservationId, newFrontendStatus, confirmMessage) {
        showMessageModal('Status Wijzigen', confirmMessage || `Weet u zeker dat u de status van reservering #${reservationId} wilt wijzigen naar "${newFrontendStatus}"?`, true, () => {
            fetch('../api/updateReservation.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', },
                body: `id=${reservationId}&status=${newFrontendStatus}`
            })
            .then(response => {
                if (!response.ok) { return response.text().then(text => { throw new Error(`Serverfout (${response.status}): ${text}`); }); }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessageModal('Succes', data.message || `Reservering #${reservationId} succesvol bijgewerkt.`);
                    fetchAndDisplayReservations();
                } else {
                    showMessageModal('Fout', `Fout bijwerken status: ${data.message || 'Onbekende serverfout.'}`);
                }
            })
            .catch(error => {
                console.error('Update Error:', error);
                showMessageModal('Fout', `Kon status niet bijwerken: ${error.message}`);
            });
        });
    };

    if (tabEl) {
        fetchAndDisplayReservations(); 
        tabEl.addEventListener('shown.bs.tab', event => {
            fetchAndDisplayReservations();
        });
    } else {
        console.error("Tab element #pills-tab not found. Cannot attach tab listeners.");
        fetchAndDisplayReservations();
    }
});