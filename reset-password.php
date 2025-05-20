<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['email'];
    $novaSenha = $data['senha'];

    // Verifica se o ID é válido
    if (!$email) {
        echo json_encode(["success" => false, "message" => "ID inválido!"]);
        exit;
    }

    // Prepara a query para atualizar a senha
    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");

    $hashedNovaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);

    $stmt->bind_param("ss", $hashedNovaSenha, $email);


    // Executa a query
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Senha redefinida com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao redefinir senha: " . $conn->error]);
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
}
