<?php
include '../banco/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codigo_sala = $_POST['codigo'];
  
  $sql = "INSERT INTO salas_jogo (codigo) VALUES ('$codigo_sala')";

  if (!mysqli_query($conn, $sql)) {
    echo "Erro ao inserir dados: " . mysqli_error($conn);
  }

  mysqli_close($conn);

  header("Location: ../view/professor.php?codigo=$codigo_sala");
}
