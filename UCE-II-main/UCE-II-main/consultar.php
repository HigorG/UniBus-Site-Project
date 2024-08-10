<?php
session_start();

// Verifica se o usuário está logado como administrador ou organizador
if (!isset($_SESSION["administrador"]) && !isset($_SESSION["organizador"])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: administradores.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include "conexao.php";

// Inicializa a variável $alunos
$alunos = [];

// Verifica se uma rota foi selecionada
$rota_selecionada = isset($_GET['rota_id']) ? $_GET['rota_id'] : '';
$dia_selecionado = isset($_GET['dia_semana']) ? $_GET['dia_semana'] : '';

// Verifica se houve uma solicitação de consulta
if (isset($_POST["consultar"])) {
    // Consulta todos os alunos cadastrados
    $query = "SELECT * FROM estudantes"; // Consulta ajustada para a tabela estudantes
    $result = $mysqli->query($query);

    // Verifica se a consulta foi bem-sucedida
    if ($result) {
        // Verifica se há alunos cadastrados
        if ($result->num_rows > 0) {
            // Obtém os alunos e armazena no array
            while ($row = $result->fetch_assoc()) {
                $alunos[] = $row;
            }
        }
    } else {
        // Se a consulta falhar, exibe uma mensagem de erro
        echo "Erro ao consultar alunos: " . $mysqli->error;
    }
}

// Processa a exclusão do aluno se houver uma solicitação
if (isset($_GET["excluir"])) {
    $matricula = $_GET["excluir"];
    // Query para excluir o aluno com base na matrícula
    $query_excluir = "DELETE FROM estudantes WHERE MATRICULA = '$matricula'";
    // Executa a query
    if ($mysqli->query($query_excluir)) {
        // Redireciona de volta para esta página para atualizar a exibição
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        // Se a exclusão falhar, exibe uma mensagem de erro
        echo "Erro ao excluir aluno: " . $mysqli->error;
    }
}

// Processa a adição do aluno se houver uma solicitação
if (isset($_POST["adicionar"])) {
    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];
    $faculdade = $_POST["faculdade"];
    $rota_id = $_POST["rota_id"];
    $dia_semana = $_POST["dia_semana"];
    $hora_saida = $_POST["hora_saida"];
    $hora_chegada = $_POST["hora_chegada"];

    $query_adicionar = "INSERT INTO estudantes (nome, MATRICULA, TELEFONE, ENDERECO_RUA, FACULDADE_LOCAL, ROTAS_EST) 
                        VALUES ('$nome', '$matricula', '$telefone', '$endereco', '$faculdade', '$rota_id')";

    if ($mysqli->query($query_adicionar)) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Erro ao adicionar aluno: " . $mysqli->error;
    }
}

