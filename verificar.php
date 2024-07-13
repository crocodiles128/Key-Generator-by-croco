<?php
include_once 'conecta.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['codigoInput'])) {
        $codigo = $_POST['codigoInput'];
        $_SESSION['key'] = $codigo;

        // Consulta SQL para verificar se o código existe e pode ser marcado como resgatado
        $stmt = $conexao->prepare("SELECT * FROM `keys` WHERE `key` = ? AND `status` = 0");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $_SESSION['resgatado'] = false; // Código válido
        } else {
            $_SESSION['resgatado'] = true; // Código já resgatado ou inexistente
        }
    }
}

// Função para marcar como resgatado
function marcarComoResgatado() {
    global $conexao;
    if (isset($_SESSION['key'])) {
        $keyParaVerificar = $_SESSION['key'];
        // Atualização do status no banco de dados
        $sql = "UPDATE `keys` SET `status` = 1 WHERE `key` = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $keyParaVerificar);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Código</title>
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
        <h1 class="mb-4">Verificação de Código</h1>
        <form action="verifica-script.php" method="post" class="mb-4">
            <div class="form-group">
                <label for="codigoInput">Digite o código para verificar:</label>
                <input type="text" class="form-control" id="codigoInput" name="codigoInput" required>
            </div>
            <button type="submit" class="btn btn-primary">Verificar</button>
        </form>

        <?php
        if (isset($_SESSION['resgatado'])) {
            if ($_SESSION['resgatado']) {
                echo "<div class='alert alert-danger' role='alert'>Código já resgatado ou inexistente</div>";
            } else {
                echo "<div class='alert alert-success' role='alert'>Código válido</div>";
            }
            unset($_SESSION['resgatado']); // Limpa a variável de sessão depois de exibir a mensagem
        }
        ?>

        <button onclick="marcarComoResgatado()" class="btn btn-danger">Marcar como resgatado (IRREVERSÍVEL)</button>
    </div>

    <!-- Bootstrap JS e dependências opcionais (jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        function marcarComoResgatado() {
            if (confirm("Tem certeza que deseja marcar como resgatado? Essa ação é irreversível.")) {
                window.location.href = "marcar-resgatado.php"; // Redireciona para a página de marcação como resgatado
            }
        }
    </script>
</body>
</html>
