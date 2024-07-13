<?php
// informações para conectar
$host = "localhost"; 
$usuario = "root"; 
$senha = ""; // deixem em branco por agora
$banco_de_dados = "keyCroco"; 


$conexao = new mysqli($host, $usuario, $senha, $banco_de_dados);

// Verifica conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}


?>
