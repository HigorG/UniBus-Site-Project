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

// Inicializa as variáveis
$alunos = [];
$mensagem = "";

// Verifica se uma rota foi selecionada
$rota_selecionada = isset($_GET['rota_id']) ? $_GET['rota_id'] : '';
$dia_selecionado = isset($_GET['dia_semana']) ? $_GET['dia_semana'] : '';

// Consulta SQL
$query = "SELECT e.id AS estudante_id, e.nome, e.numero_matricula AS MATRICULA, e.telefone AS TELEFONE, 
                 e.endereco AS ENDERECO_RUA, e.faculdade AS FACULDADE_LOCAL, 
                 r.id AS rota_id, r.nome_rota AS COD_ROTAS, 
                 h.dias_semana, h.hora_saida, h.hora_chegada
          FROM estudante e
          INNER JOIN rota r ON e.nome_rota = r.nome_rota
          INNER JOIN horario h ON r.nome_rota = h.nome_rota";

if ($rota_selecionada) {
    $query .= " WHERE r.id = '$rota_selecionada'";
    if ($dia_selecionado) {
        $query .= " AND h.dias_semana = '$dia_selecionado'";
    }
} elseif ($dia_selecionado) {
    $query .= " WHERE h.dias_semana = '$dia_selecionado'";
}

$resultado = $mysqli->query($query);

// Verifica se houve erro na consulta
if (!$resultado) {
    echo "Erro na consulta: " . $mysqli->error;
    exit();
}

// Armazena os resultados da consulta
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $faculdade = isset($row['FACULDADE']) ? $row['FACULDADE'] : 'Faculdade não definida';
        $alunos[$row['COD_ROTAS']][$row['dias_semana']][$row['hora_saida']][$row['hora_chegada']][] = [
            'id' => $row['estudante_id'],
            'nome' => $row['nome'],
            'MATRICULA' => $row['MATRICULA'],
            'TELEFONE' => $row['TELEFONE'],
            'ENDERECO_RUA' => $row['ENDERECO_RUA'],
            'FACULDADE_LOCAL' => $row['FACULDADE_LOCAL'],
            'FACULDADE' => $faculdade,
            'rota_id' => $row['rota_id']
        ];
    }
}

