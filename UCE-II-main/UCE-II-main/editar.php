<?php
session_start();

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION["administrador"])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: administradores.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include "conexao.php";

// Inicializa as variáveis para armazenar os dados do aluno
$matricula = $nome = $telefone = $endereco = $faculdade = $rota = "";

// Verifica se a matrícula foi passada pela URL
if (isset($_GET["matricula"])) {
    $matricula = $_GET["matricula"];
    
    // Consulta SQL para obter os detalhes do aluno
    $query = "SELECT * FROM estudantes WHERE MATRICULA = '$matricula'";
    $result = $mysqli->query($query);
    
    // Verifica se a consulta foi bem-sucedida
    if ($result) {
        // Verifica se o aluno foi encontrado
        if ($result->num_rows > 0) {
            // Obtém os dados do aluno
            $aluno = $result->fetch_assoc();
            $nome = $aluno["nome"];
            $telefone = $aluno["TELEFONE"];
            $endereco = $aluno["ENDERECO_RUA"];
            $faculdade = $aluno["FACULDADE_LOCAL"];
            $rota = $aluno["ROTAS_EST"];
        }
    } else {
        // Se a consulta falhar, exibe uma mensagem de erro
        echo "Erro ao consultar aluno: " . $mysqli->error;
        exit();
    }
}

// Processa a atualização dos dados do aluno se o formulário for submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST["matricula"];
    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];
    $faculdade = $_POST["faculdade"];
    $rota = $_POST["rota"];

    // Consulta SQL para atualizar os dados do aluno
    $query = "UPDATE estudantes SET 
              nome = '$nome', 
              TELEFONE = '$telefone', 
              ENDERECO_RUA = '$endereco', 
              FACULDADE_LOCAL = '$faculdade', 
              ROTAS_EST = '$rota' 
              WHERE MATRICULA = '$matricula'";

    // Executa a consulta
    if ($mysqli->query($query)) {
        // Redireciona de volta para a página de consulta após a atualização
        header("Location: consultar.php");
        exit();
    } else {
        // Se a atualização falhar, exibe uma mensagem de erro
        echo "Erro ao atualizar aluno: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
</head>
<body>
<header>
    <div>
        <h1>Editar Aluno</h1>
    </div>
    <div>
        <a href="consultar.php">Voltar</a>
    </div>
</header>

<main>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="matricula" value="<?php echo $matricula; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" required><br>
        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" value="<?php echo $telefone; ?>" required><br>
        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" value="<?php echo $endereco; ?>" required><br>
        <label for="faculdade">Faculdade/Local:</label>
        <input type="text" name="faculdade" id="faculdade" value="<?php echo $faculdade; ?>" required><br>
        <label for="rota">Rota:</label>
        <input type="text" name="rota" id="rota" value="<?php echo $rota; ?>" required><br>
        <input type="submit" value="Salvar">
        <a href="consultar.php"><button type="button">Voltar</button></a>
    </form>
</main>
</body>
</html>
