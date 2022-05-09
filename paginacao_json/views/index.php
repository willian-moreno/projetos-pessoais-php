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
function concatZero($i)
{
    if ($i >= 0 && $i < 10) {
        return '0' . $i;
    } else {
        return $i;
    }
}

$json = json_decode(getJSON());
$array = json_decode(getJSON(), true);

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

if (!empty($pesquisa)) {
    $arr = [];
    foreach ($array as $key => $value) {
        if (
            (!empty(strstr($value['name'], $pesquisa))) or
            (!empty(strstr($value['email'], $pesquisa)))
        ) {
            array_push($arr, (object)$value);
        } else {
            continue;
        }
    }
    $total_itens = count($arr);
    $per_page = 10;
    $total_pages = ceil($total_itens / $per_page) - 1 >= 0 ? ceil($total_itens / $per_page) - 1 : ceil($total_itens / $per_page);
    $current_page = 0;
    $itens = $current_page == 0 ? 0 : $current_page * $per_page;
    $first_item = ($itens + 1) > $total_itens ? $total_itens : ($itens + 1);
    $last_item = ($itens + $per_page) > $total_itens ? $total_itens : ($itens + $per_page);
    $array = $arr;
} else {
    $total_itens = count($array);
    $per_page = 10;
    $total_pages = ceil($total_itens / $per_page) - 1;
    $current_page = isset($_GET['page']) ? $_GET['page'] : 0;
    $itens = $current_page == 0 ? 0 : $current_page * $per_page;
    $first_item = ($itens + 1) > $total_itens ? $total_itens : ($itens + 1);
    $last_item = ($itens + $per_page) > $total_itens ? $total_itens : ($itens + $per_page);
    $array = array_slice($json, $itens, $per_page, true);
}

?>

