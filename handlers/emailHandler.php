<?php
// handlers/emailHandler.php

// Basisconfiguratie voor e-mails
define('ADMIN_EMAIL', 'jouw_admin_email@example.com'); // VERVANG DIT!
define('FROM_EMAIL', 'noreply@jouwdomein.com'); // VERVANG DIT!
define('SITE_NAME', 'DJ Jesse Website'); // Pas eventueel aan
define('SITE_URL', 'http://jouwdomein.com'); // VERVANG DIT! Optioneel, voor links in e-mail
define('LOGO_URL', 'http://jouwdomein.com/pad/naar/logo.png'); // VERVANG DIT! Optioneel, voor logo in e-mail

/**
 * Genereert de HTML-header voor de e-mails.
 * @param string $title De titel van de e-mail.
 * @return string HTML string voor de e-mail header.
 */
function getEmailHeader($title) {
    $header = '<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f4f4f4; }
        .container { width: 100%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background-color: #007bff; color: #ffffff; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 20px; }
        .content p { margin-bottom: 15px; }
        .content strong { color: #007bff; }
        .button { display: inline-block; background-color: #28a745; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 0.9em; color: #6c757d; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; }
        .details-table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 8px; border: 1px solid #dddddd; text-align: left; }
        .details-table th { background-color: #f2f2f2; }
        /* Specifieke inline stijlen worden per element toegepast waar nodig */
    </style>
</head>
<body>
    <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table role="presentation" class="container" width="600" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td class="header" style="background-color: #007bff; color: #ffffff; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            <h1 style="margin: 0; font-size: 24px;">' . htmlspecialchars($title) . '</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content" style="padding: 20px;">';
    // Optioneel logo:
    // if (defined('LOGO_URL') && !empty(LOGO_URL)) {
    //     $header .= '<div style="text-align: center; margin-bottom: 20px;"><img src="' . LOGO_URL . '" alt="' . SITE_NAME . ' Logo" style="max-width: 150px; height: auto;"></div>';
    // }
    return $header;
}

/**
 * Genereert de HTML-footer voor de e-mails.
 * @return string HTML string voor de e-mail footer.
 */
function getEmailFooter() {
    $footer = '       </td>
                    </tr>
                    <tr>
                        <td class="footer" style="background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 0.9em; color: #6c757d; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            <p style="margin:0;">&copy; ' . date('Y') . ' ' . SITE_NAME . '. Alle rechten voorbehouden.</p>';
    if (defined('SITE_URL') && !empty(SITE_URL)) {
        $footer .= '<p style="margin:5px 0 0 0;"><a href="' . SITE_URL . '" style="color: #007bff; text-decoration: none;">Bezoek onze website</a></p>';
    }
    $footer .= '    </td>
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
 * Verstuurt een bevestigingsmail naar de klant na een nieuwe reservering (HTML).
 */
function sendReservationConfirmationEmail($reservationDetails, $reservationId) {
    if (!isset($reservationDetails['email']) || !filter_var($reservationDetails['email'], FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid or missing email for reservation confirmation: " . print_r($reservationDetails, true));
        return false;
    }

    $to = $reservationDetails['email'];
    $emailTitle = 'Bevestiging reserveringsaanvraag #' . $reservationId;
    $subject = $emailTitle . ' bij ' . SITE_NAME;

    $message = getEmailHeader($emailTitle);

    $message .= '<p style="margin-bottom: 15px;">Beste ' . htmlspecialchars($reservationDetails['name']) . ',</p>';
    $message .= '<p style="margin-bottom: 15px;">Bedankt voor uw reserveringsaanvraag bij ' . SITE_NAME . '. Uw aanvraag met ID <strong>#' . $reservationId . '</strong> is succesvol ontvangen en wordt zo spoedig mogelijk in behandeling genomen.</p>';
    
    $message .= '<h3 style="color: #007bff; margin-top: 20px; margin-bottom: 10px;">Details van uw aanvraag:</h3>';
    $message .= '<table role="presentation" class="details-table" width="100%" border="0" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 15px; border-collapse: collapse;">';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Naam:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['name']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">E-mail:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['email']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Telefoon:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['phone']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Datum:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['date']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Type evenement:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['event_type']) . '</td></tr>';
    if (!empty($reservationDetails['comments'])) {
        $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Opmerkingen:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . nl2br(htmlspecialchars($reservationDetails['comments'])) . '</td></tr>';
    }
    $message .= '</table>';

    $message .= '<p style="margin-top: 20px; margin-bottom: 15px;">U hoort spoedig van ons over de status van uw aanvraag.</p>';
    $message .= '<p style="margin-bottom: 15px;">Met vriendelijke groet,<br>' . SITE_NAME . '</p>';

    $message .= getEmailFooter();

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . FROM_EMAIL . '>' . "\r\n" .
                'Reply-To: ' . FROM_EMAIL . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send HTML reservation confirmation email to " . $to);
        return false;
    }
}

/**
 * Verstuurt een notificatiemail naar de admin over een nieuwe reservering (HTML).
 */
function sendAdminNotificationEmail($reservationDetails, $reservationId) {
    $to = ADMIN_EMAIL;
    $emailTitle = 'Nieuwe reserveringsaanvraag #' . $reservationId;
    $subject = $emailTitle . ' ontvangen via ' . SITE_NAME;

    $message = getEmailHeader($emailTitle);

    $message .= '<p style="margin-bottom: 15px;">Er is een nieuwe reserveringsaanvraag binnengekomen:</p>';
    
    $message .= '<table role="presentation" class="details-table" width="100%" border="0" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 15px; border-collapse: collapse;">';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Reservering ID:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">#' . $reservationId . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Naam:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['name']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">E-mail:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['email']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Telefoon:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['phone']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Datum:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['date']) . '</td></tr>';
    $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Type evenement:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . htmlspecialchars($reservationDetails['event_type']) . '</td></tr>';
    if (!empty($reservationDetails['comments'])) {
        $message .= '<tr><th style="padding: 8px; border: 1px solid #dddddd; text-align: left; background-color: #f2f2f2;">Opmerkingen:</th><td style="padding: 8px; border: 1px solid #dddddd; text-align: left;">' . nl2br(htmlspecialchars($reservationDetails['comments'])) . '</td></tr>';
    }
    $message .= '</table>';
    
    // Link naar admin paneel (vervang 'admin/login.php' of 'admin/dashboard.php' met de juiste URL)
    // $adminPanelUrl = SITE_URL . '/admin/admin.php'; // Pas dit pad aan!
    // $message .= '<p style="margin-top:20px; margin-bottom: 15px;"><a href="' . $adminPanelUrl . '" class="button" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Bekijk in Adminpaneel</a></p>';
    $message .= '<p style="margin-top:20px; margin-bottom: 15px;">Gelieve deze aanvraag te controleren in het adminpaneel.</p>';

    $message .= getEmailFooter();

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . FROM_EMAIL . '>' . "\r\n" .
                'Reply-To: ' . htmlspecialchars($reservationDetails['name']) . ' <' . htmlspecialchars($reservationDetails['email']) . '>' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send HTML admin notification email for reservation #" . $reservationId);
        return false;
    }
}

/**
 * Verstuurt een e-mail naar de klant over een statusupdate van de reservering (HTML).
 */
function sendReservationStatusUpdateEmail($customerDetails, $reservationId, $newStatus, $adminNotes = '') {
    if (!isset($customerDetails['email']) || !filter_var($customerDetails['email'], FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid or missing email for status update: " . print_r($customerDetails, true));
        return false;
    }

    $to = $customerDetails['email'];
    $emailTitle = 'Update reserveringsaanvraag #' . $reservationId;
    $subject = $emailTitle . ' bij ' . SITE_NAME;
    $statusText = '';
    $statusColor = '#333333'; // Default text color

    switch ($newStatus) {
        case 'Accepted':
            $statusText = 'GEACCEPTEERD';
            $statusColor = '#28a745'; // Groen
            break;
        case 'Declined':
            $statusText = 'GEWEIGERD';
            $statusColor = '#dc3545'; // Rood
            break;
        default:
            $statusText = htmlspecialchars(strtoupper($newStatus));
            break;
    }

    $message = getEmailHeader($emailTitle);

    $message .= '<p style="margin-bottom: 15px;">Beste ' . htmlspecialchars($customerDetails['name']) . ',</p>';
    $message .= '<p style="margin-bottom: 15px;">Er is een update betreffende uw reserveringsaanvraag <strong>#' . $reservationId . '</strong> bij ' . SITE_NAME . '.</p>';
    $message .= '<p style="margin-bottom: 15px;">Uw aanvraag is: <strong style="color: ' . $statusColor . ';">' . $statusText . '</strong>.</p>';

    if ($newStatus === 'Accepted') {
        $message .= '<p style="margin-bottom: 15px;">We kijken ernaar uit u te mogen verwelkomen! Verdere details over uw boeking zullen volgen of zijn reeds gecommuniceerd.</p>';
        // Voeg hier eventueel specifieke instructies of informatie toe voor geaccepteerde reserveringen.
    } elseif ($newStatus === 'Declined') {
        $message .= '<p style="margin-bottom: 15px;">Helaas kunnen we op dit moment niet aan uw aanvraag voldoen.</p>';
        // Voeg hier eventueel een reden of alternatieve opties toe.
    }

    if (!empty($adminNotes)) {
        $message .= '<h4 style="color: #007bff; margin-top: 20px; margin-bottom: 5px;">Opmerking van de beheerder:</h4>';
        $message .= '<p style="margin-bottom: 15px; padding: 10px; background-color: #e9ecef; border-radius: 4px;">' . nl2br(htmlspecialchars($adminNotes)) . '</p>';
    }

    $message .= '<p style="margin-bottom: 15px;">Met vriendelijke groet,<br>' . SITE_NAME . '</p>';

    $message .= getEmailFooter();

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . FROM_EMAIL . '>' . "\r\n" .
                'Reply-To: ' . FROM_EMAIL . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send HTML status update email to " . $to . " for reservation #" . $reservationId);
        return false;
    }
}

?>
