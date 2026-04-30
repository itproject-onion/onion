  function filterItems(checkedTags) {
        Object.values(window.wardrobe_inventory).forEach(category => {
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

    //Clicken von Filter-Tags. Muss aktuell hier sein da variablen von oben nur in dem scope existieren
    const tagCheckboxes = document.querySelectorAll('#tags input[type="checkbox"]');
    tagCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let checkedTags = 
                Array.from(tagCheckboxes) // Convert checkboxes to an array to use filter and map.
                .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
                .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
            filterItems(checkedTags);

            Object.keys(window.wardrobe_inventory).forEach(category => {
                const currentIndex = window.active_selection_indices[category];
                const newIndex = window.findNextVisibleIndex(category, currentIndex, +1);
                window.active_selection_indices[category] = newIndex;

                window.refresh_carousel_view(category);
            });
        })
    });