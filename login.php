<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lê o JSON enviado no corpo da requisição
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data["email"] ?? '';
    $password = $data["senha"] ?? '';

    if (!$email || !$password) {
        echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
        exit;
    }

    // Preparando a consulta SQL para buscar o usuário
    $stmt = $conn->prepare("SELECT id, email, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Comparando a senha fornecida com o hash armazenado no banco de dados
        if (password_verify($password, $user["senha"])) {
            echo json_encode([
                "success" => true,
                "message" => "Login realizado com sucesso!",
                "user" => ["id" => $user["id"], "name" => $user["email"]]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Senha incorreta."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email não encontrado."]);
    }

    $stmt->close();
}
?>
