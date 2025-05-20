<?php
<?php
include 'connection.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$sql = "SELECT id, titulo, descricao, data_conclusao, horas_estudo FROM tarefas";
$result = $conn->query($sql);

$tarefas = [];
while ($row = $result->fetch_assoc()) {
    $tarefas[] = $row;
}

echo json_encode($tarefas);

$conn->close();