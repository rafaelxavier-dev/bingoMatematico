<?php

$valor = $_GET['codigo'];

include '../banco/conexao.php';

// Seleciona um valor aleatório da tabela que ainda não foi sorteado
$sql = "INSERT INTO sorteios (codigo_sala, conta, resultado, expoente)
        SELECT '$valor', conta, resultado, expoente
        FROM contas_resultados
        WHERE NOT EXISTS (
            SELECT 1
            FROM sorteios
            WHERE contas_resultados.conta = sorteios.conta
                AND (
                    (contas_resultados.expoente IS NULL AND sorteios.expoente IS NULL)
                    OR (contas_resultados.expoente = sorteios.expoente)
                )
                AND codigo_sala = '$valor'
            )
            AND (expoente IS NOT NULL OR NOT EXISTS (
                SELECT 1
                FROM sorteios
                WHERE contas_resultados.conta = sorteios.conta
                    AND contas_resultados.expoente IS NULL
                    AND codigo_sala = '$valor'
            ))
        ORDER BY RAND()
        LIMIT 1
        ON DUPLICATE KEY UPDATE codigo_sala=codigo_sala";


$resultado = $conn->query($sql);

// verificar se a consulta foi executada com sucesso
if ($resultado === false) {
    echo "Erro ao executar a consulta: " . $conn->error;
    exit;
} 

// recuperar o número de linhas afetadas pela instrução INSERT
$num_linhas_afetadas = $conn->affected_rows;
if ($num_linhas_afetadas == 0){
    echo "Não há contas disponíveis.";
}
// verificar se um registro foi inserid
if ($num_linhas_afetadas > 0) {
    
    // executar a segunda consulta para recuperar os valores da última linha inserida na tabela sorteios
    $sql = "SELECT conta, resultado, expoente
            FROM contas_resultados
            WHERE conta = (SELECT conta FROM sorteios WHERE codigo_sala = '$valor' ORDER BY id DESC LIMIT 1)
                AND resultado = (SELECT resultado FROM sorteios WHERE codigo_sala = '$valor' ORDER BY id DESC LIMIT 1)";

    $resultado = $conn->query($sql);

    // verificar se a consulta foi executada com sucesso
    if ($resultado === false) {
        echo "Erro ao executar a consulta: " . $conn->error;
        exit;
    }

    // verificar se há resultados disponíveis
    if ($resultado->num_rows == 0) {
        echo "Não há resultados disponíveis.";
    } else {
        
        // recuperar os valores da última linha inserida na tabela sorteios
        $linha = $resultado->fetch_assoc();
        $conta = $linha['conta'];
        $resultado = $linha['resultado'];
        $expoente = $linha['expoente'];

        $update = "UPDATE salas_jogo SET sorteio = '$conta', expoente = '$expoente' WHERE codigo = '$valor'";
        $resultado1 = $conn->query($update);

        $valor = $conta . '<sup>' . $expoente . '</sup>';
        echo $valor;
        // processar os dados aqui
        //echo "Conta: $conta<br>";
        //echo "Resultado: $resultado<br>";
        //echo "Expoente: $expoente<br>";
    }

    $conn->close();
} 

/*if ($resultado->num_rows > 0) {
  // Atualiza a tabela para marcar o valor sorteado
  /*$row = $resultado->fetch_assoc();
  $conta_sorteada = $row["conta"];
  $conta_expoente = $row["resultado"];
  $sql = "UPDATE contas_resultados SET sorteado = 1, resultadosorteado = 1 WHERE conta = '$conta_sorteada'";
  $conn->query($sql);

  $sqlsorteado = "UPDATE salas_jogo SET sorteio = '$conta_sorteada', expoente = '$conta_expoente' WHERE codigo = '$valor'";
  $conn->query($sqlsorteado);
  $valor = $conta_sorteada . '<sup>' . $conta_expoente . '</sup>';
  echo $valor;
} else {
  echo "ACABOU!";
}*/

// Fecha a conexão com o banco de dados
