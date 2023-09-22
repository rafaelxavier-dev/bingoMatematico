<?php
$valor = $_GET['codigo'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Área do Professor</title>
    <link rel="icon" type="image/png" href="../favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="confetti.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const cod = urlParams.get('codigo');

        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    url: "../model/verificavencedor.php?codigo=" + cod,
                    dataType: "json",
                    success: function(data) {
                        // Verifica se o valor da coluna foi alterado
                        if ($("#resultado-vencedor").html() !== data.vencedor) {
                            $("#resultado-vencedor").html(data.vencedor);
                            openModal();
                        }
                    }
                });
            }, 1000); // Tempo em milissegundos (neste exemplo, 5 segundos)
        });

        $(document).ready(function() {
            $("#botao-sortear").click(function() {
                $.ajax({
                    type: "GET",
                    url: "../model/sortear.php?codigo=" + cod,
                    success: function(data) {
                        $("#resultado-sorteio").html(data);
                    }
                });
            });
        });

        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    url: "../model/recebecontas.php?codigo=" + cod,
                    dataType: "json",
                    success: function(data) {
                        $("#tabela_contas tbody").empty();
                        $.each(data, function(index, row) {
                            var tr = $("<tr/>");
                            tr.append($("<td/>").html(row.conta + '<sup>' + row.expoente + '</sup>'));
                            tr.append($("<td/>").text(row.resultado));
                            $("#tabela_contas tbody").append(tr);
                        });
                    }
                });
            }, 2000);
        });

        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    url: "../model/recebeusuarios.php?codigo=" + cod,
                    dataType: "json",
                    success: function(data) {
                        $("#tabela_usuarios tbody").empty();
                        $.each(data, function(index, row) {
                            var tr = $("<tr/>");
                            tr.append($("<td/>").text(row.usuario));
                            tr.append($("<td/>").text(row.acertos));
                            tr.append($("<td/>").text(row.erros));
                            $("#tabela_usuarios tbody").append(tr);
                        });
                    }
                });
            }, 2000);
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

        function closeModal() {
            const modal = document.getElementById('modal-container')
            modal.classList.add('mostrar')

            modal.addEventListener('click', (e) => {

                if (e.target.id == 'modal-container' || e.target.id == "fechar") {
                    modal.classList.remove('mostrar')
                    localStorage.fechaModal = 'modal-container'
                }
            })
        }

        // start

        const start = () => {
            setTimeout(function() {
                confetti.start()
            }, 1000); // 1000 is time that after 1 second start the confetti ( 1000 = 1 sec)
        };

        //  Stop

        const stop = () => {
            setTimeout(function() {
                confetti.stop()
            }, 5000); // 5000 is time that after 5 second stop the confetti ( 5000 = 5 sec)
        };

        $(document).ready(function() {
            $("#toggle-button").click(function() {
                if ($(this).is(":checked")) {
                    $("#status").html("Mostrar vídeo aula");
                    $("#data-status").val("1");
                    sendData();
                } else {
                    $("#status").html("Não mostrar vídeo aula");
                    $("#data-status").val("0");
                    sendData();
                }
            });
        });

        $(document).ready(function() {
            $("#toggle-button-timer").click(function() {
                if ($(this).is(":checked")) {
                    $("#status-timer").html("Desativar timer (60 segundos)");
                    $("#data-status-timer").val("1");
                    sendDataTimer();
                } else {
                    $("#status-timer").html("Ativar timer (60 segundos)");
                    $("#data-status-timer").val("0");
                    sendDataTimer();
                }
            });
        });

        function sendData() {
            var videoToggle = document.getElementById("data-status").value;
            console.log(videoToggle);
            $.ajax({
                type: "POST",
                url: "../model/configura.php?codigo=" + cod,
                data: {
                    videoToggle: videoToggle
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }

        function sendDataTimer() {
            var timerToggle = document.getElementById("data-status-timer").value;
            console.log(timerToggle);
            $.ajax({
                type: "POST",
                url: "../model/configuraTimer.php?codigo=" + cod,
                data: {
                    timerToggle: timerToggle
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }

        var intervalId;
        function sorteio(){
            
            clearInterval(intervalId);
            $.ajax({
                    type: "GET",
                    url: "../model/sortear.php?codigo=" + cod,
                    success: function(data) {
                        $("#resultado-sorteio").html(data);
                    }
                });
        }

        function verificarTimer() {
            $.ajax({
                url: '../model/verificaTimer.php?codigo=' + cod,
                method: 'GET',
                success: function(response) {
                    if (response === '1') {
                        $('#botao-sortear').prop('disabled', true);
                        intervalId = setInterval(sorteio(), 60000);
                        console.log(intervalId);
                    } else {
                        $('#botao-sortear').prop('disabled', false);
                        clearInterval(intervalId);
                    }
                }
            });
        }
        setInterval(function() {
            verificarTimer();
        }, 2000);
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
    <div class="main-login">
        <div class="left-professor">
            <div class="card-professor">
                <h1>CONTA SORTEADA</h1>
                <h2>
                    <div class="resultado" id="resultado-sorteio"></div>
                </h2>
                <!--Resultado esperado: <h3>4000</h3>-->
                <button class="btn-login" id="botao-sortear">Sortear</button>
                <br>
                <h1>CONTAS SORTEADAS</h1><br>
                <div class="tabela-com-rolagem">
                    <table id="tabela_contas" class="tabela-resultado">
                        <thead>
                            <tr>
                                <th>Conta</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="right-login">
            <div class="card-professor">
                <h1>ALUNOS & RESULTADOS</h1><br>
                <h3>
                    <ul id="listausuarios"></ul>
                </h3>
                <div class="tabela-com-rolagem">
                    <table id="tabela_usuarios" class="tabela-alunos">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Acertos</th>
                                <th>Erros</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <br><h1>CONFIGURAÇÕES</h1>
                <div class="toggle">
                    <label class="switch">
                        <input type="checkbox" id="toggle-button">
                        <span class="slider round"></span>
                    </label>
                    <h3 style="color: white; font-weight: bold; margin-left: 10px;">
                        <p id="status">Não mostrar vídeo aula</p>
                    </h3>
                </div>
                <input type="hidden" name="status" id="data-status">
            </div>
        </div>
    </div>
</body>

</html>