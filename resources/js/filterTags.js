const tagCheckboxes = document.querySelectorAll('#tags input[type="checkbox"]');
const items = document.querySelectorAll('.item');

tagCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        let checkedTags = 
            Array.from(tagCheckboxes) // Convert checkboxes to an array to use filter and map.
            .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
            .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
        displayItemsWithTags(checkedTags);
  })
});

function displayItemsWithTags(checkedTags) {
    items.forEach(item => {
        const itemTags = item.dataset.tags ? JSON.parse(item.dataset.tags) : [];
        
        if (checkedTags.length === 0) {
            item.classList.remove('d-none');
            return;
        }

        const hasMatchingTags = itemTags.some(t => checkedTags.includes(t))
        if(hasMatchingTags) {
            item.classList.remove('d-none');
        } else {
            item.classList.add('d-none');
        }
    });
}