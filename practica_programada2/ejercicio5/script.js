const estudiantes = [
    { nombre: "Juan", apellido: "Pérez", nota: 85 },
    { nombre: "Ana", apellido: "Gómez", nota: 90 },
    { nombre: "Carlos", apellido: "López", nota: 78 },
    { nombre: "Sofía", apellido: "Rodríguez", nota: 95 },
    { nombre: "Diego", apellido: "Fernández", nota: 88 }
];

let lista = document.getElementById("listaEstudiantes");
let totalNotas = 0;

estudiantes.forEach(estudiante => {
    let estudianteInfo = document.createElement("p");
    estudianteInfo.textContent = `${estudiante.nombre} ${estudiante.apellido}`;
    lista.appendChild(estudianteInfo);
    totalNotas += estudiante.nota;
});

let promedio = totalNotas / estudiantes.length;
let promedioElemento = document.createElement("p");
promedioElemento.innerHTML = `<strong>Promedio de notas: ${promedio.toFixed(2)}</strong>`;
lista.appendChild(promedioElemento);

