document.addEventListener('DOMContentLoaded', function () {

    /** das objekt wardrobe_inventory muss dynamisch mit php befüllt werden
     * also das ist der bereich wo die bidler aus der db reingefetzt werden müssen -> basierend
     * auf der wetter logik...
     */
    const wardrobe_inventory = {
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
    };

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
        const len = items.length;

        //verechnen des vorherigen/nächsten items das es endlos im kreis geht
        const prev = (current - 1 + len) % len;
        const next = (current + 1) % len;

        let capsule;

        //spezialfall: logik für "körperteile" bei denen gelayert wird
        if (category_name.startsWith('upper_')) {
            capsule = document.querySelector('[data-category="upper"]');

            //aktualisieren vom bild in der ansicht in der man alle layer nebeneinander sieht
            const square = document.querySelector(`.layer-square[data-layer="${category_name}"]`);
            if (square) {
                const img_preview = square.querySelector('.square-image');
                const plus = square.querySelector('.plus-icon');
                img_preview.src = items[current].img;
                img_preview.classList.remove('d-none');
                if (plus) plus.classList.add('d-none');
            }
        } else {
            //standard fall (kopf, hose, schohe)
            capsule = document.querySelector(`[data-category="${category_name}"]`);
        }

        if (capsule) {
            //wenn wir im karusell modus sind die bilder austauschen durch das karusell
            if (!category_name.startsWith('upper_') || current_active_layer === category_name) {
                capsule.querySelector('.item-prev').src = items[prev].img;
                capsule.querySelector('.item-main').src = items[current].img;
                capsule.querySelector('.item-next').src = items[next].img;
            }

            /** backend hinweis: hier  landen die ids vom kleidungsstück im hidden inpu feld
             * beim absenden des formulars (mit anziehen) werden diese übergeben :)
             */
            const input = document.getElementById(`input-${category_name}`);
            if (input) input.value = items[current].id;
        }
    }

    //initalisierung aller kleidungsstücke beim laden der seite
    Object.keys(wardrobe_inventory).forEach(cat => refresh_carousel_view(cat));

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
            active_selection_indices[cat] = (active_selection_indices[cat] - 1 + wardrobe_inventory[cat].length) % wardrobe_inventory[cat].length;
            refresh_carousel_view(cat);
        });

        capsule.querySelector('.next').addEventListener('click', () => {
            const cat = (base_cat === 'upper') ? current_active_layer : base_cat;
            if (!cat) return;
            active_selection_indices[cat] = (active_selection_indices[cat] + 1) % wardrobe_inventory[cat].length;
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
                active_selection_indices[cat] = (active_selection_indices[cat] + 1) % wardrobe_inventory[cat].length;
            } else {
                active_selection_indices[cat] = (active_selection_indices[cat] - 1 + wardrobe_inventory[cat].length) % wardrobe_inventory[cat].length;
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
                active_selection_indices[cat] = (active_selection_indices[cat] + 1) % wardrobe_inventory[cat].length;
                refresh_carousel_view(cat);
            } else if (touchEndX - touchStartX > 50) { //rechts
                active_selection_indices[cat] = (active_selection_indices[cat] - 1 + wardrobe_inventory[cat].length) % wardrobe_inventory[cat].length;
                refresh_carousel_view(cat);
            }
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
});