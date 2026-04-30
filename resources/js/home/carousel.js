/** Kernfunktion: AKtualisieren der Anzeige der Bilder + hidden inputs im karussel
     * @param {string} category_name  die ID des körperteils (head , upper_shirt...)
     */

window.refresh_carousel_view = function(category_name) {
        const items = window.wardrobe_inventory[category_name];
        const current = window.active_selection_indices[category_name];

        //berechnen des vorherigen/nächsten items das es endlos im kreis geht
        const len = items.length;
        const prev = window.findNextVisibleIndex(category_name, (current - 1 + len) % len, -1);
        const next = window.findNextVisibleIndex(category_name, (current + 1) % len, +1);

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
            if (!category_name.startsWith('upper_') || window.current_active_layer === category_name) {
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

function getActiveCategory(base_cat) {
    return base_cat === 'upper' ? window.current_active_layer : base_cat;
}

function moveCarousel(cat, direction) {
    if (!cat) return;
    const currentIndex = window.active_selection_indices[cat];
    const len = window.wardrobe_inventory[cat].length;
    window.active_selection_indices[cat] = window.findNextVisibleIndex(cat, (currentIndex + direction + len) % len, direction);
    window.refresh_carousel_view(cat);
}

    document.querySelectorAll('.outfit-capsule').forEach(capsule => {
        const base_cat = capsule.getAttribute('data-category');

        function getCat(){
            return getActiveCategory(base_cat);
        }

        let lastWheelTime = 0;

        capsule.querySelector('.prev').addEventListener('click', () => {
            moveCarousel(getCat(), -1);
        });

        capsule.querySelector('.next').addEventListener('click', () => {
            moveCarousel(getCat(), +1);
        });

        // mausrad 
        capsule.addEventListener('wheel', (e) => {
        e.preventDefault();

        const now = Date.now();

        if (now - lastWheelTime < 300) return;
        lastWheelTime = now;

        const direction = (e.deltaY > 0 || e.deltaX > 0) ? +1 : -1;

        moveCarousel(getCat(), direction);

        }, { passive: false });

         // touch/swipe
        let touchStartX = 0;
        capsule.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        capsule.addEventListener('touchend', (e) => {
            let touchEndX = e.changedTouches[0].screenX;
            
            if (touchStartX - touchEndX > 50) { //links
                moveCarousel(getCat(), +1);
            } else if (touchEndX - touchStartX > 50) { //rechts
                moveCarousel(getCat(), -1);
            }

        }, { passive: true });
    });