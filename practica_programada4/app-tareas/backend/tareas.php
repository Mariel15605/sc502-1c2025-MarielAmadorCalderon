<?php
header("Content-Type: application/json");
require_once "conexion.php";
require_once "sesiones.php";

verificarSesionActiva();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM tareas ORDER BY fecha_limite ASC";
        $result = $conn->query($sql);
        $tareas = [];

        while ($row = $result->fetch_assoc()) {
            $tareas[] = $row;
        }

        echo json_encode($tareas);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['titulo'], $data['descripcion'], $data['fecha_limite'])) {
            $titulo = $conn->real_escape_string($data['titulo']);
            $descripcion = $conn->real_escape_string($data['descripcion']);
            $fecha = $conn->real_escape_string($data['fecha_limite']);
            $usuario = $conn->real_escape_string($_SESSION['usuario']);

            $sql = "INSERT INTO tareas (titulo, descripcion, fecha_limite, usuario) 
                    VALUES ('$titulo', '$descripcion', '$fecha', '$usuario')";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Tarea agregada"]);
            } else {
                echo json_encode(["error" => $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);

        if (isset($data['id'])) {
            $id = intval($data['id']);

            // También eliminamos los comentarios asociados a la tarea
            $conn->query("DELETE FROM comentarios WHERE tarea_id = $id");
            $sql = "DELETE FROM tareas WHERE id = $id";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Tarea eliminada"]);
            } else {
                echo json_encode(["error" => $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Falta el id"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id'], $data['titulo'], $data['descripcion'], $data['fecha_limite'])) {
            $id = intval($data['id']);
            $titulo = $conn->real_escape_string($data['titulo']);
            $descripcion = $conn->real_escape_string($data['descripcion']);
            $fecha = $conn->real_escape_string($data['fecha_limite']);

            $sql = "UPDATE tareas SET titulo='$titulo', descripcion='$descripcion', fecha_limite='$fecha' WHERE id=$id";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Tarea actualizada"]);
            } else {
                echo json_encode(["error" => $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
