function verificarEdad() {
    let edad = parseInt(document.getElementById("edad").value);
    let mensaje = "";

    if (isNaN(edad) || edad < 0) {
        mensaje = "Por favor, ingrese una edad válida.";
    } else if (edad >= 18) {
        mensaje = "Eres mayor de edad.";
    } else {
        mensaje = "Eres menor de edad.";
    }

    document.getElementById("mensaje").innerHTML = mensaje;
}
