<?php
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $mysqli->real_escape_string($_POST["nome"]);
    $matricula = $mysqli->real_escape_string($_POST["matricula"]);
    $telefone = $mysqli->real_escape_string($_POST["telefone"]);
    $endereco = $mysqli->real_escape_string($_POST["endereco"]);
    $faculdade = $mysqli->real_escape_string($_POST["faculdade"]);
    $rota_id = $mysqli->real_escape_string($_POST["rota_id"]);
    $dia_semana = $mysqli->real_escape_string($_POST["dia_semana"]);
    $hora_saida = $mysqli->real_escape_string($_POST["hora_saida"]);
    $hora_chegada = $mysqli->real_escape_string($_POST["hora_chegada"]);

    $query = "INSERT INTO estudante (nome, numero_matricula, telefone, endereco, faculdade, nome_rota) 
              VALUES ('$nome', '$matricula', '$telefone', '$endereco', '$faculdade', 
                      (SELECT nome_rota FROM rota WHERE id='$rota_id'))";

    if ($mysqli->query($query) === TRUE) {
        header("Location: visualizar.php?rota_id=$rota_id&dia_semana=$dia_semana");
        exit();
    } else {
        echo "Erro ao adicionar estudante: " . $mysqli->error;
    }
}
?>
