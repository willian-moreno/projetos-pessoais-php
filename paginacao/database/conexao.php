<?php

$driver = 'mysql';
$host = 'localhost';
$port = '3306';
$dbname = 'paginacao_php';
$username = 'root';
$password = 'moreno';

$dns = "$driver:host=$host;port=$port;dbname=$dbname";
$username = "root";
$password = "moreno";

try {
    $pdo = new \PDO($dns, $username, $password);

    /** 
     * Executar as próximas linhas, para criar a tabela e inserir os dados.
     * Após criar e inserir, é necessário comentar/excluir essas linhas;
     */

    /* <- Remover na primeira vez

    $tables = $pdo->prepare(file_get_contents('../database/usuario.sql'));
    $tables->execute();

    for ($i = 1; $i <= 100; $i++) {
        $insert = $pdo->prepare("insert into usuario (nome) values (?)");
        $insert->execute(["Usuario $i"]);
    }
    
    Remover na primeira vez -> */ 
} catch (\Throwable $th) {
    echo '<pre>' . print_r($th) . '</pre>';
}
