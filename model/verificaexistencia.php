<?php
include '../banco/conexao.php';

$codigo_sala = $_GET['codigo'];
$user = $_GET['usuario'];

$sql = "SELECT * FROM salas_jogo WHERE codigo = '$codigo_sala'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $insere = "INSERT INTO usuarios_sala(usuario, codigo) VALUES ('$user','$codigo_sala')";
    mysqli_query($conn, $insere);

    header("Location: ../view/aluno.php?codigo=$codigo_sala&usuario=$user");
} else {
    header("Location: ../index.html");
}


$conn->close();
?>