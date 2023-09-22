<?php
$cod = $_GET['codigo'];
$user = $_GET['usuario'];
include '../banco/conexao.php';

// captura os dados enviados via POST
$array1 = $_POST["valoresCertos"];
$array2 = $_POST["valoresErrados"];

echo $array1;
// insere os dados na tabela do MySQL
$sql = "UPDATE usuarios_sala SET acertos = '$array1', erros = '$array2' WHERE usuario = '$user' AND codigo = '$cod'";

if (!mysqli_query($conn, $sql)) {
    echo "Erro ao inserir os dados: " . mysqli_error($conn);
} 

mysqli_close($conn);