// Processa a edição do aluno se houver uma solicitação
if (isset($_POST["editar"])) {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];
    $faculdade = $_POST["faculdade"];
    $rota_id = $_POST["rota_id"];
    $dia_semana = $_POST["dia_semana"];
    $hora_saida = $_POST["hora_saida"];
    $hora_chegada = $_POST["hora_chegada"];

    $query_editar = "UPDATE estudantes 
                     SET nome='$nome', MATRICULA='$matricula', TELEFONE='$telefone', ENDERECO_RUA='$endereco', FACULDADE_LOCAL='$faculdade', ROTAS_EST='$rota_id'
                     WHERE id='$id'";

    if ($mysqli->query($query_editar)) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Erro ao editar aluno: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">UNIBUS</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="?logout=1">Sair</a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="administradores.php">
                            <span data-feather="users"></span>
                            Administradores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultar.php">
                            <span data-feather="search"></span>
                            Consultar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro.php">
                            <span data-feather="file-plus"></span>
                            Cadastro
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rotas.php">
                            <span data-feather="map"></span>
                            Rotas
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Consulta de Alunos</h1>
                <div class="btn-toolbar mb-2 mb-md-0"></div>
            </div>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4">
                <button type="submit" name="consultar" class="btn btn-primary">Consultar</button>
            </form>

            <?php if (isset($_POST["consultar"])) { ?>
                <?php if (!empty($alunos)) { ?>
                    <h2>Alunos Cadastrados</h2>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Endereço</th>
                            <th>Faculdade/Local</th>
                            <th>Rotas</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($alunos as $aluno) { ?>
                            <tr>
                                <td><?php echo $aluno['MATRICULA']; ?></td>
                                <td><?php echo $aluno['nome']; ?></td>
                                <td><?php echo $aluno['TELEFONE']; ?></td>
                                <td><?php echo $aluno['ENDERECO_RUA']; ?></td>
                                <td><?php echo $aluno['FACULDADE_LOCAL']; ?></td>
                                <td><?php echo $aluno['ROTAS_EST']; ?></td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="<?php echo $aluno['id']; ?>" data-nome="<?php echo $aluno['nome']; ?>" data-matricula="<?php echo $aluno['MATRICULA']; ?>" data-telefone="<?php echo $aluno['TELEFONE']; ?>" data-endereco="<?php echo $aluno['ENDERECO_RUA']; ?>" data-faculdade="<?php echo $aluno['FACULDADE_LOCAL']; ?>" data-rota="<?php echo $aluno['ROTAS_EST']; ?>">Editar</button>
                                    <a href="?excluir=<?php echo $aluno['MATRICULA']; ?>" class="btn btn-danger">Excluir</a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <td><input type="text" name="matricula" class="form-control" placeholder="Matrícula" required></td>
                                <td><input type="text" name="nome" class="form-control" placeholder="Nome" required></td>
                                <td><input type="text" name="telefone" class="form-control" placeholder="Telefone" required></td>
                                <td><input type="text" name="endereco" class="form-control" placeholder="Endereço" required></td>
                                <td><input type="text" name="faculdade" class="form-control" placeholder="Faculdade/Local" required></td>
                                <td><input type="text" name="rota_id" class="form-control" placeholder="Rota" required></td>
                                <td><button type="submit" name="adicionar" class="btn btn-success">Adicionar</button></td>
                            </form>
                        </tr>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>Nenhum aluno cadastrado.</p>
                <?php } ?>
            <?php } ?>
        </main>
    </div>
</div>

<!-- Modal para edição de estudante -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Estudante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar-id">
                    <div class="mb-3">
                        <label for="editar-nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="editar-nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-matricula" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" id="editar-matricula" name="matricula" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="editar-telefone" name="telefone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="editar-endereco" name="endereco" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-faculdade" class="form-label">Faculdade/Local</label>
                        <input type="text" class="form-control" id="editar-faculdade" name="faculdade" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-rota" class="form-label">Rota</label>
                        <input type="text" class="form-control" id="editar-rota" name="rota_id" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar" class="btn btn-primary">Salvar alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
    var editarModal = document.getElementById('editarModal');
    editarModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nome = button.getAttribute('data-nome');
        var matricula = button.getAttribute('data-matricula');
        var telefone = button.getAttribute('data-telefone');
        var endereco = button.getAttribute('data-endereco');
        var faculdade = button.getAttribute('data-faculdade');
        var rota = button.getAttribute('data-rota');

        var modalTitle = editarModal.querySelector('.modal-title');
        var modalBodyId = editarModal.querySelector('.modal-body #editar-id');
        var modalBodyNome = editarModal.querySelector('.modal-body #editar-nome');
        var modalBodyMatricula = editarModal.querySelector('.modal-body #editar-matricula');
        var modalBodyTelefone = editarModal.querySelector('.modal-body #editar-telefone');
        var modalBodyEndereco = editarModal.querySelector('.modal-body #editar-endereco');
        var modalBodyFaculdade = editarModal.querySelector('.modal-body #editar-faculdade');
        var modalBodyRota = editarModal.querySelector('.modal-body #editar-rota');

        modalTitle.textContent = 'Editar Estudante: ' + nome;
        modalBodyId.value = id;
        modalBodyNome.value = nome;
        modalBodyMatricula.value = matricula;
        modalBodyTelefone.value = telefone;
        modalBodyEndereco.value = endereco;
        modalBodyFaculdade.value = faculdade;
        modalBodyRota.value = rota;
    });
</script>
</body>
</html>
