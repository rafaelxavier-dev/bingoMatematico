<?php
$cod = $_GET['codigo'];
include '../banco/conexao.php';

// Executa a consulta SQL
$resultado = $conn->query("SELECT usuario, acertos, erros FROM usuarios_sala WHERE codigo = '$cod'");

// Cria um array para armazenar os resultados
$data = array();

// Itera sobre os resultados e adiciona ao array
while ($row = $resultado->fetch_assoc()) {
  $data[] = $row;
}

// Fecha a conexão com o banco de dados
$conn->close();

// Retorna os resultados no formato JSON
echo json_encode($data);
?>
