<?php
$cod = $_GET['codigo'];
include '../banco/conexao.php';

$query = "SELECT vencedor FROM salas_jogo WHERE codigo = '$cod'";
$result = mysqli_query($conn, $query);

// Verifica se houve erro na consulta
if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conn));
}

// Obtém o resultado da consulta e o retorna em formato JSON
$row = mysqli_fetch_assoc($result);
$vencedor = $row['vencedor'];
echo json_encode(array('vencedor' => $vencedor));

// Encerra a conexão com o banco de dados
mysqli_close($conn);
