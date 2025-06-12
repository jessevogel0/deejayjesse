<?php
// handlers/emailHandler.php

// --- BASISCONFIGURATIE ---
// Pas de onderstaande waarden aan met je eigen informatie.
define('ADMIN_EMAIL', 'info@deejayjesse.nl');     // VERVANG DIT! Admin e-mailadres.
define('FROM_EMAIL', 'info@deejayjesse.nl');            // VERVANG DIT! Het "Van" adres.
define('SITE_NAME', 'DJ Jesse - Dé All-Round DJ');         // Pas eventueel aan.
define('SITE_URL', 'https://deejayjesse.nl');              // VERVANG DIT! Website URL.
define('LOGO_URL_FOOTER', 'https://jouwdomein.com/link-naar-klein-logo.png'); // VERVANG DIT! URL naar een klein logo voor de footer.

/**
 * Genereert de HTML-header voor de e-mails met het nieuwe donkere kaart-design.
 * @param string $title De titel van de e-mail.
 * @return string HTML string voor de e-mail header.
 */
function getEmailHeader($title) {
    $current_time = date('d-m-Y H:i');
    return '<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        body { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Arial, sans-serif; background-color: #222222; color: #ffffff;}
        a { color: #cccccc; text-decoration: underline; transition: color 0.2s ease-in-out; }
        a:hover { color: #ffffff; }
        .data-value { color: #cccccc; }
        .section-header { padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #cccccc40; font-size: 18px; color: #ffffff; }
        .details-table td { padding: 5px 0; }
        /* Let op: CSS transitions worden niet door alle e-mailclients ondersteund. */
    </style>
</head>
<body style="margin: 0; padding: 20px 0; background-color: #222222;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #1e1e1e; border-radius: 8px;">
                    <tr>
                        <td style="background-color: #111111; padding: 20px 25px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            <h1 style="font-size: 24px; margin: 0; color: #ffffff;">' . htmlspecialchars($title) . '</h1>
                            <p style="font-size: 12px; margin: 5px 0 0 0; color: #cccccc;">Aangemaakt op: ' . $current_time . '</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 25px;">';
}

/**
 * Genereert de HTML-footer voor de e-mails.
 * @return string HTML string voor de e-mail footer.
 */
function getEmailFooter() {
    $footer = '</td>
                    </tr>
                    <tr>
                        <td style="background-color: #111111; padding: 20px; text-align: center; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">';
    if (defined('LOGO_URL_FOOTER') && !empty(LOGO_URL_FOOTER)) {
        $footer .= '<img src="' . LOGO_URL_FOOTER . '" alt="' . SITE_NAME . ' Logo" style="max-height: 40px; width: auto; margin-bottom: 15px;">';
    }
    $footer .= '<p style="margin: 0; font-size: 12px; color: #cccccc;">&copy; ' . date('Y') . ' ' . SITE_NAME . '. Alle rechten voorbehouden.<br>';
    if (defined('SITE_URL') && !empty(SITE_URL)) {
        $footer .= '<a href="' . SITE_URL . '" style="color: #cccccc; text-decoration: underline;">Bezoek onze website</a>';
    }
    $footer .= '</p></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    return $footer;
}

/**
 * Genereert de HTML-tabel met reserveringsdetails, gemodelleerd naar de afbeelding.
 * @param array $details De reserveringsgegevens.
 * @return string De HTML-string voor de details.
 */
function generateReservationDetailsHtml($details) {
    $html = '';

    // Contactgegevens
    $html .= '<h3 class="section-header" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #444444; font-size: 18px; color: #ffffff;">Contactgegevens</h3>';
    $html .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="details-table" style="width:100%; margin-bottom: 20px;">';
    $html .= '<tr><td style="padding: 5px 0;" width="100">Email:</td><td style="padding: 5px 0;"><a href="mailto:' . htmlspecialchars($details['email']) . '" class="data-value">' . htmlspecialchars($details['email']) . '</a></td></tr>';
    $html .= '<tr><td style="padding: 5px 0;" width="100">Telefoon:</td><td style="padding: 5px 0;"><a href="tel:' . htmlspecialchars($details['phone']) . '" class="data-value">' . htmlspecialchars($details['phone']) . '</a></td></tr>';
    $html .= '</table>';

    // Reserveringsdetails
    $html .= '<h3 class="section-header" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #444444; font-size: 18px; color: #ffffff;">Reserveringsdetails</h3>';
    $html .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="details-table" style="width:100%; margin-bottom: 20px;">';
    $html .= '<tr><td style="padding: 5px 0;" width="100">Datum:</td><td class="data-value" style="padding: 5px 0;">' . htmlspecialchars($details['date']) . '</td></tr>';
    $html .= '<tr><td style="padding: 5px 0;" width="100">Tijd:</td><td class="data-value" style="padding: 5px 0;">' . htmlspecialchars($details['time'] ?? 'N.v.t.') . '</td></tr>';
    $html .= '</table>';

    // Pakket / Evenement type
    $html .= '<h3 class="section-header" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #444444; font-size: 18px; color: #ffffff;">Pakket</h3>';
    $html .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="details-table" style="width:100%; margin-bottom: 20px;">';
    $html .= '<tr><td style="padding: 5px 0;" width="100">Type:</td><td class="data-value" style="padding: 5px 0;">';

    // Translate event type for display
    $eventTypeDisplay = htmlspecialchars($details['event_type']);
    switch ($details['event_type']) {
        case 'Standard':
            $eventTypeDisplay = 'Standaard';
            break;
        case 'All-Inclusive':
            $eventTypeDisplay = 'All-Inclusive';
            break;
        case 'DJ Only':
            $eventTypeDisplay = 'Alleen DJ';
            break;
        // Add more cases here if you have other event types
    }
    $html .= $eventTypeDisplay . '</td></tr>';
    $html .= '</table>';

    // Opmerkingen
    $html .= '<h3 class="section-header" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #444444; font-size: 18px; color: #ffffff;">Opmerkingen</h3>';
    $comment = !empty($details['comments']) ? nl2br(htmlspecialchars($details['comments'])) : 'Geen opmerkingen';
    $html .= '<p class="data-value" style="margin-top:0;">' . $comment . '</p>';

    return $html;
}

/**
 * Verstuurt een bevestigingsmail naar de klant.
 */
function sendReservationConfirmationEmail($reservationDetails, $reservationId) {
    if (!isset($reservationDetails['email'])) return false;

    $to = $reservationDetails['email'];
    $emailTitle = 'Aanvraag #' . $reservationId . ' ontvangen';
    $subject = $emailTitle . ' | ' . SITE_NAME;
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . FROM_EMAIL . ">\r\n";

    $message = getEmailHeader($emailTitle);
    $message .= '<p style="font-size: 16px; margin-top: 0;">Beste <span class="data-value">' . htmlspecialchars($reservationDetails['name']) . '</span>,</p>';
    $message .= '<p>Bedankt voor uw aanvraag. We hebben deze succesvol ontvangen en nemen zo snel mogelijk contact met u op. Hieronder vindt u een overzicht van de ingevulde gegevens.</p>';
    $message .= generateReservationDetailsHtml($reservationDetails);
    $message .= '<p>Met vriendelijke groet,<br>' . SITE_NAME . '</p>';
    $message .= getEmailFooter();

    return mail($to, $subject, $message, $headers);
}

/**
 * Verstuurt een notificatiemail naar de admin.
 */
function sendAdminNotificationEmail($reservationDetails, $reservationId) {
    $to = ADMIN_EMAIL;
    $emailTitle = 'Nieuwe Aanvraag #' . $reservationId;
    $subject = $emailTitle . ' van ' . htmlspecialchars($reservationDetails['name']);
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . FROM_EMAIL . ">\r\n";
    $headers .= 'Reply-To: ' . htmlspecialchars($reservationDetails['name']) . ' <' . htmlspecialchars($reservationDetails['email']) . ">\r\n";

    $message = getEmailHeader($emailTitle);
    $message .= '<p style="font-size: 16px; margin-top: 0;">Er is een nieuwe aanvraag binnengekomen van <span class="data-value">' . htmlspecialchars($reservationDetails['name']) . '</span>.</p>';
    $message .= generateReservationDetailsHtml($reservationDetails);
    // Voeg hier eventueel een directe link naar het admin paneel toe
    // $adminUrl = SITE_URL . '/admin/reservation.php?id=' . $reservationId;
    // $message .= '<p><a href="' . $adminUrl . '" style="...">Bekijk in Admin Paneel</a></p>';
    $message .= getEmailFooter();

    return mail($to, $subject, $message, $headers);
}

/**
 * Verstuurt een statusupdate naar de klant.
 */
function sendReservationStatusUpdateEmail($customerDetails, $reservationId, $newStatus, $adminNotes = '') {
    if (!isset($customerDetails['email'])) return false;

    $to = $customerDetails['email'];
    $emailTitle = 'Update voor aanvraag #' . $reservationId;
    $subject = $emailTitle . ' | ' . SITE_NAME;
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . FROM_EMAIL . ">\r\n";

    $statusText = 'Onbekend';
    $statusColor = '#ffffff'; // wit
    switch ($newStatus) {
        case 'Accepted':
            $statusText = 'GEACCEPTEERD';
            $statusColor = '#28a745'; // groen
            break;
        case 'Declined':
            $statusText = 'GEWEIGERD';
            $statusColor = '#dc3545'; // rood
            break;
    }

    $message = getEmailHeader($emailTitle);
    $message .= '<p style="font-size: 16px; margin-top: 0;">Beste <span class="data-value">' . htmlspecialchars($customerDetails['name']) . '</span>,</p>';
    $message .= '<p>De status van uw aanvraag <strong>#' . $reservationId . '</strong> is bijgewerkt naar: <strong style="color: ' . $statusColor . ';">' . $statusText . '</strong>.</p>';

    if (!empty($adminNotes)) {
        $message .= '<h3 class="section-header" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #444444; font-size: 18px; color: #ffffff;">Opmerking</h3>';
        $message .= '<p style="margin-top:0;">' . nl2br(htmlspecialchars($adminNotes)) . '</p>';
    }

    $message .= '<p>Met vriendelijke groet,<br>' . SITE_NAME . '</p>';
    $message .= getEmailFooter();

    return mail($to, $subject, $message, $headers);
}
?>