// Processa a exclusão do aluno se houver uma solicitação
if (isset($_POST["excluir"])) {
    $id = $_POST["estudante_id"];
    // Query para excluir o aluno com base no ID
    $query_excluir = "DELETE FROM estudante WHERE id = '$id'";
    // Executa a query
    if ($mysqli->query($query_excluir)) {
        $mensagem = "Aluno excluído com sucesso!";
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    } else {
        $mensagem = "Erro ao excluir aluno: " . $mysqli->error;
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

    $query_adicionar = "INSERT INTO estudante (nome, numero_matricula, telefone, endereco, faculdade, nome_rota) 
                        VALUES ('$nome', '$matricula', '$telefone', '$endereco', '$faculdade', (SELECT nome_rota FROM rota WHERE id = '$rota_id'))";

    if ($mysqli->query($query_adicionar)) {
        $mensagem = "Aluno adicionado com sucesso!";
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    } else {
        $mensagem = "Erro ao adicionar aluno: " . $mysqli->error;
    }
}

// Processa a edição do aluno se houver uma solicitação
if (isset($_POST["editar"])) {
    $id = $_POST["estudante_id"];
    $nome = $_POST["nome"];
    $matricula = $_POST["matricula"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];
    $faculdade = $_POST["faculdade"];
    $rota_id = $_POST["rota_id"];
    $dia_semana = $_POST["dia_semana"];
    $hora_saida = $_POST["hora_saida"];
    $hora_chegada = $_POST["hora_chegada"];

    $query_editar = "UPDATE estudante 
                     SET nome='$nome', numero_matricula='$matricula', telefone='$telefone', endereco='$endereco', faculdade='$faculdade', nome_rota=(SELECT nome_rota FROM rota WHERE id='$rota_id')
                     WHERE id='$id'";

    if ($mysqli->query($query_editar)) {
        $mensagem = "Aluno editado com sucesso!";
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    } else {
        $mensagem = "Erro ao editar aluno: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Visualização de Alunos por Rota</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4 {
            font-family: system-ui, -apple-system, Helvetica, Arial, sans-serif;
        }

        /* NAV BAR */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: system-ui, -apple-system, Helvetica, Arial, sans-serif;
            background: #000;
            padding: 10px 20px;
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
            margin-left: 32px;
        }

        .nav-list a:hover {
            opacity: 0.7;
        }

        /* FOOTER */
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
            color: #fff;
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
            color: #bbb;
            text-decoration: none;
            font-weight: 300;
            display: block;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover {
            color: #fff;
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
            color: #fff;
            transition: all 0.5s ease;
        }

        .footer-col .social-links a:hover {
            color: #24262b;
            background-color: #fff;
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
                <li><a style="color:white; text-decoration:none;" href="?logout=1">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Visualização de Alunos por Rota</h1>
        </div>

        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rota_id" class="form-label">Selecione a Rota:</label>
                    <select name="rota_id" id="rota_id" class="form-select">
                        <?php
                        $query_rotas = "SELECT id, nome_rota FROM rota";
                        $result_rotas = $mysqli->query($query_rotas);
                        if ($result_rotas->num_rows > 0) {
                            while ($rota = $result_rotas->fetch_assoc()) {
                                $selected = $rota_selecionada == $rota['id'] ? 'selected' : '';
                                echo "<option value='{$rota['id']}' $selected>{$rota['nome_rota']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dia_semana" class="form-label">Selecione o Dia da Semana:</label>
                    <select name="dia_semana" id="dia_semana" class="form-select">
                        <option value="">Todos os Dias</option>
                        <option value="Segunda" <?php echo $dia_selecionado == 'Segunda' ? 'selected' : ''; ?>>Segunda</option>
                        <option value="Terça" <?php echo $dia_selecionado == 'Terça' ? 'selected' : ''; ?>>Terça</option>
                        <option value="Quarta" <?php echo $dia_selecionado == 'Quarta' ? 'selected' : ''; ?>>Quarta</option>
                        <option value="Quinta" <?php echo $dia_selecionado == 'Quinta' ? 'selected' : ''; ?>>Quinta</option>
                        <option value="Sexta" <?php echo $dia_selecionado == 'Sexta' ? 'selected' : ''; ?>>Sexta</option>
                        <option value="Sábado" <?php echo $dia_selecionado == 'Sábado' ? 'selected' : ''; ?>>Sábado</option>
                        <option value="Domingo" <?php echo $dia_selecionado == 'Domingo' ? 'selected' : ''; ?>>Domingo</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <?php if (!empty($alunos)) { ?>
            <?php foreach ($alunos as $cod_rota => $dias) { ?>
                <div class="mb-4">
                    <h2>Rota: <?php echo $cod_rota; ?></h2>
                    <?php foreach ($dias as $dia => $horarios) { ?>
                        <h3>Dia: <?php echo $dia; ?></h3>
                        <?php foreach ($horarios as $hora_saida => $hora_chegadas) { ?>
                            <?php foreach ($hora_chegadas as $hora_chegada => $alunos_horario) { ?>
                                <h4>Horário: <?php echo "$hora_saida - $hora_chegada"; ?></h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Matrícula</th>
                                                <th>Telefone</th>
                                                <th>Endereço</th>
                                                <th>Faculdade/Local</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($alunos_horario as $aluno) { ?>
                                                <tr>
                                                    <td><?php echo $aluno['nome']; ?></td>
                                                    <td><?php echo $aluno['MATRICULA']; ?></td>
                                                    <td><?php echo $aluno['TELEFONE']; ?></td>
                                                    <td><?php echo $aluno['ENDERECO_RUA']; ?></td>
                                                    <td><?php echo $aluno['FACULDADE_LOCAL']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="<?php echo $aluno['id']; ?>" data-nome="<?php echo $aluno['nome']; ?>" data-matricula="<?php echo $aluno['MATRICULA']; ?>" data-telefone="<?php echo $aluno['TELEFONE']; ?>" data-endereco="<?php echo $aluno['ENDERECO_RUA']; ?>" data-faculdade="<?php echo $aluno['FACULDADE_LOCAL']; ?>" data-rota="<?php echo $aluno['rota_id']; ?>">Editar</button>
                                                        <form style="display:inline;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                            <input type="hidden" name="estudante_id" value="<?php echo $aluno['id']; ?>">
                                                            <input type="hidden" name="excluir" value="1">
                                                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                    <input type="hidden" name="adicionar" value="1">
                                                    <input type="hidden" name="rota_id" value="<?php echo $alunos_horario[0]['rota_id']; ?>">
                                                    <input type="hidden" name="dia_semana" value="<?php echo $dia; ?>">
                                                    <input type="hidden" name="hora_saida" value="<?php echo $hora_saida; ?>">
                                                    <input type="hidden" name="hora_chegada" value="<?php echo $hora_chegada; ?>">
                                                    <td><input type="text" name="nome" class="form-control form-control-sm" placeholder="Nome"></td>
                                                    <td><input type="text" name="matricula" class="form-control form-control-sm" placeholder="Matrícula"></td>
                                                    <td><input type="text" name="telefone" class="form-control form-control-sm" placeholder="Telefone"></td>
                                                    <td><input type="text" name="endereco" class="form-control form-control-sm" placeholder="Endereço"></td>
                                                    <td><input type="text" name="faculdade" class="form-control form-control-sm" placeholder="Faculdade/Local"></td>
                                                    <td><button type="submit" class="btn btn-success btn-sm">Adicionar</button></td>
                                                </form>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Nenhum aluno cadastrado.</p>
        <?php } ?>
        <a href="inicio.php" class="btn btn-secondary mt-4">Voltar</a>
    </main>

    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Estudante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="modal-body">
                        <input type="hidden" name="estudante_id" id="editar-id">
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>
    <script>
        feather.replace();

        // Script para preencher o modal de edição com os dados do estudante
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

            var modalId = editarModal.querySelector('#editar-id');
            var modalNome = editarModal.querySelector('#editar-nome');
            var modalMatricula = editarModal.querySelector('#editar-matricula');
            var modalTelefone = editarModal.querySelector('#editar-telefone');
            var modalEndereco = editarModal.querySelector('#editar-endereco');
            var modalFaculdade = editarModal.querySelector('#editar-faculdade');
            var modalRota = editarModal.querySelector('#editar-rota');

            modalId.value = id;
            modalNome.value = nome;
            modalMatricula.value = matricula;
            modalTelefone.value = telefone;
            modalEndereco.value = endereco;
            modalFaculdade.value = faculdade;
            modalRota.value = rota;
        });
    </script>
</body>

</html>
