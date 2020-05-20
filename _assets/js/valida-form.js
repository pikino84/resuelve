// Detiene el envio del formulario si algun campo es invalidp
(function() {
    'use strict';
    window.addEventListener('load', function() {
        //Se ontiene el formulario al que se le quiere validar
        var forms = document.getElementsByClassName('needs-validation');
        //Revisa los campos y previene el envio
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();