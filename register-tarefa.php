<?php
<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $titulo = $data["titulo"] ?? '';
    $descricao = $data["descricao"] ?? '';
    $data_conclusao = $data["data_conclusao"] ?? '';
    $horas_estudo = $data["horas_estudo"] ?? '';

    if (!$titulo || !$descricao || !$data_conclusao || !$horas_estudo) {
        echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, data_conclusao, horas_estudo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $titulo, $descricao, $data_conclusao, $horas_estudo);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Tarefa cadastrada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao cadastrar tarefa: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}