<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "bingo_matematico";


$conn = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
  die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}
