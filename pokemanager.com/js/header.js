document.addEventListener("DOMContentLoaded", () => {
    const abrir = document.getElementById("abrir-registro");
    const cerrar = document.getElementById("cerrar-registro");
    const dialogo = document.getElementById("dialogo-registro");
    const formulario = document.querySelector(".formulario-registro");

    abrir.addEventListener("click", () => dialogo.showModal());
    cerrar.addEventListener("click", () => dialogo.close());

    formulario.addEventListener("submit", (e) => {
        const email = formulario.email.value.trim();
        const emailRep = formulario.email_repetido.value.trim();
        const pass = formulario.contrasenya.value;
        const passRep = formulario.contrasenya_repetida.value;
        const edad = parseInt(formulario.edad.value, 10);

        const tieneLetra = /[a-zA-Z]/.test(pass);
        const tieneNumero = /\d/.test(pass);

        if (email !== emailRep) {
            alert("Los emails no coinciden.");
            e.preventDefault();
        } else if (pass !== passRep) {
            alert("Las contraseñas no coinciden.");
            e.preventDefault();
        } else if (pass.length < 8 || !tieneLetra || !tieneNumero) {
            alert("La contraseña debe tener al menos 8 caracteres, una letra y un número.");
            e.preventDefault();
        } else if (isNaN(edad) || edad < 14) {
            alert("Debes tener al menos 14 años para registrarte.");
            e.preventDefault();
        }
    });
});

