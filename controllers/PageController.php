<?php
// NavigationController.php

class PageController {

    // Methode om de naam van de huidige pagina te verkrijgen
    public static function getCurrentPage() {
        return basename($_SERVER['PHP_SELF']);
    }

    // Methode om de class voor de actieve pagina te bepalen
    public static function getActiveClass($page) {
        // Vergelijk de pagina's en controleer de huidige pagina
        return (self::getCurrentPage() == $page . '.php') ? 'active' : '';
    }

    public static function formatPageName($pageName) {
        return ucwords(str_replace('-', ' ', $pageName));
    }
}

