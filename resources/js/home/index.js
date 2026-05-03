//speichert den aktuellen index (also das item das gerade in der mitte sichbar ist) 
    // pro kategorie - das wir uns das beim anzeihen dann rauszeiehn können
    window.active_selection_indices = {
        head: 0,
        upper_shirt: 0, upper_pulli: 0, upper_jacke: 0,
        lower: 0, feet: 0
    };


document.addEventListener('DOMContentLoaded', function () {
    Object.values(window.wardrobe_inventory).forEach(category => {
        category.forEach(item => {
                item.hide = false;
            }
        )
    });

    //initalisierung aller kleidungsstücke beim laden der seite
    Object.keys(window.wardrobe_inventory).forEach(cat => {
        window.findNextVisibleIndex(cat, window.active_selection_indices[cat], +1);
        window.refresh_carousel_view(cat);
    });
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
        //.. usw für alle anderen kategorien - 
        // ich hab mal beispielhaft ein paar sachen von mir genommen :)
    };*/
