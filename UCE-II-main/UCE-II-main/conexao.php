<?php
// Definindo as variáveis de conexão com o banco de dados
$host = "localhost"; // Endereço do servidor de banco de dados (geralmente "localhost")
$usuario = "root"; // Nome de usuário do banco de dados (geralmente "root")
$senha = ""; // Senha do banco de dados (deixe em branco ou coloque sua senha)
$bd = "db_onibus"; // Nome do banco de dados que você deseja usar

// Criando uma nova conexão com o banco de dados usando a classe mysqli
$mysqli = new mysqli($host, $usuario, $senha, $bd);

// Verificando se houve erro na conexão
if ($mysqli->connect_errno) {
  // Se houver erro, exibe uma mensagem com o código do erro
  echo "Falha na conexão: " . $mysqli->connect_errno;
}

?>
