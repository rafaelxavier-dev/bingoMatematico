<?php
include '../banco/conexao.php';

$sql = "SELECT resultado FROM contas_resultados";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conn));
}

$valores = array();
while ($row = mysqli_fetch_assoc($result)) {
    $valores[] = $row['resultado'];
}
shuffle($valores);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>BINGO!</title>
    <link rel="icon" type="image/png" href="../favicon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="confetti.js"></script>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const cod = urlParams.get('codigo');
        const user = urlParams.get('usuario');

        $(document).ready(function () {
            setInterval(function () {
                $.ajax({
                    url: "../model/verificavencedor.php?codigo=" + cod,
                    dataType: "json",
                    success: function (data) {
                        // Verifica se o valor da coluna foi alterado
                        if ($("#resultado-vencedor").html() !== data.vencedor) {
                            $("#resultado-vencedor").html(data.vencedor);
                            verificaCertos();
                            openModal();
                        }
                    }
                });
            }, 1000); // Tempo em milissegundos (neste exemplo, 5 segundos)
        });

        $(document).ready(function () {
            setInterval(function () {
                $.ajax({
                    url: "../model/verificamudanca.php?codigo=" + cod,
                    dataType: "json",
                    success: function (data) {
                        // Verifica se o valor da coluna foi alterado
                        if ($("#resultado-sorteado").html() !== data.sorteio) {
                            $("#resultado-sorteado").html(data.sorteio);
                        }
                    }
                });
            }, 1000); // Tempo em milissegundos (neste exemplo, 5 segundos)
        });

        function openModal() {
            start();
            const modal = document.getElementById('modal-container')
            modal.classList.add('mostrar')
            stop();
            closeModal();
            if (e.target.id == 'modal-container' || e.target.id == "fechar") {
                modal.classList.remove('mostrar')
                localStorage.fechaModal = 'modal-container'
            }
        }

        function openModalErros() {
            const modal = document.getElementById('modal-container-erros')
            modal.classList.add('mostrar')
            close();
            if (e.target.id == 'modal-container-erros' || e.target.id == "fechar") {
                modal.classList.remove('mostrar')
                localStorage.fechaModal = 'modal-container-erros'
            }
        }

        function closeModal() {
            const modal = document.getElementById('modal-container')
            modal.classList.add('mostrar')

            modal.addEventListener('click', (e) => {

                if (e.target.id == 'modal-container' || e.target.id == "fechar") {
                    modal.classList.remove('mostrar')
                    localStorage.fechaModal = 'modal-container';
                    removerCores();
                    $.ajax({
                        url: "../model/capturaerros.php?codigo=" + cod + "&usuario=" + user,
                        dataType: "json",
                        success: function (data) {
                            // Verifica se o valor da coluna foi alterado
                            if (data.erros !== undefined && data.erros !== null && data.erros !== '') {
                                console.log("DATA ERROS: " + data.erros);
                                openModalErros();
                            }
                        }
                    });
                }
            })
        }

        function close() {
            const modal = document.getElementById('modal-container-erros')
            modal.classList.add('mostrar')

            modal.addEventListener('click', (e) => {

                if (e.target.id == 'modal-container-erros' || e.target.id == "fechar") {
                    modal.classList.remove('mostrar')
                    localStorage.fechaModal = 'modal-container-erros';
                }
            })
        }

        function verificaCertos() {
            $.ajax({
                url: '../model/validacertos.php?codigo=' + cod,
                type: 'GET',
                dataType: 'json',
                success: function (resultadosBanco) {
                    var selected = [];
                    $('.cell').each(function () {
                        if ($(this).hasClass('selected')) {
                            selected.push($(this).text());
                        }
                    });

                    var valoresComuns = []; //Array para armazenar valores em comum

                    for (var i = 0; i < selected.length; i++) {
                        if (resultadosBanco.indexOf(selected[i]) !== -1) {
                            valoresComuns.push(selected[i]);
                        }
                    }
                    var valoresCertos = [];
                    var valoresErrados = [];

                    selected.forEach(elementX => {
                        // Verificando se o elemento da array X também está presente na array Y
                        if (resultadosBanco.includes(elementX)) {
                            valoresCertos.push(elementX.trim());
                            // Encontrando todos os elementos correspondentes na tabela HTML
                            const cells = document.querySelectorAll(`#tabela td[data-value="${elementX.trim()}"]`);
                            // Mudando o background de todas as células correspondentes para verde
                            cells.forEach(cell => cell.style.backgroundColor = "#00ff88");
                        } else {
                            valoresErrados.push(elementX.trim());
                            console.log(elementX);
                            // Procurando todos os elementos correspondentes na tabela HTML
                            const cells = document.querySelectorAll(`#tabela td[data-value="${elementX.trim()}"]`);
                            // Mudando o background de todas as células correspondentes para vermelho
                            cells.forEach(cell => cell.style.backgroundColor = "red");
                        }

                    });

                    const separador = ' | ';
                    const novoCertos = valoresCertos.join(separador);
                    const novoErros = valoresErrados.join(separador);
                    console.log(novoErros);
                    console.log(valoresErrados);

                    $.ajax({
                        url: "../model/insereCertosErrados.php?codigo=" + cod + "&usuario=" + user,
                        type: "POST",
                        data: {
                            valoresCertos: novoCertos,
                            valoresErrados: novoErros
                        },
                        error: function (xhr, status, error) {
                            alert("Erro ao inserir os dados!");
                        }
                    });

                }
            });
        }

        // start

        const start = () => {
            setTimeout(function () {
                confetti.start()
            }, 1000); // 1000 is time that after 1 second start the confetti ( 1000 = 1 sec)
        };

        //  Stop

        const stop = () => {
            setTimeout(function () {
                confetti.stop()
            }, 5000); // 5000 is time that after 5 second stop the confetti ( 5000 = 5 sec)
        };

        function removerCores() {
            setTimeout(() => {
                const celulasVermelhas = document.querySelectorAll("#tabela .cell[style='background-color: red;']");
                celulasVermelhas.forEach(celula => {
                    celula.style.backgroundColor = "";
                    celula.classList.remove("selected");
                });
            }, 5000);
        }

    </script>
