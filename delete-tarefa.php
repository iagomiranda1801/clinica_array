<?php
<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data["id"] ?? '';

    if (!$id) {
        echo json_encode(["success" => false, "message" => "ID da tarefa não informado."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Tarefa deletada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao deletar tarefa: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}