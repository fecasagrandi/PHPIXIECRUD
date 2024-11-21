<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? null;
    $cotacao = $data['cotacao'] ?? null;

    if (!$id || !$cotacao) {
        echo json_encode(['status' => 'error', 'message' => 'ID e cotação são obrigatórios.']);
        exit;
    }

    $query = $pdo->prepare("UPDATE moedas SET cotacao = :cotacao, updated_at = NOW() WHERE id = :id");
    $query->execute([
        'id' => $id,
        'cotacao' => floatval($cotacao)
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Cotação atualizada com sucesso!']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro: ' . $e->getMessage()]);
}
?>
