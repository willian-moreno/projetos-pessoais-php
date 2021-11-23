<!doctype html>
<html lang="en">

<head>
    <title>Paginacao retorno JSON</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<?php
$polo = 1;
$url = "https://innovation.org.br/api/rm/dadosAluno/alunosPorPolo.php?codCampus=" . $polo;
$alunos_json = json_decode(file_get_contents($url));
$alunos_array = json_decode(file_get_contents($url), true);

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

$total_itens = count($alunos_array);
$per_page = 10;
$total_pages = ceil($total_itens / $per_page) - 1;
$current_page = isset($_GET['page']) ? $_GET['page'] : 0;
$itens = $current_page == 0 ? 0 : $current_page * $per_page;
$first_item = ($itens + 1) > $total_itens ? $total_itens : ($itens + 1);
$last_item = ($itens + 10) > $total_itens ? $total_itens : ($itens + 10);
$array = array_slice($alunos_json, $itens, $per_page, true);

if (!empty($pesquisa)) {
    foreach ($array as $key => $value) {
        if (!($value->Ra == $pesquisa)) {
            continue;
        } else {
            $array = array($key => $value);
            $total_itens = count($array);
            $per_page = 0;
            $total_pages = 0;
            $current_page = 0;
            $itens = 1;
            $first_item = 1;
            $last_item = 1;
        }
    }
}
?>

<body>

    <div class="card m-3">
        <div class="card-body">
            <div class="d-flex">
                <form action="index.php?page=<?php echo 0; ?>&pesquisa=<?php echo urldecode($pesquisa); ?>" method="get" class="ms-auto">
                    <div class="input-group w-auto ms-auto mb-3">
                        <input class="form-control rounded-start py-0" id="pesquisa" name="pesquisa" type="search" placeholder="Pesquisar" value="<?php echo $pesquisa; ?>">
                        <button class="btn btn-primary" type="submit">
                            Pesquisar
                        </button>
                    </div>
                </form>
            </div>
            <table class="table table-responsive table-sm">
                <thead>
                    <tr>
                        <th class="col-2">Nome</th>
                        <th class="col-2">RA</th>
                        <th class="col-2">Per. Letivo</th>
                        <th class="col-2">Turma</th>
                        <th class="col-2">Status</th>
                        <th class="col-2">Tipo Matrícula</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($array as $key => $aluno) {
                        echo "<tr>";
                        echo "<td>$aluno->Nome</td>";
                        echo "<td>$aluno->Ra</td>";
                        echo "<td>$aluno->PeriodoLetivo</td>";
                        echo "<td>$aluno->Curso</td>";
                        echo "<td>$aluno->StatusMatricula</td>";
                        echo "<td>$aluno->TipoMatricula</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="mx-auto m-2">
                <h6 class="text-center my-2">
                    Listando entre <?php echo $first_item ?> e <?php echo $last_item ?> de <?php echo $total_itens ?> resultados.
                </h6>
                <h6 class="text-center my-2">
                    Página <?php echo $current_page ?> de <?php echo $total_pages ?> páginas.
                </h6>
            </div>
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="">
                    <a href="index.php?page=<?php echo 0; ?>" type="button" class="btn btn-primary 
                        <?php if ($current_page == 0) echo 'disabled' ?>">
                        Primeira
                    </a>
                    <a href="index.php?page=<?php echo $current_page - 1; ?>" type="button" class="btn btn-primary 
                        <?php if ($current_page == 0) echo 'disabled' ?>">
                        Anterior
                    </a>
                    <a href="index.php?page=<?php echo $current_page + 1; ?>" type="button" class="btn btn-primary
                        <?php if ($current_page == $total_pages) echo 'disabled' ?>">
                        Próximo
                    </a>
                    <a href="index.php?page=<?php echo $total_pages ?>" type="button" class="btn btn-primary
                        <?php if ($current_page == $total_pages) echo 'disabled' ?>">
                        Última
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>