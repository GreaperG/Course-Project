console.log('app.js loaded');
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';
import 'bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    document.getElementById('select-all-inventorys-index').addEventListener('change', function() {
        document.querySelectorAll('input[name="inventorys_ids[]"]').forEach(cb => cb.checked = this.checked);
    });

    // Edit button
    document.getElementById('editSelectedBtn').addEventListener('click', function() {
        const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
        if (selected) {
            const id = selected.value;
            window.location.href = '/inventory/' + id + '/edit';
        } else {
            alert('Select an inventory first!');
        }
    });

    // Add item button
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
        if (selected) {
            const inventoryId = parseInt(selected.value);
            window.location.href = `/inventory/${inventoryId}/createItem`;
        }
    });

    // Show inventory button
    document.getElementById('showInventoryBtn').addEventListener('click', function() {
        const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');
        if (selected) {
            const inventoryId = selected.value;
            window.location.href = `/inventory/${inventoryId}`;
        } else {
            alert('Please select an inventory first!');
        }
    });

    // Enable/disable buttons
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



