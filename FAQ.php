<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veelgestelde Vragen - DJ Jesse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="effects/animations.css">
    <link rel="stylesheet" href="css/faq.css"> 
</head>
<body class="faq-page-active">
    <div class="back-link-container">
        <a href="javascript:history.back()" class="back-link">
            <i class="fas fa-arrow-left"></i> Terug
        </a>
    </div>

    <div class="faq-page-container">
        <header class="faq-page-header animate-on-scroll fade-in">
            <h1>Veelgestelde Vragen</h1>
            <p class="lead">Kies een onderwerp en klik op een vraag om het antwoord te lezen.</p>
        </header>

        <?php
$faq_data_by_topic = [
    "Algemeen" => [
        "icon" => "fas fa-info-circle",
        "questions" => [
            ["question" => "Wat voor muziek draait DJ Jesse?", "answer" => "<p>Mijn repertoire is breed en altijd afgestemd op de sfeer: van de nieuwste hits tot tijdloze classics, house, R&B, en meer. Uw wensen staan centraal!</p>"],
            ["question" => "Hoe lang van tevoren moet ik boeken?", "answer" => "<p>Populaire data zijn snel vol. Ik raad aan zo vroeg mogelijk contact op te nemen, maar informeer ook gerust voor last-minute mogelijkheden.</p>"]
        ]
    ],
    "Boekingen & Prijzen" => [
        "icon" => "fas fa-calendar-alt",
        "questions" => [
            ["question" => "Hoe lang is een optie of offerte geldig?", "answer" => "<p>Een optie of offerte voor een reservering is doorgaans 14 dagen geldig. Binnen deze periode kun je de boeking definitief maken om de datum vast te leggen.</p>"],
            ["question" => "Wat gebeurt er als ik mijn boeking niet op tijd bevestig?", "answer" => "<p>Als een boeking niet binnen de gestelde termijn definitief wordt bevestigd, kan de gereserveerde datum weer beschikbaar komen voor andere aanvragen.</p>"],
            ["question" => "Worden er reiskosten in rekening gebracht?", "answer" => "<p>Voor optredens buiten de regio Wijchen/Nijmegen kunnen reiskosten van toepassing zijn. Dit wordt altijd vooraf gecommuniceerd in de offerte.</p>"],
            ["question" => "Wanneer is mijn reservering definitief?", "answer" => "<p>Een reservering wordt definitief zodra deze schriftelijk of mondeling is bevestigd, na akkoord op de offerte.</p>"],
            ["question" => "Voor welke evenementen ben je beschikbaar?", "answer" => "<p>Van bruiloften en verjaardagen tot bedrijfsfeesten en jubilea. Elk feest is uniek, dus neem contact op voor de mogelijkheden!</p>"],
            ["question" => "Wat zijn de kosten?", "answer" => "<p>Prijzen zijn afhankelijk van uw wensen (duur, locatie, apparatuur). Vraag een vrijblijvende offerte aan voor een prijs op maat.</p>"]
        ]
    ],
    "Annulering" => [ // Nieuwe categorie toegevoegd
        "icon" => "fas fa-times-circle",
        "questions" => [
            ["question" => "Hoe kan ik een boeking annuleren?", "answer" => "<p>Annulering dient schriftelijk te gebeuren. Neem hiervoor contact met ons op via e-mail of bericht.</p>"],
            ["question" => "Zijn er annuleringskosten?", "answer" => "<p>Annuleren is kosteloos tot 30 dagen voor de evenementdatum. Daarna gelden er annuleringskosten die oplopen naarmate de evenementdatum dichterbij komt.</p>"],
            ["question" => "Zijn er situaties waarin ik kosteloos kan annuleren, ook op korte termijn?", "answer" => "<p>Ja, in uitzonderlijke, onvoorziene omstandigheden zoals overlijden van naasten of ingrijpende overheidsmaatregelen, kan in overleg een volledige terugbetaling van de annuleringskosten plaatsvinden.</p>"]
        ]
    ],
    "Techniek & Show" => [
        "icon" => "fas fa-sliders-h",
        "questions" => [
            ["question" => "Verzorg je ook licht en geluid?", "answer" => "<p>Zeker! Ik kan een complete drive-in show leveren met professioneel licht en geluid, perfect afgestemd op uw evenement en locatie.</p>"],
            ["question" => "Wat heb je nodig op locatie?", "answer" => "<p>Een stabiele tafel, voldoende stroom (230V), en bij buitenoptredens een overdekte, droge plek. Details bespreken we altijd vooraf.</p>"],
            ["question" => "Wat zijn de vereisten voor parkeren op locatie?", "answer" => "<p>Goede laad- en losgelegenheid en/of parkeergelegenheid dichtbij de locatie is wenselijk voor een efficiënte op- en afbouw.</p>"],
            ["question" => "Hoeveel tijd is er nodig voor de op- en afbouw?", "answer" => "<p>De benodigde tijd voor op- en afbouw varieert per pakket en locatie, maar wordt altijd ruim van tevoren ingepland om een soepele start en einde van het evenement te garanderen.</p>"],
            ["question" => "Welke stroomvoorziening is nodig?", "answer" => "<p>Een adequate stroomvoorziening is essentieel. Specifieke details hierover worden vooraf besproken op basis van de gekozen apparatuur en locatie.</p>"],
            ["question" => "Zijn er speciale overwegingen voor buitenoptredens?", "answer" => "<p>Bij buitenoptredens is een overdekte en beschutte DJ-plek vereist ter bescherming tegen weersinvloeden.</p>"],
            ["question" => "Welke technische specificaties gelden voor het 'Alleen DJ' pakket?", "answer" => "<p>Voor het 'Alleen DJ' pakket zijn er specifieke vereisten voor de aanwezige DJ-apparatuur en geluidsinstallatie. Deze worden nauwkeurig met u afgestemd om de compatibiliteit te waarborgen.</p>"]
        ]
    ],
    "Beleid & Overeenkomst" => [ // Nieuwe categorie toegevoegd
        "icon" => "fas fa-file-contract",
        "questions" => [
            ["question" => "Worden er foto's of video's gemaakt tijdens het evenement en hoe worden deze gebruikt?", "answer" => "<p>Tijdens evenementen kunnen er opnames gemaakt worden die voor promotiedoeleinden gebruikt kunnen worden. Indien u hier bezwaar tegen heeft, kunt u dit vooraf kenbaar maken.</p>"],
            ["question" => "Hoe zit het met de aansprakelijkheid van DJ Jesse?", "answer" => "<p>DJ Jesse beschikt over een basisverzekering voor apparatuur. Onze aansprakelijkheid is beperkt tot het factuurbedrag van de boeking en geldt niet voor schade of diefstal veroorzaakt door derden of gasten, tenzij direct veroorzaakt door grove nalatigheid van DJ Jesse.</p>"],
            ["question" => "Dien ik belangrijke locatie-informatie door te geven?", "answer" => "<p>Ja, het is belangrijk dat de opdrachtgever of locatie eventuele restricties, zoals geluidslimieten of andere locatie-eisen, vooraf aan DJ Jesse doorgeeft.</p>"],
            ["question" => "Kunnen de algemene voorwaarden aangepast worden?", "answer" => "<p>DJ Jesse behoudt zich het recht voor om de algemene voorwaarden aan te passen. Eventuele wijzigingen worden altijd vooraf op een transparante wijze gecommuniceerd.</p>"],
            ["question" => "Welk recht is van toepassing op de overeenkomst?", "answer" => "<p>Op alle overeenkomsten en diensten is het Nederlands recht van toepassing.</p>"]
        ]
    ]
];
?>

        <nav class="faq-tabs-nav animate-on-scroll fade-in" style="transition-delay: 0.1s;">
            <?php 
            $is_first_tab = true;
            foreach ($faq_data_by_topic as $topic_name => $topic_data): 
                $tab_id_nav = "topic-nav-" . strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 ]/', '', $topic_name)));
                $content_id_nav = "content-topic-" . strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 ]/', '', $topic_name)));
            ?>
                <button type="button" class="tab-label <?php echo $is_first_tab ? 'active' : ''; ?>" data-tab-target="<?php echo $content_id_nav; ?>" id="<?php echo $tab_id_nav; ?>">
                    <i class="<?php echo htmlspecialchars($topic_data['icon']); ?>"></i>
                    <span><?php echo htmlspecialchars($topic_name); ?></span>
                </button>
            <?php 
                $is_first_tab = false;
            endforeach; 
            ?>
             <button type="button" class="tab-label" data-tab-target="content-tab-ai" id="topic-nav-ai"><i class="fas fa-robot"></i> Vraag het de AI Assistent ✨</button>
        </nav>

        <div class="faq-tabs-content-wrapper">
            <?php
            $is_first_content = true;
            foreach ($faq_data_by_topic as $topic_name => $topic_data):
                $content_id = "content-topic-" . strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 ]/', '', $topic_name)));
            ?>
                <div id="<?php echo $content_id; ?>" class="faq-tab-content <?php echo $is_first_content ? 'active' : ''; ?>">
                    <h2 class="animate-on-scroll fade-in"><?php echo htmlspecialchars($topic_name); ?></h2>
                    <div class="faq-accordion-list">
                        <?php 
                        $item_delay = 0.1; 
                        foreach ($topic_data['questions'] as $faq): 
                            $item_inline_style = 'animation-delay: ' . $item_delay . 's; transition-delay: ' . $item_delay . 's;';
                        ?>
                            <div class="faq-qna-item animate-on-scroll fade-in" style="<?php echo $item_inline_style; ?>">
                                <button type="button" class="faq-question-toggle">
                                    <span><?php echo htmlspecialchars($faq["question"]); ?></span>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </button>
                                <div class="faq-answer-panel">
                                    <?php echo $faq["answer"]; ?>
                                </div>
                            </div>
                        <?php 
                            $item_delay += 0.05;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php 
                $is_first_content = false;
            endforeach; 
            ?>

            <div id="content-tab-ai" class="faq-tab-content">
                <h2 class="animate-on-scroll fade-in">Vraag het de AI Assistent ✨</h2>
                <p class="animate-on-scroll fade-in" style="transition-delay:0.1s;">Heb je een specifieke vraag die hier niet tussen staat? Stel hem aan onze slimme AI Assistent!</p>
                <div class="ai-input-group animate-on-scroll fade-in" style="transition-delay:0.2s;">
                    <label for="aiQuestionTextarea" class="form-label visually-hidden">Jouw vraag:</label>
                    <textarea id="aiQuestionTextarea" class="form-control" rows="3" placeholder="Typ hier je vraag..."></textarea>
                </div>
                <button type="button" id="askAiButton" class="btn ai-submit-btn animate-on-scroll fade-in" style="transition-delay:0.3s;">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i class="fas fa-paper-plane"></i> Verstuur Vraag
                </button>
                <div id="aiAnswerArea" class="mt-3 animate-on-scroll fade-in" style="transition-delay:0.4s;">
                    <em>Het antwoord van de AI Assistent verschijnt hier...</em>
                </div>
            </div>
             <div id="faq-initial-message" class="animate-on-scroll fade-in" style="display: <?php echo (empty($faq_data_by_topic)) ? 'flex' : 'none'; ?>;">
                <i class="fas fa-hand-pointer"></i>
                <p>Kies hierboven een onderwerp om de vragen te zien.</p>
            </div>
        </div>
    </div>

    <?php 
        // include_once("footer.php"); 
    ?>

    <script src="js/faq.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
