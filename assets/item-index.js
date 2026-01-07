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

    const selectAllItems = document.getElementById('select-all-items-index');
    if (selectAllItems) {
        selectAllItems.addEventListener('change', function() {
            document.querySelectorAll('input[name="items_ids[]"]').forEach(cb => cb.checked = this.checked);
        });
    }


    const deleteBtn = document.getElementById('deleteSelectedBtn');
    if (deleteBtn) {
        const inventoryId = deleteBtn.dataset.inventoryId;
        const csrfToken = deleteBtn.dataset.csrfToken;

        document.querySelectorAll('input[name="items_ids[]"]').forEach(cb => {
            cb.addEventListener('change', function() {
                const hasSelection = document.querySelectorAll('input[name="items_ids[]"]:checked').length > 0;
                deleteBtn.disabled = !hasSelection;
            });
        });

        deleteBtn.addEventListener('click', function() {
            const selected = document.querySelector('input[name="items_ids[]"]:checked');
            if (selected && confirm('Delete selected item?')) {
                const itemId = selected.value;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/inventory/${inventoryId}/item/${itemId}/delete`;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = csrfToken;

                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});

