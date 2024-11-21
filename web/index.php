<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Project\Framework;

$framework = new Framework();
$framework->registerDebugHandlers();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

function getUserData($pdo)
{
    $stmt = $pdo->query("SELECT * FROM moedas");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function renderUserTable($moedas)
{
    if (count($moedas) > 0) {
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Nome da Moeda</th>
                <th>Cotação (BRL)</th>
                <th>Data Atualização</th>
                <th>Última Alteração</th>
                <th>Ações</th>
              </tr>";

        foreach ($moedas as $moeda) {
            echo "<tr>";
            echo "<td>{$moeda['id']}</td>";
            echo "<td>{$moeda['nome']}</td>";
            echo "<td>R$ {$moeda['cotacao']}</td>";
            echo "<td>{$moeda['created_at']}</td>";
            echo "<td>{$moeda['updated_at']}</td>";
            echo "<td>
                    <button onclick=\"updateCurrency(" . $moeda['id'] . ")\">Atualizar Cotação</button>
                    <button onclick=\"deleteCurrency(" . $moeda['id'] . ")\">Remover</button>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhuma moeda cadastrada.</p>";
    }
    echo "<button onclick=\"createCurrency()\">Adicionar Nova Moeda</button>";
}

$moedas = getUserData($pdo);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD - PHPixie</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <script>
        function createCurrency() {
            const nome = prompt("Digite o nome da moeda (ex: USD, EUR, BTC):");
            const cotacao = prompt("Digite a cotação em BRL:");
            if (nome && cotacao) {
                fetch(`api/create.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nome,
                        cotacao
                    })
                }).then(() => location.reload());
            }
        }

        function updateCurrency(id) {
            const cotacao = prompt("Digite a nova cotação em BRL:");
            if (cotacao) {
                fetch(`api/update.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        cotacao
                    })
                }).then(() => location.reload());
            }
        }

        function deleteCurrency(id) {
            if (confirm("Deseja remover esta moeda?")) {
                fetch(`api/delete.php`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Erro ao remover moeda:', error);
                });
            }
        }
    </script>
</head>

<body>
    <h1>GESTOR DE MOEDAS - API PHPIXIE</h1>
    <?php renderUserTable($moedas); ?>
</body>

</html>
