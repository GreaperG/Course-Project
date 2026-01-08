
document.addEventListener('DOMContentLoaded', function() {


    const selectAllInventories = document.getElementById('select-all-inventorys-search');
    if (selectAllInventories) {
        selectAllInventories.addEventListener('change', function() {
            document.querySelectorAll('input[name="inventorys_ids[]"]').forEach(cb => cb.checked = this.checked);
        });
    }


    const selectAllItems = document.getElementById('select-all-items-search');
    if (selectAllItems) {
        selectAllItems.addEventListener('change', function() {
            document.querySelectorAll('input[name="items_ids[]"]').forEach(cb => cb.checked = this.checked);
        });
    }


    const viewInventoryBtn = document.getElementById('viewInventorySelectedBtn');
    if (viewInventoryBtn) {
        viewInventoryBtn.addEventListener('click', function() {
            const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
            if (selected) {
                window.location.href = '/inventory/' + selected.value;
            } else {
                alert('Select an inventory first!');
            }
        });


        document.querySelectorAll('input[name="inventorys_ids[]"]').forEach(cb => {
            cb.addEventListener('change', function() {
                const hasSelection = document.querySelectorAll('input[name="inventorys_ids[]"]:checked').length > 0;
                viewInventoryBtn.disabled = !hasSelection;
            });
        });
    }


    const viewItemBtn = document.getElementById('viewItemSelectedBtn');
    if (viewItemBtn) {
        viewItemBtn.addEventListener('click', function() {
            const selected = document.querySelector('input[name="items_ids[]"]:checked');
            if (selected) {
                const inventoryId = selected.dataset.inventoryId;
                window.location.href = '/inventory/' + inventoryId + '/items';
            } else {
                alert('Select an item first!');
            }
        });


        document.querySelectorAll('input[name="items_ids[]"]').forEach(cb => {
            cb.addEventListener('change', function() {
                const hasSelection = document.querySelectorAll('input[name="items_ids[]"]:checked').length > 0;
                viewItemBtn.disabled = !hasSelection;
            });
        });
    }
});