</head>

<body>

    <div id="modal-container" class="modal-container">
        <div class="modal">
            <button class="fechar" id="fechar">X</button>
            <h1>O jogador <div class="vencedor" id="resultado-vencedor"></div>apertou</h1>
            <h2>BINGO!</h2>
        </div>
    </div>
    <div id="modal-container-erros" class="modal-container">
        <div class="modal">
            <button class="fechar" id="fechar">X</button>
            <h1>Aconselhamos que você assista esse vídeo!</h1>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/-e2ExUbZ-8M"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
    </div>
    <div class="main-login">
        <div class="side-aluno">
            <img src="../img/bingomesa.svg" class="left-professor-image" alt="Bingo">
        </div>
        <div class="right-login">
            <div class="card-login">
                <h1>CARTELA #
                    <?php echo $valor = $_GET['codigo']; ?>
                </h1><br>
                <h3>Sorteio:</h3>
                <h1>
                    <div class="resultado" id="resultado-sorteado"></div>
                </h1><br>
                <table id="tabela" cellspacing="0" cellpadding="0">
                    <tr>
                        <td data-value="<?php echo $valores[0]; ?>" id="cell" class="cell"><?php echo $valores[0]; ?>
                        </td>
                        <td data-value="<?php echo $valores[1]; ?>" id="cell" class="cell"><?php echo $valores[1]; ?>
                        </td>
                        <td data-value="<?php echo $valores[2]; ?>" id="cell" class="cell"><?php echo $valores[2]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td data-value="<?php echo $valores[3]; ?>" id="cell" class="cell"><?php echo $valores[3]; ?>
                        </td>
                        <td data-value="<?php echo $valores[4]; ?>" id="cell" class="cell"><?php echo $valores[4]; ?>
                        </td>
                        <td data-value="<?php echo $valores[5]; ?>" id="cell" class="cell"><?php echo $valores[5]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td data-value="<?php echo $valores[6]; ?>" id="cell" class="cell"><?php echo $valores[6]; ?>
                        </td>
                        <td data-value="<?php echo $valores[7]; ?>" id="cell" class="cell"><?php echo $valores[7]; ?>
                        </td>
                        <td data-value="<?php echo $valores[8]; ?>" id="cell" class="cell"><?php echo $valores[8]; ?>
                        </td>
                    </tr>
                </table>
                <button class="btn-login" id="bingo-btn">BINGO!</button>
            </div>
        </div>
        <div class="side-aluno">
            <img src="../img/nerdcomputador.svg" class="left-professor-image" alt="Bingo">
        </div>
    </div>
