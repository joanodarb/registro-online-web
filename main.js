// assets/js/main.js

document.addEventListener('DOMContentLoaded', function () {
    // Confirmación de eliminación
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este elemento?');
            if (!confirmDelete) {
                e.preventDefault();
            }
        });
    });

    // Mostrar mensaje de éxito temporal
    function showSuccessMessage(message, duration = 3000) {
        const alert = document.createElement('div');
        alert.className = 'alert success';
        alert.textContent = message;
        document.body.prepend(alert);

        setTimeout(() => {
            alert.remove();
        }, duration);
    }

    // Mostrar mensaje de error temporal
    function showErrorMessage(message, duration = 4000) {
        const alert = document.createElement('div');
        alert.className = 'alert error';
        alert.textContent = message;
        document.body.prepend(alert);

        setTimeout(() => {
            alert.remove();
        }, duration);
    }

    // Validación de formulario
    function validateForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function (e) {
                const inputs = form.querySelectorAll('input, textarea, select');
                let isValid = true;

                inputs.forEach(input => {
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        input.style.border = '1px solid red';
                        isValid = false;
                    } else {
                        input.style.border = '1px solid #ccc';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    showErrorMessage('Por favor, completa todos los campos obligatorios.');
                }
            });
        }
    }

    // Ejecutar validación en formularios
    validateForm('registroForm');
    validateForm('loginForm');
    validateForm('usuarioForm');

    // Mostrar mensaje de éxito o error desde PHP
    const successMsg = document.querySelector('.alert.success');
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 4000);
    }

    const errorMsg = document.querySelector('.alert.error');
    if (errorMsg) {
        setTimeout(() => {
            errorMsg.style.display = 'none';
        }, 5000);
    }
});