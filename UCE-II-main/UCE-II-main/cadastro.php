<?php
include "conexao.php";
$mensagem = "";

function getRotas($mysqli) {
    $rotas = [];
    $query = "SELECT id, nome_rota FROM rota";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rotas[] = $row;
        }
    }
    return $rotas;
}

$rotas = getRotas($mysqli);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["cadastrar_estudante"])) {
        $nome_estudante = $mysqli->real_escape_string($_POST["nome_estudante"]);
        $matricula = $mysqli->real_escape_string($_POST["matricula"]);
        $telefone = $mysqli->real_escape_string($_POST["telefone"]);
        $endereco_rua = $mysqli->real_escape_string($_POST["endereco_rua"]);
        $faculdade_local = $mysqli->real_escape_string($_POST["faculdade_local"]);
        $rota_id = $mysqli->real_escape_string($_POST["rota_id"]);

        $query_estudante = "INSERT INTO estudante (nome, numero_matricula, telefone, endereco, faculdade, nome_rota) 
                            VALUES ('$nome_estudante', '$matricula', '$telefone', '$endereco_rua', '$faculdade_local', 
                                    (SELECT nome_rota FROM rota WHERE id = '$rota_id'))";

        if ($mysqli->query($query_estudante) === TRUE) {
            $mensagem = "Estudante cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar estudante: " . $mysqli->error;
        }

    } elseif (isset($_POST["cadastrar_rota"])) {
        $nome_rota = $mysqli->real_escape_string($_POST["nome_rota"]);

        // Verificar se a rota já existe
        $query_verifica_rota = "SELECT id FROM rota WHERE nome_rota = '$nome_rota'";
        $result_verifica_rota = $mysqli->query($query_verifica_rota);

        if ($result_verifica_rota->num_rows == 0) {
            $query_rota = "INSERT INTO rota (nome_rota) VALUES ('$nome_rota')";
            if ($mysqli->query($query_rota) === TRUE) {
                $rota_id = $mysqli->insert_id;
                if (isset($_POST["horarios"])) {
                    foreach ($_POST["horarios"] as $dia_semana => $horario) {
                        if (!empty($horario['hora_saida']) && !empty($horario['hora_chegada'])) {
                            $hora_saida = $mysqli->real_escape_string($horario['hora_saida']);
                            $hora_chegada = $mysqli->real_escape_string($horario['hora_chegada']);
                            $query_horario = "INSERT INTO horario (nome_rota, dias_semana, hora_saida, hora_chegada) 
                                              VALUES ('$nome_rota', '$dia_semana', '$hora_saida', '$hora_chegada')";
                            if (!$mysqli->query($query_horario)) {
                                $mensagem = "Erro ao cadastrar horário: " . $mysqli->error;
                            }
                        }
                    }
                }
                $mensagem = "Rota cadastrada com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar rota: " . $mysqli->error;
            }
        } else {
            $mensagem = "Erro: Rota já cadastrada!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Estudante e Rota</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #bbbbbb;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            font-family: system-ui, -apple-system, Helvetica, Arial, sans-serif;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            font-family: system-ui, -apple-system, Helvetica, Arial, sans-serif;
            background: #000000;
            height: 8vh;
        }

        nav h2 {
            color: #fff;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .nav-list {
            list-style: none;
            display: flex;
        }

        .nav-list a {
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .nav-list a:hover {
            opacity: 0.7;
        }

        .nav-list li {
            letter-spacing: 3px;
            margin-left: 32px;
        }

        @media (max-width: 999px) {
            body {
                overflow-x: hidden;
            }

            .nav-list {
                position: absolute;
                top: 8vh;
                right: 0;
                width: 50vw;
                height: 92vh;
                background: #23232e;
                flex-direction: column;
                align-items: center;
                justify-content: space-around;
                transform: translateX(100%);
                transition: transform 0.3s ease-in;
            }

            .nav-list li {
                margin-left: 0;
                opacity: 0;
            }
        }

        .nav-list.active {
            transform: translateX(0);
        }

        @keyframes navLinkFade {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-grow: 1;
            padding: 20px;
        }

        .form-container {
            background-color: #e88700;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 60%;
            display: none;
            margin-top: 20px;
        }

        .form-container h2 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container label {
            color: white;
            display: block;
            margin: 10px 0 5px;
        }

        .form-container input,
        .form-container select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }

        .form-container input[type="submit"] {
            background-color: #000000;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #444444;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .btn-group .btn {
            margin: 0 10px;
            padding: 15px 30px;
            border: none;
            background-color: #000000;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-group .btn:hover {
            background-color: #444444;
            transform: scale(1.05);
        }

        .message {
            font-weight: bold;
            color: white;
            text-align: center;
            margin-top: 20px;
        }

        .btn-primary {
            display: block;
            text-align: center;
            margin-top: 20px;
            background-color: #000000;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #444444;
            transform: scale(1.05);
        }

        .containe {
            max-width: 1170px;
            width: 100%;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        ul {
            list-style: none;
        }

        .footer {
            padding: 20px 0;
            background-color: #a0451e;
            width: 100%;
            margin-top: auto;
        }

        .footer-col {
            width: 25%;
            padding: 0 15px;
            box-sizing: border-box;
            float: left;
        }

        .footer-col h4 {
            font-size: 18px;
            color: #ffffff;
            text-transform: capitalize;
            margin-bottom: 35px;
            font-weight: 500;
            position: relative;
        }

        .footer-col h4::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            background-color: #120208;
            height: 2px;
            box-sizing: border-box;
            width: 50px;
        }

        .footer-col ul li:not(:last-child) {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            font-size: 16px;
            text-transform: capitalize;
            color: #ffffff;
            text-decoration: none;
            font-weight: 300;
            color: #bbbbbb;
            display: block;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover {
            color: #ffffff;
            padding-left: 8px;
        }

        .footer-col .social-links {
            text-align: left;
        }

        .footer-col .social-links a {
            display: inline-block;
            height: 40px;
            width: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            margin: 0 10px 10px 0;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            color: #ffffff;
            transition: all 0.5s ease;
        }

        .footer-col .social-links a:hover {
            color: #24262b;
            background-color: #ffffff;
        }

        @media(max-width: 767px) {
            .footer-col {
                width: 50%;
                margin-bottom: 30px;
            }
        }

        @media(max-width: 574px) {
            .footer-col {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script>
        function showForm(formId) {
            document.getElementById('estudanteForm').style.display = 'none';
            document.getElementById('rotaForm').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }
    </script>
</head>

<body>
    <header>
        <nav>
            <h2>UNIBUS</h2>
            <ul class="nav-list">
                <li><a href="administradores.php">Administradores</a></li>
                <li><a href="consultar.php">Consultar</a></li>
                <li><a href="cadastro.php">Cadastro</a></li>
                <li><a href="rotas.php">Rotas</a></li>
            </ul>
            <ul>
                <li><a style="color:white; text-decoration:none; width:20px;" href="?logout=1">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div class="btn-group" role="group" aria-label="Form Selection">
            <button type="button" class="btn" onclick="showForm('estudanteForm')">Cadastrar Estudante</button>
            <button type="button" class="btn" onclick="showForm('rotaForm')">Cadastrar Rota</button>
        </div>

        <div id="rotaForm" class="form-container">
            <h2>Cadastro de Rota</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="nome_rota">Nome da Rota:</label>
                <input type="text" id="nome_rota" name="nome_rota" required><br>
                <h3>Dias da Semana e Horários</h3>
                <?php
                $dias_semana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
                foreach ($dias_semana as $dia) {
                    echo '<label>' . $dia . ':</label>';
                    echo '<input type="checkbox" name="horarios[' . $dia . '][dia_semana]" value="' . $dia . '">';
                    echo '<input type="time" name="horarios[' . $dia . '][hora_saida]" placeholder="Hora de Saída">';
                    echo '<input type="time" name="horarios[' . $dia . '][hora_chegada]" placeholder="Hora de Chegada"><br>';
                }
                ?>
                <input type="submit" name="cadastrar_rota" value="Cadastrar Rota">
            </form>
        </div>

        <div id="estudanteForm" class="form-container">
            <h2>Cadastro de Estudante</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="nome_estudante">Nome do Estudante:</label>
                <input type="text" id="nome_estudante" name="nome_estudante" required><br>
                <label for="matricula">Matrícula:</label>
                <input type="text" id="matricula" name="matricula" required><br>
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required><br>
                <label for="endereco_rua">Endereço (Rua):</label>
                <input type="text" id="endereco_rua" name="endereco_rua" required><br>
                <label for="faculdade_local">Faculdade/Local:</label>
                <input type="text" id="faculdade_local" name="faculdade_local" required><br>
                <label for="rota_id">Rota:</label>
                <select id="rota_id" name="rota_id" required>
                    <?php foreach ($rotas as $rota): ?>
                        <option value="<?php echo $rota['id']; ?>"><?php echo $rota['nome_rota']; ?></option>
                    <?php endforeach; ?>
                </select><br>
                <input type="submit" name="cadastrar_estudante" value="Cadastrar Estudante">
            </form>
        </div>

        <?php if (!empty($mensagem)) { ?>
            <p class="message"><?php echo $mensagem; ?></p>
        <?php } ?>

        <a href="inicio.php" class="btn-primary">Voltar para o Início</a>
    </main>

    <footer class="footer">
        <div class="containe">
            <div class="row">
                <div class="footer-col">
                    <h4>company</h4>
                    <ul>
                        <li><a href="#">about us</a></li>
                        <li><a href="#">our services</a></li>
                        <li><a href="#">privacy policy</a></li>
                        <li><a href="#">affiliate program</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>get help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">shipping</a></li>
                        <li><a href="#">returns</a></li>
                        <li><a href="#">order status</a></li>
                        <li><a href="#">payment options</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>follow us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7/zJfLk+H3zt5l6P+hqlP3yKhJJgkpKSu7EjzL+AB1n0Z/3QUm0Hb8YRBIEI+1UJ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9H3cZw5eMpsjceus2/hkfz3GGaWlB46d5Yl7/heuo7oo6nQpBBF6JpC" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script>
        feather.replace();
    </script>
</body>

</html>