</body>
<script>
    var selectedCells = [];

    $('.cell').on('click', function () {
        if ($(this).hasClass('selected')) {
            selectedCells.splice(selectedCells.indexOf(this), 1);
            $(this).removeClass('selected');

        } else {
            selectedCells.push(this);
            $(this).addClass('selected');

        }
    });

    function collectData() {

        var selected = [];
        $('.cell').each(function () {
            if ($(this).hasClass('selected')) {
                selected.push($(this).text());
            }
        });
    }

    $('#bingo-btn').on('click', function () {
        collectData();

        // Define as sequências de vitória
        var winSequences = [
            [0, 1, 2], // linha 1
            [3, 4, 5], // linha 2
            [6, 7, 8], // linha 3
            [0, 3, 6], // coluna 1
            [1, 4, 7], // coluna 2
            [2, 5, 8], // coluna 3
            [0, 4, 8], // diagonal 1
            [2, 4, 6] // diagonal 2
        ];

        // Verifica se todas as células estão selecionadas
        var allCellsSelected = true;
        for (var i = 0; i < $('.cell').length; i++) {
            if (!selectedCells.includes($('.cell')[i])) {
                allCellsSelected = false;
                break;
            }
        }

        // Verifica se todas as sequências de vitória foram selecionadas
        var winner = false;
        if (allCellsSelected) {
            for (var i = 0; i < winSequences.length; i++) {
                if (
                    selectedCells.includes($('.cell')[winSequences[i][0]]) &&
                    selectedCells.includes($('.cell')[winSequences[i][1]]) &&
                    selectedCells.includes($('.cell')[winSequences[i][2]])
                ) {
                    winner = true;
                    break;
                }
            }
        }

        if (winner) {
            start();
            selectedCells = [];
            atualizarRegistro();
            stop();
        } else {
            // Modal de derrota
            alert('Não há uma sequência selecionada.');
        }
    });

    function createConfetti() {
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');
        confetti.style.left = Math.random() * window.innerWidth + 'px';
        confetti.style.animationDelay = Math.random() * 3 + 's';
        document.body.appendChild(confetti);
        setTimeout(() => {
            confetti.remove();
        }, 3000);
    }

    function atualizarRegistro() {

        // faz a requisição AJAX
        $.ajax({
            url: '../model/vencedor.php?codigo=' + cod + '&usuario=' + user, // arquivo PHP que vai atualizar o registro
            type: 'POST', // método de envio dos dados
            success: function (response) {
                // exibe mensagem de sucesso ou erro
            }
        });
    }

    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let searchBtn = document.querySelector(".bx-search");

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        menuBtnChange(); //calling the function(optional)
    });

    searchBtn.addEventListener("click", () => { // Sidebar open when you click on the search iocn
        sidebar.classList.toggle("open");
        menuBtnChange(); //calling the function(optional)
    });

    // following are the code to change sidebar button(optional)
    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
        }
    }
</script>

</html>