<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Criar Sala</title>
    <link rel="icon" type="image/png" href="../favicon.png">
</head>

<body>
    <form method="POST" action="../model/createroom.php">
        <div class="main-login">
            <div class="left-create">
                <div class="card-login">
                    <h1>ENTRAR NA SALA </h1>
                    <div class="textfield">
                        <label for="senha">Usuário</label>
                        <input type="text" name="senha" placeholder="Usuário">
                    </div>
                    <button type="submit" class="btn-login">ENTRAR</button>
                </div>
            </div>
            <div class="right-create">
                <img src="../img/bingo3.svg" class="left-login-image" alt="Bingo">
            </div>
        </div>
    </form>
</body>
<script>
    function gerarCodigo() {
        var codigo = "";
        var caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for (var i = 0; i < 6; i++) {
            codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        var codigoComSeparador = codigo.slice(0, 3) + "-" + codigo.slice(3);
        document.getElementById("codigo").value = codigoComSeparador;
    }

    function copiarTexto() {
        var campoDeTexto = document.getElementById("codigo");
        campoDeTexto.select();

        navigator.clipboard.writeText(campoDeTexto.value).then(function() {

            var balao = document.createElement("div");
            balao.classList.add("balao");
            balao.textContent = "Texto copiado!";
            campoDeTexto.parentNode.appendChild(balao);
            setTimeout(function() {
                balao.classList.remove("mostrar");
                setTimeout(function() {
                    campoDeTexto.parentNode.removeChild(balao);
                }, 300);
            }, 2000);
            setTimeout(function() {
                balao.classList.add("mostrar");
            }, 50);
        });
    }

    gerarCodigo();

    function copyToClipboard() {
        var url = "localhost/Bingo/alunos?codigo=" + codigoComSeparador + "&usuario="; // URL que será copiada
        var el = document.createElement('textarea'); // Cria um elemento de texto temporário
        el.value = url; // Define o valor do elemento de texto para a URL
        el.setAttribute('readonly', ''); // Define o elemento de texto como somente leitura para impedir edição
        el.style.position = 'absolute'; // Define a posição do elemento de texto como absoluta
        el.style.left = '-9999px'; // Move o elemento de texto para fora da tela
        document.body.appendChild(el); // Adiciona o elemento de texto à página
        el.select(); // Seleciona o conteúdo do elemento de texto
        document.execCommand('copy'); // Copia o conteúdo selecionado para a área de transferência
        document.body.removeChild(el); // Remove o elemento de texto da página
        alert("A URL foi copiada para a área de transferência."); // Exibe uma mensagem de confirmação
    }
</script>

</html>