<?php

$cod = $_GET['codigo'];

include '../banco/conexao.php';

// Seleciona um valor aleatório da tabela que ainda não foi sorteado
$sql = "SELECT timer FROM salas_jogo WHERE codigo = '$cod'";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    // Atualiza a tabela para marcar o valor sorteado
    $row = $resultado->fetch_assoc();
    $timer = $row["timer"];
    echo $timer;
}

// Fecha a conexão com o banco de dados
$conn->close();
