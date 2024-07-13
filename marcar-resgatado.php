<?php
include_once 'conecta.php';
session_start();

if (isset($_SESSION['key'])) {
    $key = $_SESSION['key'];

    // Preparando e executando a consulta SQL com prepared statement
    $sql = "UPDATE `keys` SET `status` = '1' WHERE `key` = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $key); // "s" indica que $key é uma string
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: verificar.php');
    } else {
        echo "Não foi possível atualizar o status da chave.";
    }

    $stmt->close(); // Fechando a declaração preparada
} else {
    echo "Chave não encontrada na sessão.";
}

$conexao->close(); // Fechando a conexão com o banco de dados
?>
