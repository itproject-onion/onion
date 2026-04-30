// hilfsvariable für 'körperteile' (oberkörper) mit mehreren schichten 
//window weil die variable in mehreren js dateien vewendet wird, also global dann
    window.current_active_layer = null;

/** umschalten von Layer Übersicht in die Karusell ansicht des ausgewälten layers... */
    document.querySelectorAll('.layer-square').forEach(square => {
        square.addEventListener('click', () => {
            const layer = square.getAttribute('data-layer');
            window.current_active_layer = layer;

            
            const capsule = square.closest('.outfit-capsule');
            capsule.querySelector('.layer-selection-grid').classList.add('d-none');
            capsule.querySelector('.layer-carousel-view').classList.remove('d-none');

            window.refresh_carousel_view(layer);
        });
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
            window.current_active_layer = null;
        });
    });
