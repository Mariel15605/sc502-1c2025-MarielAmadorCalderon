<?php
header("Content-Type: application/json");
require_once "conexion.php";
require_once "sesiones.php";

verificarSesionActiva();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['tarea_id'])) {
            $tarea_id = intval($_GET['tarea_id']);
            $sql = "SELECT * FROM comentarios WHERE tarea_id = $tarea_id ORDER BY fecha DESC";
            $result = $conn->query($sql);
            $comentarios = [];

            while ($row = $result->fetch_assoc()) {
                $comentarios[] = $row;
            }

            echo json_encode($comentarios);
        } else {
            echo json_encode(["error" => "Falta tarea_id"]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['tarea_id'], $data['contenido'])) {
            $tarea_id = intval($data['tarea_id']);
            $contenido = $conn->real_escape_string($data['contenido']);
            $usuario = $conn->real_escape_string($_SESSION['usuario']);

            $sql = "INSERT INTO comentarios (tarea_id, contenido, usuario) 
                    VALUES ($tarea_id, '$contenido', '$usuario')";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Comentario agregado"]);
            } else {
                echo json_encode(["error" => $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id'], $data['contenido'])) {
            $id = intval($data['id']);
            $contenido = $conn->real_escape_string($data['contenido']);

            $sql = "UPDATE comentarios SET contenido = '$contenido' WHERE id = $id";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Comentario actualizado"]);
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
            $sql = "DELETE FROM comentarios WHERE id = $id";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Comentario eliminado"]);
            } else {
                echo json_encode(["error" => $conn->error]);
            }
        } else {
            echo json_encode(["error" => "Falta el id"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
