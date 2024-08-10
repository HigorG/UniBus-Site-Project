<?php
session_start();

// Verifica se o usuário já está logado como administrador
if (isset($_SESSION["administrador"])) {
    // Se estiver logado como administrador, redireciona para a página inicial do administrador
    header("Location: inicio.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include "conexao.php";

// Processamento do formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $mysqli->real_escape_string($_POST["usuario"]);
    $senha = $mysqli->real_escape_string($_POST["senha"]);

    // Consulta SQL para verificar se o usuário e a senha correspondem a um registro na tabela de administradores
    $query_admin = "SELECT * FROM administrador WHERE USUARIO_ADM = '$usuario' AND SENHA_ADM = '$senha'";
    $resultado_admin = $mysqli->query($query_admin);

    if ($resultado_admin->num_rows == 1) {
        // Se as credenciais estiverem corretas para o administrador, inicia uma sessão para o administrador
        $_SESSION["administrador"] = $usuario;
        // Redireciona para a página inicial do administrador
        header("Location: inicio.php");
        exit();
    } else {
        // Se as credenciais estiverem incorretas, exibe uma mensagem de erro
        $erro = "Usuário ou senha incorretos.";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Link para Font Awesome -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tela de login moderna">
    <meta name="author" content="Seu Nome">
    <title>Login</title>
    <style>
        body {
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #000000;
            font-family: 'Roboto', sans-serif;
        }
        .form-signin {
            background: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .form-signin .bus-icon {
            font-size: 72px;
            margin-bottom: 1rem;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-control {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            color: #000000;
        }
        .form-control::placeholder {
            color: #000000;
        }
        .form-control:focus {
            background: rgba(0, 0, 0, 0.2);
            border-color: #000000;
            box-shadow: none;
            color: #000000;
        }
        .form-floating label {
            color: #000000;
        }
        .btn-primary {
            background: #000000;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #333333;
        }
        .alert {
            margin-top: 1rem;
            background: rgba(255, 0, 0, 0.7);
            border: none;
            color: #ffffff;
        }
        .text-muted {
            color: #000000 !important;
        }
    </style>
</head>
<body>
<main class="form-signin">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="bus-icon">
            <i class="fas fa-bus"></i> <!-- Ícone de ônibus -->
        </div>
        <h1 class="h3 mb-3 fw-normal">Login</h1>

        <div class="form-floating">
            <input type="text" class="form-control" id="floatingUsuario" name="usuario" placeholder="Digite seu usuário" required>
            <label for="floatingUsuario">Usuário</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="senha" placeholder="Digite sua senha" required>
            <label for="floatingPassword">Senha</label>
        </div>

        <?php if (isset($erro)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $erro; ?>
            </div>
        <?php } ?>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="confirmar">Entrar</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2024</p>
    </form>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script> <!-- Script para Font Awesome -->
</body>
</html>
