<?php
include '../banco/conexao.php';

$codigo = $_GET['codigo'];

$resultadosBanco = array();

$sql = "SELECT resultado FROM sorteios WHERE codigo_sala = '$codigo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $resultadosBanco[] = $row["resultado"];
  }
}

echo json_encode($resultadosBanco);
$conn->close();
?>
