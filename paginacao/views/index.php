<!doctype html>
<html lang="pt-br">

<head>
    <title>Paginação</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<?php
// Conexão com o banco
include_once('../database/conexao.php');
try {
    // Itens/registros por página
    $maximo_por_pagina = "10";
    // Setando o valor da página atual
    $pagina = isset($_GET['pagina']) ? ($_GET['pagina']) : '1';
    // Variavel com o valor da página começando em zero, visto que uma array começa com o índice zero
    $pagina_inicial = $pagina - 1;
    // Por meio do valor da página e da quantidade de registros, pode-se definir os registros que serão mostrados
    $pagina_inicial = $pagina_inicial * $maximo_por_pagina;
    // Contando a quantidade de registros existentes na tabela
    $contar_registros = $pdo->prepare("select count(*) as 'total_registros' from usuario");
    $contar_registros->execute();
    $contar_registros = $contar_registros->fetchAll();
    /** 
     * Iniciando uma varial 'total' que receberá a quantidade de registros
     * Obs.: Daria para utilizar a variavel 'contar_registros', mas como ela é uma array,
     * portanto ela deve ser percorrida com o foreach, para trazer somente o que é necessário.
     */
    $total = 0;
    if (count($contar_registros)) {
        foreach ($contar_registros as $retorno) {
            $total = $retorno["total_registros"];
        }
    }
    // Selecionando os usuários utilizando o 'limit' do sql
    $usuarios = $pdo->prepare("select * from usuario nome limit $pagina_inicial, $maximo_por_pagina");
    $usuarios->execute();
    $usuarios = $usuarios->fetchAll();
    // Variáveis para darem ação aos botões de 'Anterior' e 'Próximo'
    $anterior = $pagina - 1;
    $proximo = $pagina + 1;
    // Calculando qual será o valor da última página, para bloquear o botão próximo
    $pagina_final = ceil($total / $maximo_por_pagina);
    // Condições para desabilitar os botões
    if ($pagina == 1) {
        $btnAnterior = "<a href='?pagina=$anterior' type='button' class='btn btn-outline-secondary col-md-1 disabled'>Anterior</a>";
    } else {
        $btnAnterior = "<a href='?pagina=$anterior' type='button' class='btn btn-outline-secondary col-md-1'>Anterior</a>";
    }
    if ($pagina == $pagina_final) {
        $btnProximo = "<a href='?pagina=$proximo' type='button' class='btn btn-outline-secondary col-md-1 disabled'>Próximo</a>";
    } else {
        $btnProximo = "<a href='?pagina=$proximo' type='button' class='btn btn-outline-secondary col-md-1'>Próximo</a>";
    }
} catch (\Throwable $th) {
    echo '<pre>' . print_r($th) . '</pre>';
}
?>

<body>
    <div class="card m-4 border border-secondary table-sm">
        <div class="card-body">
            <h4 class="card-title">Lista de usuários</h4>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-1">ID</th>
                        <th class="col-11">Nome</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($usuarios)) {
                        foreach ($usuarios as $u => $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id'] . '</td>';
                            echo '<td>' . $value['nome'] . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center btn-group">
                <?php echo $btnAnterior ?>
                <?php echo $btnProximo ?>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="../assets/js/app.js"></script>
</body>

</html>