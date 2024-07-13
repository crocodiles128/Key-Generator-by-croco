<?php
include_once 'conecta.php'; // Inclui o arquivo de conexão

$gerado = false; // Inicializa a variável $gerado

function genKey($tamanho) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo_aleatorio = '';
    
    for ($i = 0; $i < $tamanho; $i++) {
        $codigo_aleatorio .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    
    $key = "#" . $codigo_aleatorio . "CROCO";
    
    return $key;
}

// Verifica se já foi gerada uma chave para esta máquina
session_start();
if (isset($_SESSION['chave_gerada'])) {
    $key = $_SESSION['chave_gerada'];
    $gerado = true;
} elseif (isset($_POST['gerar'])) {
    $key = genKey(4);
    
    // Verifica se a chave já existe no banco de dados
    $sql_verifica = "SELECT COUNT(*) AS total FROM `keys` WHERE `key` = '$key'";
    $resultado_verifica = $conexao->query($sql_verifica);
    
    if ($resultado_verifica) {
        $row = $resultado_verifica->fetch_assoc();
        if ($row['total'] == 0) {
            // Se a chave não existe no banco de dados, insere
            $sql_inserir = "INSERT INTO `keys` (`ID`, `key`, `status`) VALUES (NULL, '$key', 0);";
            $resultado_inserir = $conexao->query($sql_inserir);
            if ($resultado_inserir) {
                $_SESSION['chave_gerada'] = $key; // Armazena a chave na sessão
                $gerado = true;
            } else {
                echo "<p class='alert alert-danger'>Ocorreu um erro ao inserir o código no banco de dados.</p>";
            }
        } else {
            // Se a chave já existe no banco de dados, não faz nada
            echo "<p class='alert alert-warning'>Já existe uma chave idêntica gerada anteriormente.</p>";
        }
    } else {
        echo "<p class='alert alert-danger'>Ocorreu um erro ao verificar a existência da chave no banco de dados.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Código</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Gerador de Código</h1>
        <form method="post" class="mb-4">
            <button type="submit" name="gerar" class="btn btn-primary">Gerar Código</button>
        </form>
        <p id="codigoGerado" class="lead"><?php if(isset($key)){ echo $key; } ?></p> <!-- Exibe o código gerado -->

        <?php
        if ($gerado) {
            echo "<div class='alert alert-success' role='alert'>Código gerado e inserido no banco de dados.</div>";
        }
        ?>

        <!-- Botão para Copiar Código -->
        <button class="btn btn-secondary" id="btnCopiar" onclick="copiarCodigo()">Copiar Código</button>
    </div>

    <!-- Bootstrap JS e dependências opcionais (jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function copiarCodigo() {
            var codigoText = document.getElementById('codigoGerado').textContent.trim();
            var textarea = document.createElement('textarea');
            textarea.value = codigoText;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Código copiado para a área de transferência.');
        }
    </script>
</body>
</html>
