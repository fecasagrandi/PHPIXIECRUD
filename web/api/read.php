<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $moeda = isset($_GET['moeda']) ? strtoupper($_GET['moeda']) : null;

    if (!$moeda) {
        throw new Exception('Código da moeda não fornecido');
    }

    $stmt = $pdo->prepare("SELECT nome, cotacao, updated_at FROM moedas WHERE nome = :nome LIMIT 1");
    $stmt->execute(['nome' => $moeda]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo json_encode([
            'nome' => $resultado['nome'],
            'cotacao' => floatval($resultado['cotacao']),
            'updated_at' => $resultado['updated_at']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Moeda não encontrada']);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['erro' => $e->getMessage()]);
}
?> 