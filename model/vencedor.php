<?php

$cod = $_GET['codigo'];
$user = $_GET['usuario'];
include '../banco/conexao.php';

$sql = "UPDATE salas_jogo SET vencedor = '$user' WHERE codigo = '$cod'";
if ($conn->query($sql) === TRUE) {
    echo "Coluna atualizada com sucesso!";
} else {
    echo "Erro ao atualizar a coluna: " . $conn->error;
}

$conn->close();
