<?php
$cod = $_GET['codigo'];
include '../banco/conexao.php';

$sql = "SELECT conta, expoente, resultado FROM sorteios WHERE codigo_sala = '$cod'";
$result = $conn->query($sql);


$data = array();
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

$conn->close();

echo json_encode($data);
?>
