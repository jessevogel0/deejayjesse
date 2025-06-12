document.addEventListener('DOMContentLoaded', function () {
    const tabLabels = document.querySelectorAll('.faq-tabs-nav .tab-label');
    const tabContents = document.querySelectorAll('.faq-tab-content');
    const faqInitialMessage = document.getElementById('faq-initial-message');
    let currentlyOpenAccordionItem = null; 

    // --- Tab Functionaliteit ---
    tabLabels.forEach(label => {
        label.addEventListener('click', function() {
            tabLabels.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => {
                c.classList.remove('active');
                // Sluit ook alle accordeons in de niet-actieve tabs
                c.querySelectorAll('.faq-qna-item.open').forEach(openItem => {
                    openItem.classList.remove('open');
                    const answerPanel = openItem.querySelector('.faq-answer-panel');
                    const icon = openItem.querySelector('.faq-question-toggle .faq-icon i');
                    
                    answerPanel.style.maxHeight = null;
                    answerPanel.style.paddingTop = '0px'; // Reset padding direct
                    answerPanel.style.paddingBottom = '0px'; // Reset padding direct
                    
                    if (icon) {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });

            this.classList.add('active');
            const targetContentId = this.dataset.tabTarget;
            const targetContentElement = document.getElementById(targetContentId);

            if (faqInitialMessage) { 
                faqInitialMessage.style.display = 'none';
            }

            if (targetContentElement) {
                targetContentElement.classList.add('active');
                initializeAccordionsInTopic(targetContentElement);
            }
            currentlyOpenAccordionItem = null; 
        });
    });

    // Standaard eerste tab actief
    if (tabLabels.length > 0) {
        const firstTabLabel = tabLabels[0];
        // Controleer of de eerste tab niet de AI tab is, tenzij het de enige tab is.
        let initialTabIndex = 0;
        if (tabLabels[0].dataset.tabTarget === 'content-tab-ai' && tabLabels.length > 1) {
            initialTabIndex = 1; // Neem de volgende tab als AI de eerste is en er meer zijn
        }
        
        const initialActiveLabel = tabLabels[initialTabIndex] || tabLabels[0]; // Fallback naar de allereerste
        initialActiveLabel.classList.add('active');
        const firstTabContentId = initialActiveLabel.dataset.tabTarget;
        const firstTabContentElement = document.getElementById(firstTabContentId);

        if (firstTabContentElement) {
            if (faqInitialMessage && firstTabContentId !== 'content-tab-ai') faqInitialMessage.style.display = 'none';
            else if (faqInitialMessage && firstTabContentId === 'content-tab-ai' && tabLabels.length ===1) faqInitialMessage.style.display = 'none';
            else if (faqInitialMessage) faqInitialMessage.style.display = 'flex';


            firstTabContentElement.classList.add('active');
            initializeAccordionsInTopic(firstTabContentElement);
        } else if (faqInitialMessage) { 
             faqInitialMessage.style.display = 'flex'; 
        }
    } else if (faqInitialMessage) {
        faqInitialMessage.style.display = 'flex';
    }


    // --- Accordeon Functionaliteit ---
    function initializeAccordionsInTopic(topicElement) {
        const accordionToggles = topicElement.querySelectorAll('.faq-question-toggle');

        accordionToggles.forEach(toggle => {
            const newToggle = toggle.cloneNode(true);
            toggle.parentNode.replaceChild(newToggle, toggle);

            newToggle.addEventListener('click', function() {
                const parentItem = this.closest('.faq-qna-item');
                const answerPanel = parentItem.querySelector('.faq-answer-panel');
                // De HTML structuur heeft geen .faq-answer-inner-content meer,
                // dus we werken direct met het answerPanel voor scrollHeight.
                const iconElement = this.querySelector('.faq-icon i');
                const isOpening = !parentItem.classList.contains('open');

                // Sluit het eventueel reeds geopende accordeon item op de pagina
                if (currentlyOpenAccordionItem && currentlyOpenAccordionItem !== parentItem) {
                    currentlyOpenAccordionItem.classList.remove('open');
                    const prevAnswerPanel = currentlyOpenAccordionItem.querySelector('.faq-answer-panel');
                    const prevIcon = currentlyOpenAccordionItem.querySelector('.faq-question-toggle .faq-icon i');
                    
                    prevAnswerPanel.style.maxHeight = null; // Triggert CSS transitie naar 0
                    prevAnswerPanel.style.paddingTop = '0px'; 
                    prevAnswerPanel.style.paddingBottom = '0px';
                    if (prevIcon) {
                        prevIcon.classList.remove('fa-chevron-up');
                        prevIcon.classList.add('fa-chevron-down');
                    }
                }

                // Toggle het huidige item
                if (isOpening) {
                    parentItem.classList.add('open');
                    // Stel padding in VOORDAT scrollHeight wordt gelezen
                    answerPanel.style.paddingTop = '15px';
                    answerPanel.style.paddingBottom = '20px';
                    
                    // Gebruik requestAnimationFrame om de browser de layout te laten herberekenen
                    requestAnimationFrame(() => {
                        answerPanel.style.maxHeight = answerPanel.scrollHeight + 'px';
                    });

                    if (iconElement) {
                        iconElement.classList.remove('fa-chevron-down');
                        iconElement.classList.add('fa-chevron-up');
                    }
                    currentlyOpenAccordionItem = parentItem;
                } else { 
                    parentItem.classList.remove('open');
                    answerPanel.style.maxHeight = null; 
                    answerPanel.style.paddingTop = '0px'; 
                    answerPanel.style.paddingBottom = '0px';
                    if (iconElement) {
                        iconElement.classList.remove('fa-chevron-up');
                        iconElement.classList.add('fa-chevron-down');
                    }
                    currentlyOpenAccordionItem = null;
                }
            });
        });
    }

    // --- AI Assistent Logica ---
    const aiQuestionTextarea = document.getElementById('aiQuestionTextarea');
    const askAiButton = document.getElementById('askAiButton');
    const aiAnswerArea = document.getElementById('aiAnswerArea');
    const aiButtonSpinner = askAiButton ? askAiButton.querySelector('.spinner-border-sm') : null;
    const aiButtonIcon = askAiButton ? askAiButton.querySelector('i') : null;

    if (askAiButton && aiQuestionTextarea && aiAnswerArea) {
        askAiButton.addEventListener('click', async function() {
            const userQuestion = aiQuestionTextarea.value.trim();
            if (!userQuestion) {
                aiAnswerArea.textContent = 'Stel eerst een vraag.';
                aiAnswerArea.classList.remove('loading');
                return;
            }

            aiAnswerArea.textContent = 'Een ogenblik, de AI DJ denkt na...';
            aiAnswerArea.classList.add('loading');
            askAiButton.disabled = true;
            if (aiButtonSpinner) aiButtonSpinner.classList.remove('d-none');
            if (aiButtonIcon) aiButtonIcon.classList.add('d-none');

            const prompt = `Je bent de AI assistent van DJ Jesse, een professionele, enthousiaste en klantvriendelijke DJ uit Kessel, Limburg. 
Een websitebezoeker stelt de volgende vraag: "${userQuestion}"

Geef een behulpzaam en vriendelijk antwoord in de stijl van DJ Jesse. 
Als de vraag gaat over boekingen, specifieke prijzen, beschikbaarheid, of zeer persoonlijke zaken, verwijs dan vriendelijk door naar het contactformulier op de website of de directe contactgegevens van DJ Jesse. 
Als de vraag algemeen is over muziek, feesten, of DJ-gerelateerde onderwerpen, geef dan een informatief en leuk antwoord.
Houd het antwoord relatief beknopt en positief. Gebruik "ik" alsof je DJ Jesse's assistent bent of namens hem spreekt.`;

            try {
                let chatHistory = [{ role: "user", parts: [{ text: prompt }] }];
                const payload = { contents: chatHistory };
                const apiKey = ""; 
                const apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' + apiKey;
                
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('API Error Data:', errorData);
                    throw new Error(`API fout: ${response.status} ${response.statusText}`);
                }

                const result = await response.json();
                
                let aiResponseText = "Sorry, ik kon op dit moment geen antwoord genereren. Probeer het later opnieuw.";
                if (result.candidates && result.candidates.length > 0 &&
                    result.candidates[0].content && result.candidates[0].content.parts &&
                    result.candidates[0].content.parts.length > 0) {
                    aiResponseText = result.candidates[0].content.parts[0].text;
                } else {
                     console.warn("Unexpected API response structure:", result);
                }
                aiAnswerArea.textContent = aiResponseText;

            } catch (error) {
                console.error('Fout bij het aanroepen van de Gemini API:', error);
                aiAnswerArea.textContent = 'Oeps, er ging iets mis bij het verbinden met de AI Assistent. Probeer het later nog eens of neem direct contact op met DJ Jesse.';
            } finally {
                aiAnswerArea.classList.remove('loading');
                askAiButton.disabled = false;
                if (aiButtonSpinner) aiButtonSpinner.classList.add('d-none');
                if (aiButtonIcon) aiButtonIcon.classList.remove('d-none');
            }
        });
    }

    // Animate-on-scroll basis
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    if (animatedElements.length > 0 && typeof IntersectionObserver !== 'undefined') {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); 
                }
            });
        }, { threshold: 0.1 }); 
        animatedElements.forEach(el => observer.observe(el));
    }
});
