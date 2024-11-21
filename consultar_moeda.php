<?php
header('Content-Type: text/html; charset=utf-8');

function consultarMoeda($moeda) {
    $url = "http://localhost/phpixiecrud/web/api/read.php?moeda=" . urlencode($moeda);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $resposta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return json_decode($resposta, true);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Cotação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .resultado {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .erro {
            color: red;
            margin-top: 20px;
        }
        .calculo-receita {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Consulta de Cotação</h1>
        
        <form method="POST">
            <label for="moeda">Digite o código da moeda (ex: USD, EUR, BTC):</label>
            <input type="text" id="moeda" name="moeda" required 
                   placeholder="Digite o código da moeda" 
                   value="<?php echo isset($_POST['moeda']) ? htmlspecialchars($_POST['moeda']) : ''; ?>">
            <button type="submit">Consultar Cotação</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['moeda'])) {
            $moeda = strtoupper(trim($_POST['moeda']));
            $resultado = consultarMoeda($moeda);
            
            if (isset($resultado['cotacao'])) {
                $valor_dolares = 10000;
                $valor_reais = $valor_dolares * $resultado['cotacao'];
                
                echo "<div class='resultado'>";
                echo "<h2>Moeda: {$resultado['nome']}</h2>";
                echo "<h3>Cotação: R$ " . number_format($resultado['cotacao'], 2, ',', '.') . "</h3>";
                echo "<p>Última atualização: {$resultado['updated_at']}</p>";
                
                echo "<div class='calculo-receita'>";
                echo "<h3>Cálculo de Receita</h3>";
                echo "<p>Receita em dólares: $" . number_format($valor_dolares, 2, ',', '.') . "</p>";
                echo "<p>Receita em reais: R$ " . number_format($valor_reais, 2, ',', '.') . "</p>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='erro'>";
                echo "Moeda não encontrada ou erro na consulta.";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html> 