<?php
include_once 'conecta.php';
session_start();

$gerado = false; // Inicializa a variável $gerado
$senha_correta = 'senha_admin'; // Senha de administrador (substitua pela sua senha)

function genKey($tamanho) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo_aleatorio = '';
    
    for ($i = 0; $i < $tamanho; $i++) {
        $codigo_aleatorio .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    
    $key = "#" . $codigo_aleatorio . "CROCO";
    
    return $key;
}

// Verifica se foi submetido o formulário de gerar código
if (isset($_POST['gerar'])) {
    // Não faz nada aqui inicialmente, o processamento será feito pelo JavaScript
}

// Verifica se a senha foi confirmada via JavaScript
if (isset($_POST['senha'])) {
    $senha_digitada = $_POST['senha'];
    
    // Verifica se a senha digitada é correta
    if ($senha_digitada === $senha_correta) {
        $gerado = true;
        
        // Se a senha estiver correta, gera o código
        $key = genKey(4);
        
        // Verifica se a chave já existe no banco de dados
        $sql_verifica = "SELECT COUNT(*) AS total FROM `keys` WHERE `key` = '$key'";
        $resultado_verifica = $conexao->query($sql_verifica);
        
        if ($resultado_verifica) {
            $row = $resultado_verifica->fetch_assoc();
            if ($row['total'] == 0) {
                // Se a chave não existe no banco de dados, insere
                $sql_inserir = "INSERT INTO `keys` (`ID`, `key`, `status`, `desconto`, `usos`) VALUES (NULL, '$key', 0, '{desconto}', '{usos}');";
                $resultado_inserir = $conexao->query($sql_inserir);
                if ($resultado_inserir) {
                    echo "<div class='alert alert-success' role='alert'>Código gerado e inserido no banco de dados.</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Ocorreu um erro ao inserir o código no banco de dados.</div>";
                }
            } else {
                // Se a chave já existe no banco de dados, não faz nada
                echo "<div class='alert alert-warning' role='alert'>Já existe uma chave idêntica gerada anteriormente.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Ocorreu um erro ao verificar a existência da chave no banco de dados.</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Senha incorreta. Apenas administradores podem gerar códigos.</div>";
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
        
        <!-- Formulário para gerar código -->
        <form id="gerarForm" method="post">
            <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="confirmarSenha()">Gerar Código</button>
            </div>
        </form>
        
        <script>
            function confirmarSenha() {
                var senha = prompt("Por favor, digite a senha para gerar o código:");
                if (senha !== null) {
                    // Se a senha não for nula, enviar o valor via POST
                    var form = document.getElementById("gerarForm");
                    var senhaInput = document.createElement("input");
                    senhaInput.setAttribute("type", "hidden");
                    senhaInput.setAttribute("name", "senha");
                    senhaInput.setAttribute("value", senha);
                    form.appendChild(senhaInput);
                    form.submit(); // Submeter o formulário
                }
            }
        </script>
        
        <?php
        if ($gerado && isset($_POST['senha'])) {
            // Exibe o código gerado apenas se a senha foi confirmada
            echo "<p id='codigoGerado' class='lead'>$key</p>";
            echo "<button class='btn btn-info mb-3' onclick='copiarCodigo(\"codigoGerado\")'>Clique para copiar</button>";
        }
        ?>
    </div>

    <!-- Bootstrap JS e dependências opcionais (jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        function copiarCodigo(elementId) {
            var codigoElement = document.getElementById(elementId);
            var textoSelecionado = window.getSelection().rangeCount > 0 ? window.getSelection().toString() : codigoElement.textContent;
            var copiaInput = document.createElement("textarea");
            copiaInput.value = textoSelecionado;
            document.body.appendChild(copiaInput);
            copiaInput.select();
            document.execCommand("copy");
            document.body.removeChild(copiaInput);
            alert("Código copiado para a área de transferência!");
        }
    </script>
</body>
</html>
