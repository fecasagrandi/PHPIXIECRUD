<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    $nome = $data['nome'] ?? null;
    $cotacao = $data['cotacao'] ?? null;

    if (!$nome || !$cotacao) {
        echo json_encode(['status' => 'error', 'message' => 'Nome da moeda e cotação são obrigatórios.']);
        exit;
    }

    $query = $pdo->prepare("INSERT INTO moedas (nome, cotacao, created_at, updated_at) VALUES (:nome, :cotacao, NOW(), NOW())");
    $query->execute([
        'nome' => strtoupper($nome),
        'cotacao' => floatval($cotacao)
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Moeda adicionada com sucesso!']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro: ' . $e->getMessage()]);
}
?>
