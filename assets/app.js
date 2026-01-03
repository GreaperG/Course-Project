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

document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });


document
    .querySelectorAll('ul.attributes li')
    .forEach((attributes) => {
       addAttributeFormDeleteLink(attributes)
    });

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addAttributeFormDeleteLink(item);
};


function addAttributeFormDeleteLink(item) {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Delete this attribute';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();

        item.remove();
    });
}

document.getElementById('deleteSelectedBtn').addEventListener('click', function() {
    const selected = document.querySelector('input[name="inventorys_ids[]"]:checked');

    if (selected && confirm('Delete selected inventory?')) {
        const id = selected.value;
        const csrfToken = selected.dataset.csrf; // ← Берем из data-csrf чекбокса!

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/inventory/${id}`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = csrfToken;

        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }
});

// Включаем кнопки при выборе
document.querySelectorAll('input[name="inventorys_ids[]"]').forEach(cb => {
    cb.addEventListener('change', function() {
        const hasSelection = document.querySelectorAll('input[name="inventorys_ids[]"]:checked').length > 0;
        document.getElementById('editSelectedBtn').disabled = !hasSelection;
        document.getElementById('deleteSelectedBtn').disabled = !hasSelection;
    });
});