<body>

    <div class="card m-3">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <form action="index.php?page=<?php echo 0; ?>&pesquisa=<?php echo urldecode($pesquisa); ?>" method="get" class="ms-auto col-5">
                    <div class="input-group mb-3">
                        <input class="form-control rounded-start py-0" id="pesquisa" name="pesquisa" type="search" placeholder="Pesquisar" value="<?php echo $pesquisa; ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                Pesquisar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col" class="col-6">Nome</th>
                        <th scope="col" class="col-6">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($array as $key => $pessoa) {
                        echo "<tr>";
                        echo "<td>$pessoa->name</td>";
                        echo "<td>$pessoa->email</td>";
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
                    <?php $disabledPreview          = ($current_page == 0) ? 'disabled' : ''; ?>
                    <?php $disabledNext             = ($current_page == $total_pages) ? 'disabled' : ''; ?>
                    <?php $disabledPreviewOrNext    = ($current_page == 0 || $current_page == $total_pages) ? 'disabled' : ''; ?>
                    <?php $limitPreview             = ($current_page - 3) <= 0 ? 0 : $current_page - 3 ?>
                    <?php $limitNext                = ($current_page + 3) >= $total_pages ? $total_pages : $current_page + 3 ?>
                    <!-- Primeira -->
                    <?= "<a href='index.php?page=0' type='button' class='btn btn-primary $disabledPreview'>Primeira</a>" ?>
                    <!-- Anterior -->
                    <?= "<a href='index.php?page=" . ($current_page - 1) . "' type='button' class='btn btn-primary $disabledPreview'>Anterior</a>" ?>
                    <!-- Opções Anteriores -->
                    <?php for ($i = $limitPreview; $i < $current_page; $i++) { ?>
                        <?= "<a href='index.php?page=$i' type='button' class='btn btn-primary' $disabledPreviewOrNext>" . concatZero($i) . "</a>" ?>
                    <?php } ?>
                    <!-- Página atual -->
                    <?= "<a href='index.php?page=$current_page' type='button' class='btn btn-primary active $disabledPreviewOrNext'>" . concatZero($current_page) . "</a>" ?>
                    <!-- Opções Posteriores -->
                    <?php for ($i = $current_page + 1; $i <= $limitNext; $i++) { ?>
                        <?= "<a href='index.php?page=$i' type='button' class='btn btn-primary' $disabledPreviewOrNext>" . concatZero($i) . "</a>" ?>
                    <?php } ?>
                    <!-- Próximo -->
                    <?= "<a href='index.php?page=" . ($current_page + 1) . "' type='button' class='btn btn-primary $disabledNext'>Próximo</a>" ?>
                    <!-- Última -->
                    <?= "<a href='index.php?page=$total_pages' type='button' class='btn btn-primary $disabledNext'>Última</a>" ?>
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

<?php
function getJSON()
{
    return '[
        {
          "name": "Crane Cherry",
          "email": "cranecherry@earbang.com"
        },
        {
          "name": "Dana Sparks",
          "email": "danasparks@earbang.com"
        },
        {
          "name": "Jamie Sandoval",
          "email": "jamiesandoval@earbang.com"
        },
        {
          "name": "Deann Mcfarland",
          "email": "deannmcfarland@earbang.com"
        },
        {
          "name": "Lindsay Vang",
          "email": "lindsayvang@earbang.com"
        },
        {
          "name": "Ethel Bowers",
          "email": "ethelbowers@earbang.com"
        },
        {
          "name": "Espinoza Powell",
          "email": "espinozapowell@earbang.com"
        },
        {
          "name": "Cole Gardner",
          "email": "colegardner@earbang.com"
        },
        {
          "name": "Monroe Bartlett",
          "email": "monroebartlett@earbang.com"
        },
        {
          "name": "Lelia Kelly",
          "email": "leliakelly@earbang.com"
        },
        {
          "name": "Chrystal Alvarado",
          "email": "chrystalalvarado@earbang.com"
        },
        {
          "name": "Leann Palmer",
          "email": "leannpalmer@earbang.com"
        },
        {
          "name": "Nadine Cortez",
          "email": "nadinecortez@earbang.com"
        },
        {
          "name": "Luna Villarreal",
          "email": "lunavillarreal@earbang.com"
        },
        {
          "name": "Frank Evans",
          "email": "frankevans@earbang.com"
        },
        {
          "name": "Alisha Crane",
          "email": "alishacrane@earbang.com"
        },
        {
          "name": "Cunningham Odonnell",
          "email": "cunninghamodonnell@earbang.com"
        },
        {
          "name": "Oconnor Sampson",
          "email": "oconnorsampson@earbang.com"
        },
        {
          "name": "Hayes Gray",
          "email": "hayesgray@earbang.com"
        },
        {
          "name": "Strickland Fernandez",
          "email": "stricklandfernandez@earbang.com"
        },
        {
          "name": "Boone Frost",
          "email": "boonefrost@earbang.com"
        },
        {
          "name": "Megan Tate",
          "email": "megantate@earbang.com"
        },
        {
          "name": "Erma Lloyd",
          "email": "ermalloyd@earbang.com"
        },
        {
          "name": "Rush Orr",
          "email": "rushorr@earbang.com"
        },
        {
          "name": "Kathie Velasquez",
          "email": "kathievelasquez@earbang.com"
        },
        {
          "name": "Felecia Good",
          "email": "feleciagood@earbang.com"
        },
        {
          "name": "Caldwell Hendrix",
          "email": "caldwellhendrix@earbang.com"
        },
        {
          "name": "Natasha Gibson",
          "email": "natashagibson@earbang.com"
        },
        {
          "name": "Margaret Charles",
          "email": "margaretcharles@earbang.com"
        },
        {
          "name": "Mcfadden Herman",
          "email": "mcfaddenherman@earbang.com"
        },
        {
          "name": "Morrison Conley",
          "email": "morrisonconley@earbang.com"
        },
        {
          "name": "Nadia Ortiz",
          "email": "nadiaortiz@earbang.com"
        },
        {
          "name": "Melisa Garrett",
          "email": "melisagarrett@earbang.com"
        },
        {
          "name": "Marcella Hawkins",
          "email": "marcellahawkins@earbang.com"
        },
        {
          "name": "Santana Camacho",
          "email": "santanacamacho@earbang.com"
        },
        {
          "name": "Dyer Greer",
          "email": "dyergreer@earbang.com"
        },
        {
          "name": "Reynolds Wise",
          "email": "reynoldswise@earbang.com"
        },
        {
          "name": "Schroeder Warner",
          "email": "schroederwarner@earbang.com"
        },
        {
          "name": "Reed Sims",
          "email": "reedsims@earbang.com"
        },
        {
          "name": "Mercedes Case",
          "email": "mercedescase@earbang.com"
        },
        {
          "name": "Mccullough Stone",
          "email": "mcculloughstone@earbang.com"
        },
        {
          "name": "Hillary Steele",
          "email": "hillarysteele@earbang.com"
        },
        {
          "name": "Ryan Sharp",
          "email": "ryansharp@earbang.com"
        },
        {
          "name": "Autumn Burris",
          "email": "autumnburris@earbang.com"
        },
        {
          "name": "Guthrie Henderson",
          "email": "guthriehenderson@earbang.com"
        },
        {
          "name": "Penelope Hayes",
          "email": "penelopehayes@earbang.com"
        },
        {
          "name": "Santiago Castro",
          "email": "santiagocastro@earbang.com"
        },
        {
          "name": "Spence Castillo",
          "email": "spencecastillo@earbang.com"
        },
        {
          "name": "Pauline Shepard",
          "email": "paulineshepard@earbang.com"
        },
        {
          "name": "Hendricks Humphrey",
          "email": "hendrickshumphrey@earbang.com"
        },
        {
            "name": "Crane Cherry",
            "email": "cranecherry@earbang.com"
          },
          {
            "name": "Dana Sparks",
            "email": "danasparks@earbang.com"
          },
          {
            "name": "Jamie Sandoval",
            "email": "jamiesandoval@earbang.com"
          },
          {
            "name": "Deann Mcfarland",
            "email": "deannmcfarland@earbang.com"
          },
          {
            "name": "Lindsay Vang",
            "email": "lindsayvang@earbang.com"
          },
          {
            "name": "Ethel Bowers",
            "email": "ethelbowers@earbang.com"
          },
          {
            "name": "Espinoza Powell",
            "email": "espinozapowell@earbang.com"
          },
          {
            "name": "Cole Gardner",
            "email": "colegardner@earbang.com"
          },
          {
            "name": "Monroe Bartlett",
            "email": "monroebartlett@earbang.com"
          },
          {
            "name": "Lelia Kelly",
            "email": "leliakelly@earbang.com"
          },
          {
            "name": "Chrystal Alvarado",
            "email": "chrystalalvarado@earbang.com"
          },
          {
            "name": "Leann Palmer",
            "email": "leannpalmer@earbang.com"
          },
          {
            "name": "Nadine Cortez",
            "email": "nadinecortez@earbang.com"
          },
          {
            "name": "Luna Villarreal",
            "email": "lunavillarreal@earbang.com"
          },
          {
            "name": "Frank Evans",
            "email": "frankevans@earbang.com"
          },
          {
            "name": "Alisha Crane",
            "email": "alishacrane@earbang.com"
          },
          {
            "name": "Cunningham Odonnell",
            "email": "cunninghamodonnell@earbang.com"
          },
          {
            "name": "Oconnor Sampson",
            "email": "oconnorsampson@earbang.com"
          },
          {
            "name": "Hayes Gray",
            "email": "hayesgray@earbang.com"
          },
          {
            "name": "Strickland Fernandez",
            "email": "stricklandfernandez@earbang.com"
          },
          {
            "name": "Boone Frost",
            "email": "boonefrost@earbang.com"
          },
          {
            "name": "Megan Tate",
            "email": "megantate@earbang.com"
          },
          {
            "name": "Erma Lloyd",
            "email": "ermalloyd@earbang.com"
          },
          {
            "name": "Rush Orr",
            "email": "rushorr@earbang.com"
          },
          {
            "name": "Kathie Velasquez",
            "email": "kathievelasquez@earbang.com"
          },
          {
            "name": "Felecia Good",
            "email": "feleciagood@earbang.com"
          },
          {
            "name": "Caldwell Hendrix",
            "email": "caldwellhendrix@earbang.com"
          },
          {
            "name": "Natasha Gibson",
            "email": "natashagibson@earbang.com"
          },
          {
            "name": "Margaret Charles",
            "email": "margaretcharles@earbang.com"
          },
          {
            "name": "Mcfadden Herman",
            "email": "mcfaddenherman@earbang.com"
          },
          {
            "name": "Morrison Conley",
            "email": "morrisonconley@earbang.com"
          },
          {
            "name": "Nadia Ortiz",
            "email": "nadiaortiz@earbang.com"
          },
          {
            "name": "Melisa Garrett",
            "email": "melisagarrett@earbang.com"
          },
          {
            "name": "Marcella Hawkins",
            "email": "marcellahawkins@earbang.com"
          },
          {
            "name": "Santana Camacho",
            "email": "santanacamacho@earbang.com"
          },
          {
            "name": "Dyer Greer",
            "email": "dyergreer@earbang.com"
          },
          {
            "name": "Reynolds Wise",
            "email": "reynoldswise@earbang.com"
          },
          {
            "name": "Schroeder Warner",
            "email": "schroederwarner@earbang.com"
          },
          {
            "name": "Reed Sims",
            "email": "reedsims@earbang.com"
          },
          {
            "name": "Mercedes Case",
            "email": "mercedescase@earbang.com"
          },
          {
            "name": "Mccullough Stone",
            "email": "mcculloughstone@earbang.com"
          },
          {
            "name": "Hillary Steele",
            "email": "hillarysteele@earbang.com"
          },
          {
            "name": "Ryan Sharp",
            "email": "ryansharp@earbang.com"
          },
          {
            "name": "Autumn Burris",
            "email": "autumnburris@earbang.com"
          },
          {
            "name": "Guthrie Henderson",
            "email": "guthriehenderson@earbang.com"
          },
          {
            "name": "Penelope Hayes",
            "email": "penelopehayes@earbang.com"
          },
          {
            "name": "Santiago Castro",
            "email": "santiagocastro@earbang.com"
          },
          {
            "name": "Spence Castillo",
            "email": "spencecastillo@earbang.com"
          },
          {
            "name": "Pauline Shepard",
            "email": "paulineshepard@earbang.com"
          },
          {
            "name": "Hendricks Humphrey",
            "email": "hendrickshumphrey@earbang.com"
          },
          {
            "name": "Crane Cherry",
            "email": "cranecherry@earbang.com"
          },
          {
            "name": "Dana Sparks",
            "email": "danasparks@earbang.com"
          },
          {
            "name": "Jamie Sandoval",
            "email": "jamiesandoval@earbang.com"
          },
          {
            "name": "Deann Mcfarland",
            "email": "deannmcfarland@earbang.com"
          },
          {
            "name": "Lindsay Vang",
            "email": "lindsayvang@earbang.com"
          },
          {
            "name": "Ethel Bowers",
            "email": "ethelbowers@earbang.com"
          },
          {
            "name": "Espinoza Powell",
            "email": "espinozapowell@earbang.com"
          },
          {
            "name": "Cole Gardner",
            "email": "colegardner@earbang.com"
          },
          {
            "name": "Monroe Bartlett",
            "email": "monroebartlett@earbang.com"
          },
          {
            "name": "Lelia Kelly",
            "email": "leliakelly@earbang.com"
          },
          {
            "name": "Chrystal Alvarado",
            "email": "chrystalalvarado@earbang.com"
          },
          {
            "name": "Leann Palmer",
            "email": "leannpalmer@earbang.com"
          },
          {
            "name": "Nadine Cortez",
            "email": "nadinecortez@earbang.com"
          },
          {
            "name": "Luna Villarreal",
            "email": "lunavillarreal@earbang.com"
          },
          {
            "name": "Frank Evans",
            "email": "frankevans@earbang.com"
          },
          {
            "name": "Alisha Crane",
            "email": "alishacrane@earbang.com"
          },
          {
            "name": "Cunningham Odonnell",
            "email": "cunninghamodonnell@earbang.com"
          },
          {
            "name": "Oconnor Sampson",
            "email": "oconnorsampson@earbang.com"
          },
          {
            "name": "Hayes Gray",
            "email": "hayesgray@earbang.com"
          },
          {
            "name": "Strickland Fernandez",
            "email": "stricklandfernandez@earbang.com"
          },
          {
            "name": "Boone Frost",
            "email": "boonefrost@earbang.com"
          },
          {
            "name": "Megan Tate",
            "email": "megantate@earbang.com"
          },
          {
            "name": "Erma Lloyd",
            "email": "ermalloyd@earbang.com"
          },
          {
            "name": "Rush Orr",
            "email": "rushorr@earbang.com"
          },
          {
            "name": "Kathie Velasquez",
            "email": "kathievelasquez@earbang.com"
          },
          {
            "name": "Felecia Good",
            "email": "feleciagood@earbang.com"
          },
          {
            "name": "Caldwell Hendrix",
            "email": "caldwellhendrix@earbang.com"
          },
          {
            "name": "Natasha Gibson",
            "email": "natashagibson@earbang.com"
          },
          {
            "name": "Margaret Charles",
            "email": "margaretcharles@earbang.com"
          },
          {
            "name": "Mcfadden Herman",
            "email": "mcfaddenherman@earbang.com"
          },
          {
            "name": "Morrison Conley",
            "email": "morrisonconley@earbang.com"
          },
          {
            "name": "Nadia Ortiz",
            "email": "nadiaortiz@earbang.com"
          },
          {
            "name": "Melisa Garrett",
            "email": "melisagarrett@earbang.com"
          },
          {
            "name": "Marcella Hawkins",
            "email": "marcellahawkins@earbang.com"
          },
          {
            "name": "Santana Camacho",
            "email": "santanacamacho@earbang.com"
          },
          {
            "name": "Dyer Greer",
            "email": "dyergreer@earbang.com"
          },
          {
            "name": "Reynolds Wise",
            "email": "reynoldswise@earbang.com"
          },
          {
            "name": "Schroeder Warner",
            "email": "schroederwarner@earbang.com"
          },
          {
            "name": "Reed Sims",
            "email": "reedsims@earbang.com"
          },
          {
            "name": "Mercedes Case",
            "email": "mercedescase@earbang.com"
          },
          {
            "name": "Mccullough Stone",
            "email": "mcculloughstone@earbang.com"
          },
          {
            "name": "Hillary Steele",
            "email": "hillarysteele@earbang.com"
          },
          {
            "name": "Ryan Sharp",
            "email": "ryansharp@earbang.com"
          },
          {
            "name": "Autumn Burris",
            "email": "autumnburris@earbang.com"
          },
          {
            "name": "Guthrie Henderson",
            "email": "guthriehenderson@earbang.com"
          },
          {
            "name": "Penelope Hayes",
            "email": "penelopehayes@earbang.com"
          },
          {
            "name": "Santiago Castro",
            "email": "santiagocastro@earbang.com"
          },
          {
            "name": "Spence Castillo",
            "email": "spencecastillo@earbang.com"
          },
          {
            "name": "Pauline Shepard",
            "email": "paulineshepard@earbang.com"
          },
          {
            "name": "Hendricks Humphrey",
            "email": "hendrickshumphrey@earbang.com"
          }
      ]';
}
?>
