<?php
include_once 'conecta.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['codigoInput'])) {
        $codigo = $_POST['codigoInput'];
        $_SESSION['key'] = $codigo;

        // Evite SQL Injection usando prepared statements
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

header('Location: verificar.php');
exit();
?>
