<?php
$cod = $_GET['codigo'];
$user = $_GET['usuario'];
include '../banco/conexao.php';

$query = "SELECT us.erros FROM usuarios_sala AS us JOIN salas_jogo AS sj ON us.codigo = '$cod' AND sj.codigo = '$cod' WHERE sj.mostravideo = '1' AND us.usuario = '$user'";

$result = mysqli_query($conn, $query);

// Verifica se houve erro na consulta
if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$erro = $row['erros'];
echo json_encode(array('erros' => $erro));

mysqli_close($conn);
