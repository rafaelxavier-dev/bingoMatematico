<?php
$cod = $_GET['codigo'];
include '../banco/conexao.php';

// captura os dados enviados via POST
$timerToggle = $_POST["timerToggle"];

// insere os dados na tabela do MySQL
$sql = "UPDATE salas_jogo SET timer = '$timerToggle' WHERE codigo = '$cod'";

if (!mysqli_query($conn, $sql)) {
    echo "Erro ao inserir os dados: " . mysqli_error($conn);
} 

mysqli_close($conn);
