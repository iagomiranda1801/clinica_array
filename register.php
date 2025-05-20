<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $nome = $data["nome"] ?? '';
    $email = $data["email"] ?? '';
    $senha = $data["senha"] ?? '';

    if (!$nome || !$email || !$senha) {
        echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
        exit;
    }

    // Verifica se o email já está cadastrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email já cadastrado."]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Insere o novo usuário
    $hashedSenha = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $hashedSenha);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao cadastrar usuário: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}