<?php
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estudante_id = $_POST["estudante_id"];
    $nome = $mysqli->real_escape_string($_POST["nome"]);
    $matricula = $mysqli->real_escape_string($_POST["matricula"]);
    $telefone = $mysqli->real_escape_string($_POST["telefone"]);
    $endereco = $mysqli->real_escape_string($_POST["endereco"]);
    $faculdade = $mysqli->real_escape_string($_POST["faculdade"]);
    $rota_id = $mysqli->real_escape_string($_POST["rota_id"]);
    $dia_semana = $mysqli->real_escape_string($_POST["dia_semana"]);

    $query = "UPDATE estudante 
              SET nome='$nome', numero_matricula='$matricula', telefone='$telefone', endereco='$endereco', faculdade='$faculdade', nome_rota=(SELECT nome_rota FROM rota WHERE id='$rota_id')
              WHERE id='$estudante_id'";

    if ($mysqli->query($query) === TRUE) {
        header("Location: visualizar.php?rota_id=$rota_id&dia_semana=$dia_semana");
        exit();
    } else {
        echo "Erro ao editar estudante: " . $mysqli->error;
    }
}
?>
