document.addEventListener('DOMContentLoaded', function () {
    let currentTaskId = null;

    function loadTasks() {
        fetch('../backend/listar_tareas.php')
            .then(res => res.json())
            .then(tasks => {
                const taskList = document.getElementById('task-list');
                taskList.innerHTML = '';

                tasks.forEach(function (task) {
                    const taskCard = document.createElement('div');
                    taskCard.className = 'col-md-4 mb-3';
                    taskCard.innerHTML = `
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">${task.titulo}</h5>
                                <p class="card-text">${task.descripcion}</p>
                                <p class="card-text"><small class="text-muted">Estado: ${task.estado || 'pendiente'}</small></p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button class="btn btn-secondary btn-sm edit-task" data-id="${task.id}" data-titulo="${task.titulo}" data-desc="${task.descripcion}" data-estado="${task.estado}">Editar</button>
                                <button class="btn btn-danger btn-sm delete-task" data-id="${task.id}">Eliminar</button>
                            </div>
                        </div>
                    `;
                    taskList.appendChild(taskCard);
                });

                document.querySelectorAll('.edit-task').forEach(function (button) {
                    button.addEventListener('click', handleEditTask);
                });

                document.querySelectorAll('.delete-task').forEach(function (button) {
                    button.addEventListener('click', handleDeleteTask);
                });
            });
    }

    function handleEditTask(event) {
        const button = event.target;
        currentTaskId = button.dataset.id;
        document.getElementById('task-title').value = button.dataset.titulo;
        document.getElementById('task-desc').value = button.dataset.desc;
        document.getElementById('task-estado').value = button.dataset.estado || 'pendiente';

        const modal = new bootstrap.Modal(document.getElementById('taskModal'));
        modal.show();
    }

    function handleDeleteTask(event) {
        const taskId = event.target.dataset.id;

        if (confirm("¿Estás seguro de eliminar esta tarea?")) {
            fetch('../backend/eliminar_tarea.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${taskId}`
            })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    loadTasks();
                });
        }
    }

    document.getElementById('task-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const titulo = document.getElementById('task-title').value;
        const descripcion = document.getElementById('task-desc').value;
        const estado = document.getElementById('task-estado').value;

        const url = currentTaskId ? '../backend/actualizar_tarea.php' : '../backend/crear_tarea.php';
        const bodyData = currentTaskId
            ? `id=${currentTaskId}&titulo=${encodeURIComponent(titulo)}&descripcion=${encodeURIComponent(descripcion)}&estado=${estado}`
            : `titulo=${encodeURIComponent(titulo)}&descripcion=${encodeURIComponent(descripcion)}`;

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: bodyData
        })
            .then(res => res.text())
            .then(data => {
                alert(data);
                loadTasks();
                document.getElementById('task-form').reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
                modal.hide();
                currentTaskId = null;
            });
    });

    loadTasks();
});
