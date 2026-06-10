window.findNextVisibleIndex = function(cat, startIndex, direction) { //Direction +1/-1 für richtung des carousels
        const items = window.wardrobe_inventory[cat];
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