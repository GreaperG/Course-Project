console.log('inventory-index.js loaded');
document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('select-all-inventorys-index').addEventListener('change', function() {
        document.querySelectorAll('input[name="inventorys_ids[]"]').forEach(cb => cb.checked = this.checked);
    });


    document.getElementById('editSelectedBtn').addEventListener('click', function() {
        const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
        if (selected) {
            const id = selected.value;
            window.location.href = '/inventory/' + id + '/edit';
        } else {
            alert('Select an inventory first!');
        }
    });


    document.getElementById('addItemBtn').addEventListener('click', function() {
        const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
        if (selected) {
            const inventoryId = parseInt(selected.value);
            window.location.href = `/inventory/${inventoryId}/createItem`;
        }
    });


    document.getElementById('showInventoryBtn').addEventListener('click', function() {
        const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
        if (selected) {
            const inventoryId = selected.value;
            window.location.href = `/inventory/${inventoryId}`;
        } else {
            alert('Please select an inventory first!');
        }
    });


    document.querySelectorAll('input[name="inventorys_ids[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const hasSelection = document.querySelectorAll('input[name="inventorys_ids[]"]:checked').length > 0;
            document.getElementById('editSelectedBtn').disabled = !hasSelection;
            document.getElementById('addItemBtn').disabled = !hasSelection;
            document.getElementById('deleteSelectedBtn').disabled = !hasSelection;
            document.getElementById('showInventoryBtn').disabled = !hasSelection;
        });
    });
});



