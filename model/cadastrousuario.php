<?php

$cod = $_GET['codigo'];
$user = $_GET['usuario'];
include '../banco/conexao.php';

$sql = "INSERT INTO usuarios_sala(usuario, codigo) VALUES ('$user','$cod')";
if ($conn->query($sql) === TRUE) {
    echo "Coluna atualizada com sucesso!";
} else {
    echo "Erro ao atualizar a coluna: " . $conn->error;
}

$conn->close();

header("Location: ../view/aluno.php?codigo=$cod&usuario=$user");
exit;
