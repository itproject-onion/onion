/*const tagCheckboxes = document.querySelectorAll('#tags input[type="checkbox"]');

tagCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        let checkedTags = 
            Array.from(tagCheckboxes) // Convert checkboxes to an array to use filter and map.
            .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
            .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
        filterItems(checkedTags);
    })
});

function filterItems(checkedTags) {
    wardrobe_inventory.forEach(category => {
        category.forEach(item => {
            const itemTags = item.tags || [];
            if(itemTags) {
                if (checkedTags.length === 0) {
                    item.hide = false;
                    return;
                }

                const hasMatchingTags = itemTags.some(t => checkedTags.includes(t))
                if(hasMatchingTags) {
                    item.hide = false;
                } else {
                    item.hide = true;
                }
            }
        })
    });
}

function findNextVisibleIndex(category_name, startIndex, direction = 1) { //Direction +1/-1 für richtung des carousels
    const items = wardrobe_inventory[category_name];
    const len = items.length;

    let index = startIndex;

    for (let i = 0; i < len; i++) {
        if (!items[index].hide) {
            return index;
        }

        index = (index + direction + len) % len; // funktioniert für +1 und -1
    }

    return -1; // alle hidden
}*/