document.addEventListener('DOMContentLoaded', function () {
    Object.values(wardrobe_inventory).forEach(category => {
        category.forEach(item => {
                item.hide = false;
            }
        )
    });

    /** das objekt wardrobe_inventory muss dynamisch mit php befüllt werden
     * also das ist der bereich wo die bidler aus der db reingefetzt werden müssen -> basierend
     * auf der wetter logik...
     */
    /*const wardrobe_inventory = {
        head: [
            { id: 1, img: '/images/konfig/head1_cosi.png' },
            { id: 2, img: '/images/konfig/head2_cosi.png' },
            { id: 3, img: '/images/konfig/head3_cosi.png' },
        ],
        upper_shirt: [
            { id: 4, img: '/images/konfig/shirt1_cosi.png' },
            { id: 5, img: '/images/konfig/shirt2_cosi.png' },
            { id: 6, img: '/images/konfig/shirt3_cosi.png' },
            { id: 19, img: '/images/konfig/shirt4_cosi.png' },
        ],
        upper_pulli: [
            { id: 7, img: '/images/konfig/pulli1_cosi.png' },
            { id: 8, img: '/images/konfig/pulli2_cosi.png' },
            { id: 9, img: '/images/konfig/pulli3_cosi.png' },
        ],
        upper_jacke: [
            { id: 10, img: '/images/konfig/jacke1_cosi.png' },
            { id: 11, img: '/images/konfig/jacke2_cosi.png' },
            { id: 12, img: '/images/konfig/jacke3_cosi.png' },
        ],
        lower: [
            { id: 13, img: '/images/konfig/lower1_cosi.png' },
            { id: 14, img: '/images/konfig/lower2_cosi.png' },
            { id: 15, img: '/images/konfig/lower3_cosi.png' },
        ],
        feet: [
            { id: 16, img: '/images/konfig/feet1_cosi.png' },
            { id: 17, img: '/images/konfig/feet2_cosi.png' },
            { id: 18, img: '/images/konfig/feet3_cosi.png' }
        ]
        //.. usw für alle anderen kathegorien - 
        // ich hab mal beispielhaft ein paar sachen von mir genommen :)
    };*/

    //specihert den aktuellen index (also das item das gerade in der mitte sichbar ist) 
    // pro kaathegorie - das wir uns das beim anzeihen dann rauszeiehn können
    const active_selection_indices = {
        head: 0,
        upper_shirt: 0, upper_pulli: 0, upper_jacke: 0,
        lower: 0, feet: 0
    };

    // hilfsvariable für 'körperteile' (oberkörper) mit mehreren schichten
    let current_active_layer = null;

    /** Kernfunktion: AKtualisieren der Anzeige der Bilder + hidden inputs im karussel
     * @param {string} category_name  die ID des körperteils (head , upper_shirt...)
     */
    function refresh_carousel_view(category_name) {
        const items = wardrobe_inventory[category_name];
        const current = active_selection_indices[category_name];

        //berechnen des vorherigen/nächsten items das es endlos im kreis geht
        const len = items.length;
        const prev = findNextVisibleIndex(category_name, (current - 1 + len) % len, -1);
        const next = findNextVisibleIndex(category_name, (current + 1) % len, +1);

        let capsule;

        //spezialfall: logik für "körperteile" bei denen gelayert wird
        if (category_name.startsWith('upper_')) {
            capsule = document.querySelector('[data-category="upper"]');

            //aktualisieren vom bild in der ansicht in der man alle layer nebeneinander sieht
            const square = document.querySelector(`.layer-square[data-layer="${category_name}"]`);
            if (square) {
                const img_preview = square.querySelector('.square-image');
                const plus = square.querySelector('.plus-icon');
                if(current != -1) {
                    img_preview.src = items[current].img;
                    img_preview.classList.remove('d-none');
                } else {
                    img_preview.classList.add('d-none');
                }
                if (plus) plus.classList.add('d-none');
            }
        } else {
            //standard fall (kopf, hose, schohe)
            capsule = document.querySelector(`[data-category="${category_name}"]`);
        }

        if (capsule) {
            //wenn wir im karusell modus sind die bilder austauschen durch das karusell
            if (!category_name.startsWith('upper_') || current_active_layer === category_name) {
                if(current == -1) { //Keine Items zum anzeigen
                    capsule.querySelector('.item-next').classList.add('d-none');
                    capsule.querySelector('.item-main').classList.add('d-none');
                    capsule.querySelector('.item-prev').classList.add('d-none');
                } else {
                    capsule.querySelector('.item-main').src = items[current].img;
                    capsule.querySelector('.item-main').classList.remove('d-none');

                    if(prev == -1) {
                        capsule.querySelector('.item-next').src = items[next].img; 
                        capsule.querySelector('.item-next').classList.remove('d-none');
                        
                        capsule.querySelector('.item-prev').classList.add('d-none');
                    } else if(next == -1) {
                        capsule.querySelector('.item-prev').src = items[prev].img;
                        capsule.querySelector('.item-prev').classList.remove('d-none');
                        
                        capsule.querySelector('.item-next').classList.add('d-none');
                    } else if (prev == -1 && next == -1) {
                        capsule.querySelector('.item-next').classList.add('d-none');
                        capsule.querySelector('.item-prev').classList.add('d-none');
                    } else {
                        capsule.querySelector('.item-next').src = items[next].img;
                        capsule.querySelector('.item-next').classList.remove('d-none');
                        capsule.querySelector('.item-prev').src = items[prev].img;
                        capsule.querySelector('.item-prev').classList.remove('d-none');
                    }
                    
                    /** backend hinweis: hier  landen die ids vom kleidungsstück im hidden inpu feld
                     * beim absenden des formulars (mit anziehen) werden diese übergeben :)
                     */
                    const input = document.getElementById(`input-${category_name}`);
                    if (input) input.value = items[current].id;
                }
            }
        }
    }

    //initalisierung aller kleidungsstücke beim laden der seite
    Object.keys(wardrobe_inventory).forEach(cat => {
        findNextVisibleIndex(cat, active_selection_indices[cat], +1);
        refresh_carousel_view(cat);
    });

    /** umschalen von Layer Übersicht in die Karusell ansicht des ausgewälten layers... */
    document.querySelectorAll('.layer-square').forEach(square => {
        square.addEventListener('click', () => {
            const layer = square.getAttribute('data-layer');
            current_active_layer = layer;

            
            const capsule = square.closest('.outfit-capsule');
            capsule.querySelector('.layer-selection-grid').classList.add('d-none');
            capsule.querySelector('.layer-carousel-view').classList.remove('d-none');

            refresh_carousel_view(layer);
        });
    });

    /**  pfeil navigation - schaltet sich durchs 'wardrobe_inventory' array 
     * 
    */
    document.querySelectorAll('.outfit-capsule').forEach(capsule => {
        const base_cat = capsule.getAttribute('data-category');

        capsule.querySelector('.prev').addEventListener('click', () => {
            const cat = (base_cat === 'upper') ? current_active_layer : base_cat;
            if (!cat) return;
            
            const currentIndex = active_selection_indices[cat];
            const len = wardrobe_inventory[cat].length;
            active_selection_indices[cat] = findNextVisibleIndex(cat, (currentIndex - 1 + len) % len, -1);

            refresh_carousel_view(cat);
        });

        capsule.querySelector('.next').addEventListener('click', () => {
            const cat = (base_cat === 'upper') ? current_active_layer : base_cat;
            if (!cat) return;
            
            const currentIndex = active_selection_indices[cat];
            const len = wardrobe_inventory[cat].length;
            active_selection_indices[cat] = findNextVisibleIndex(cat, (currentIndex + 1) % len, +1);

            refresh_carousel_view(cat);
        });
    });

    
    /* bedienung der karuselle auch mit maus/swipe
    ich habs noch nicht geschafft die gescwindigkeit gescheit zu drosseln für die maus...*/
    document.querySelectorAll('.outfit-capsule').forEach(capsule => {
        const base_cat = capsule.getAttribute('data-category');

        // mausrad 
        capsule.addEventListener('wheel', (e) => {
            e.preventDefault();
            const cat = (base_cat === 'upper') ? current_active_layer : base_cat;
            if (!cat) return;

            if (e.deltaY > 0 || e.deltaX > 0) {
                const currentIndex = active_selection_indices[cat];
                const len = wardrobe_inventory[cat].length;
                active_selection_indices[cat] = findNextVisibleIndex(cat, (currentIndex + 1) % len, +1);
            } else {
                const currentIndex = active_selection_indices[cat];
                const len = wardrobe_inventory[cat].length;
                active_selection_indices[cat] = findNextVisibleIndex(cat, (currentIndex - 1 + len) % len, -1);
            }
            refresh_carousel_view(cat);
        }, { passive: false });

        // touch/swipe
        let touchStartX = 0;
        capsule.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        capsule.addEventListener('touchend', (e) => {
            let touchEndX = e.changedTouches[0].screenX;
            const cat = (base_cat === 'upper') ? current_active_layer : base_cat;
            if (!cat) return;

            if (touchStartX - touchEndX > 50) { //links
                const currentIndex = active_selection_indices[cat];
                const len = wardrobe_inventory[cat].length;
                active_selection_indices[cat] = findNextVisibleIndex(cat, (currentIndex + 1) % len, +1);
            } else if (touchEndX - touchStartX > 50) { //rechts
                const currentIndex = active_selection_indices[cat];
                const len = wardrobe_inventory[cat].length;
                active_selection_indices[cat] = findNextVisibleIndex(cat, (currentIndex - 1 + len) % len, -1);
            }
            refresh_carousel_view(cat);
        }, { passive: true });
    });

    /** auswahl bestätigen
     * wenn man das karusell eines gelayerten items das auswählt 
     * wird das karusell geschlossen und man kommt zurück zu übersicht
     */
    document.querySelectorAll('.select-trigger-overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
            const capsule = overlay.closest('.outfit-capsule');
            capsule.querySelector('.layer-carousel-view').classList.add('d-none');
            capsule.querySelector('.layer-selection-grid').classList.remove('d-none');
            current_active_layer = null;
        });
    });










    //Clicken von Filter-Tags. Muss aktuell hier sein da variablen von oben nur in dem scope existieren
    const tagCheckboxes = document.querySelectorAll('#tags input[type="checkbox"]');
    tagCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let checkedTags = 
                Array.from(tagCheckboxes) // Convert checkboxes to an array to use filter and map.
                .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
                .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
            filterItems(checkedTags);

            Object.keys(wardrobe_inventory).forEach(category => {
                const currentIndex = active_selection_indices[category];
                const newIndex = findNextVisibleIndex(category, currentIndex, +1);
                active_selection_indices[category] = newIndex;

                refresh_carousel_view(category);
            });
        })
    });

    function filterItems(checkedTags) {
        Object.values(wardrobe_inventory).forEach(category => {
            category.forEach(item => {
                const itemTags = item.tags || [];
                if (checkedTags.length == 0) {
                    item.hide = false;
                    return;
                }

                const hasMatchingTags = itemTags.some(t => checkedTags.includes(t))
                if(hasMatchingTags) {
                    item.hide = false;
                } else {
                    item.hide = true;
                }
            })
        });
    }

    function findNextVisibleIndex(cat, startIndex, direction) { //Direction +1/-1 für richtung des carousels
        const items = wardrobe_inventory[cat];
        const len = items.length;

        let index = (startIndex == -1) ? 0 : startIndex;

        for (let i = 0; i < len; i++) {
            if (!items[index].hide) {
                return index;
            }

            index = (index + direction + len) % len; // funktioniert für +1 und -1
        }

        return -1; // alle hidden
    }
});