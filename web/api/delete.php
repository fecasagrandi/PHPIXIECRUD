<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        echo json_encode(["error" => "ID da moeda não fornecido."]);
        exit;
    }

    $id = $data['id'];
    $stmt = $pdo->prepare("DELETE FROM moedas WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Moeda removida com sucesso!"]);
    } else {
        echo json_encode(["error" => "Erro ao remover a moeda."]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Erro: " . $e->getMessage()]);
}
