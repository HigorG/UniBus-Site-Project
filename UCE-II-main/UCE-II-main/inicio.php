<?php
session_start();

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION["administrador"])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: administradores.php");
    exit();
}

// Se houver solicitação de logout, encerra a sessão e redireciona para a página de login
if (isset($_GET["logout"])) {
    session_destroy(); // Destroi todas as informações registradas da sessão
    header("Location: administradores.php"); // Redireciona para a página de login após sair
    exit();
}

$usuario = $_SESSION["administrador"];

// Inclui o arquivo de conexão com o banco de dados
include "conexao.php";

// Consulta para obter a quantidade de alunos cadastrados
$query = "SELECT COUNT(*) AS total_alunos FROM estudante"; // Verifique se a tabela é 'estudante'
$result = $mysqli->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $total_alunos = $row['total_alunos'];
} else {
    $total_alunos = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Início</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            margin: 0;
            padding: 0;
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
            text-decoration: solid;
        }

        /* NAV BAR */
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

        /* FIM NAV BAR */
        /* FOOTER */
        .containe {
            max-width: 1170px;
            width: 100%;
            margin: 0 auto;
            /* centralizar o conteúdo */
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
            /* Garantir que o padding não afete a largura total */
            float: left;
            /* Colocar as colunas lado a lado */
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

        /*responsive footer*/
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

        /* DASHBOARD STYLES */
        .dashboard {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50vh;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }

        .dashboard h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .dashboard .card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard .card h3 {
            font-size: 36px;
            color: #333;
        }

        .dashboard .card p {
            font-size: 18px;
            color: #666;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

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

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tela Inicial</h1>
            <div class="btn-toolbar mb-2 mb-md-0"></div>
        </div>
        <h3>Olá, <?php echo htmlspecialchars($usuario); ?> </h3>

        <div class="dashboard">
            <h2>Dashboard</h2>
            <div class="card">
                <h3><?php echo $total_alunos; ?></h3>
                <p>Alunos Cadastrados</p>
            </div>
        </div>
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
</body>
</html>
