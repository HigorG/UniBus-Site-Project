<?php
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estudante_id = $_POST["estudante_id"];
    $rota_id = $_POST["rota_id"];
    $dia_semana = $_POST["dia_semana"];

    $query = "DELETE FROM estudante WHERE id='$estudante_id'";

    if ($mysqli->query($query) === TRUE) {
        header("Location: visualizar.php?rota_id=$rota_id&dia_semana=$dia_semana");
        exit();
    } else {
        echo "Erro ao excluir estudante: " . $mysqli->error;
    }
}
?>
