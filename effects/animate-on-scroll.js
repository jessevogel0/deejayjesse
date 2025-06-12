// animate-on-scroll.js
document.addEventListener('DOMContentLoaded', () => {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');

    if (!animatedElements.length) {
        return; // Geen elementen om te animeren
    }

    // --- Configuratie voor vertragingen per effect ---
    // De waarde hier bepaalt hoeveel later het VOLGENDE element in een batch
    // zal starten, nadat DIT element is begonnen met animeren.
    const effectStaggerConfig = {
        defaultStagger: 350, // Standaard interval tot het volgende element (in ms)
        effectSpecificDelays: {
            'fade-in': 150,
            'slide-up': 150,
            'slide-in-left': 220,
            'slide-in-right': 220,
            'zoom-in': 150,
            'rotate-in-left': 220,
            'rotate-in-right': 220,
            'flip-in-x': 250,
            'flip-in-y': 250
            // Voeg hier meer custom effect classes en hun gewenste stagger delays toe
        }
    };
    // --- Einde Configuratie ---

    const animationOptions = {
        root: null, // Observeert t.o.v. de viewport
        rootMargin: '-20% 0px -20% 0px', // Start animatie als element in de middelste 60% van het scherm komt
        threshold: 0.1 // Trigger als 10% van het element zichtbaar is binnen de aangepaste zone
    };

    // Helper functie om de stagger-interval voor een element te bepalen
    const getElementStaggerIncrement = (element) => {
        for (const effectClass in effectStaggerConfig.effectSpecificDelays) {
            if (element.classList.contains(effectClass)) {
                return effectStaggerConfig.effectSpecificDelays[effectClass];
            }
        }
        return effectStaggerConfig.defaultStagger; // Fallback naar standaard interval
    };

    const handleIntersection = (entries, observer) => {
        // Filter entries om alleen de elementen te krijgen die nu intersecten (zichtbaar worden)
        const intersectingEntries = entries.filter(entry => entry.isIntersecting);

        // Sorteer de zichtbaar wordende elementen op hun verticale positie op de pagina.
        // Dit helpt om een logische volgorde te garanderen, zelfs als de observer ze anders rapporteert.
        intersectingEntries.sort((a, b) => {
            return a.target.getBoundingClientRect().top - b.target.getBoundingClientRect().top;
        });

        let currentBatchDelayOffset = 0;
        let lastParentNode = null; // Houd de parent van het vorige element bij

        intersectingEntries.forEach((entry) => {
            const currentParentNode = entry.target.parentNode;

            // Reset de vertraging als de parent van het huidige element verschilt van de vorige,
            // en het niet het allereerste element in deze batch is.
            // Dit behandelt elementen in verschillende "secties" (met verschillende directe ouders)
            // alsof ze een nieuwe stagger-sequentie starten.
            if (lastParentNode !== null && currentParentNode !== lastParentNode) {
                currentBatchDelayOffset = 0; // Reset de vertraging voor een nieuwe "sectie"
            }

            const delayIncrementForNext = getElementStaggerIncrement(entry.target);

            // Plan de animatie voor het HUIDIGE element met de HUIDIGE offset
            setTimeout(() => {
                entry.target.classList.add('is-visible');
            }, currentBatchDelayOffset);

            // Verhoog de offset voor het VOLGENDE element in deze (sub)batch
            currentBatchDelayOffset += delayIncrementForNext;

            // Update de laatst geziene parent
            lastParentNode = currentParentNode;

            // Stop met het observeren van dit element nadat de animatie is ingepland.
            observer.unobserve(entry.target);
        });
    };

    const observer = new IntersectionObserver(handleIntersection, animationOptions);

    animatedElements.forEach(el => {
        observer.observe(el);
    });
});
