/* Interacciones de la interfaz */
(() => {
    'use strict';

    const body = document.body;
    document.querySelectorAll('[data-sidebar-open]').forEach((button) => {
        button.addEventListener('click', () => body.classList.add('sidebar-open'));
    });
    document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
        button.addEventListener('click', () => body.classList.remove('sidebar-open'));
    });
    document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('¿Deseas eliminar este registro? Esta acción no se puede deshacer.')) {
                event.preventDefault();
            }
        });
    });
    document.querySelectorAll('.resource-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
})();
