<?php
session_start();

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION["organizador"])) {
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

$usuario = $_SESSION["organizador"];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Início</title>
</head>
<body>
    <header>
        <div>
            <h1>Tela Inicial</h1>
            <h3>Olá, <?php echo $usuario; ?> </h3>
        </div>
        <div>
            <a href="?logout=1">Sair</a> <!-- Link para sair -->
            <a href="administradores.php">Administradores</a> <!-- Link para a página de administradores -->
            <a href="consultar.php">Consultar</a> <!-- Link para a página de consulta -->
            <a href="cadastro.php">Cadastro</a> <!-- Link para a página de cadastro -->
        </div>
    </header>
</body>
</html>
