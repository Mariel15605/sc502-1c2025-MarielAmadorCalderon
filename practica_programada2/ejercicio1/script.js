function calcular() {
let salarioBruto = parseFloat(document.getElementById("salario").value);

if (isNaN(salarioBruto) || salarioBruto <= 0) {
    document.getElementById("resultado").innerHTML = "Por favor, ingrese un salario válido.";
    return;
}


let cargasSociales = salarioBruto * 0.1067;


let impuestoRenta = 0;

if (salarioBruto > 941000 && salarioBruto <= 1383000) {
        impuestoRenta = (salarioBruto - 941000) * 0.1;
    } else if (salarioBruto > 1383000 && salarioBruto <= 2422000) {
        impuestoRenta = (442000 * 0.1) + ((salarioBruto - 1383000) * 0.15);
    } else if (salarioBruto > 2422000) {
        impuestoRenta = (442000 * 0.1) + (1039000 * 0.15) + ((salarioBruto - 2422000) * 0.2);
}

let salarioNeto = salarioBruto - cargasSociales - impuestoRenta;

document.getElementById("resultado").innerHTML = `
    <p>Cargas Sociales: ₡${cargasSociales.toFixed(2)}</p>
    <p>Impuesto sobre la Renta: ₡${impuestoRenta.toFixed(2)}</p>
    <p><strong>Salario Neto: ₡${salarioNeto.toFixed(2)}</strong></p>
    `;
}
