📘 Caso de Estudio 2 – Instrucciones de Implementación

A continuación, se detallan los pasos necesarios para la correcta implementación y entrega del proyecto. ¡Sigue cada sección cuidadosamente!

🛠️ 1. Creación de la Base de Datos 

    Dirígete a la carpeta db del proyecto.

    Ejecuta el query SQL que se encuentra allí para crear la base de datos en tu instalación de MySQL.
listo

📁 2. Configuración del Entorno

    Copia y aloja todo el proyecto en la carpeta htdocs de tu instalación de XAMPP.

    Asegúrate de que los servicios Apache y MySQL estén activos desde el panel de control de XAMPP.


⚙️ 3. Configuración de la Conexión a la Base de Datos

    Abre el archivo config/db.php.

    Verifica y, de ser necesario, ajusta los datos de conexión (host, user, password, database) de acuerdo con tu entorno local de MySQL.


🔐 4. Verificación del Login

    Accede al proyecto a través del navegador.

    Verifica que el sistema de login funcione correctamente ingresando credenciales válidas.


🧾 5. Revisión del Mantenimiento de Tareas

    Explora y comprende el código asociado al módulo de tareas.

    Asegúrate de conocer cómo se crean, editan, listan y eliminan tareas.


✍️ 6. Actualización del Módulo de Comentarios

    Abre el archivo dashboard.js.

    Modifica el código encargado de cargar, crear y eliminar comentarios para que utilice fetch y se conecte con el nuevo archivo comments.php.


📄 7. Implementación de comments.php

    Crea el archivo comments.php.

    Utiliza task.php como referencia para su estructura y lógica de acceso a la base de datos.

    Asegúrate de cubrir los métodos necesarios: GET, POST, DELETE, etc.


✅ 8. Validación Final

    Realiza pruebas completas del sistema.

    Verifica que todas las funcionalidades trabajen correctamente, incluyendo login, tareas y comentarios.


📦 9. Empaquetado y Entrega

    Comprime todo el proyecto en un archivo .zip llamado:
    caso_estudio_2.zip

    Sube el archivo al Campus Virtual como se indica en las instrucciones del curso.

    