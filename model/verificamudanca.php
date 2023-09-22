<?php
$valor = $_GET['codigo'];
include '../banco/conexao.php';

$query = "SELECT sorteio, expoente FROM salas_jogo WHERE codigo = '$valor'";
$result = mysqli_query($conn, $query);

// Verifica se houve erro na consulta
if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conn));
}

// Obtém o resultado da consulta e o retorna em formato JSON
$row = mysqli_fetch_assoc($result);
$sorteio = $row['sorteio'];
$expoente = $row['expoente'];
echo json_encode(array('sorteio' => $sorteio . '<sup>' . $expoente . '</sup>'));

// Encerra a conexão com o banco de dados
mysqli_close($conn);
