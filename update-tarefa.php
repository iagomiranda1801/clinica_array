<?php
<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data["id"] ?? '';
    $titulo = $data["titulo"] ?? '';
    $descricao = $data["descricao"] ?? '';
    $data_conclusao = $data["data_conclusao"] ?? '';
    $horas_estudo = $data["horas_estudo"] ?? '';

    if (!$id || !$titulo || !$descricao || !$data_conclusao || !$horas_estudo) {
        echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE tarefas SET titulo=?, descricao=?, data_conclusao=?, horas_estudo=? WHERE id=?");
    $stmt->bind_param("sssdi", $titulo, $descricao, $data_conclusao, $horas_estudo, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Tarefa atualizada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar tarefa: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}