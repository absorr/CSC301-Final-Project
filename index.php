<?php
require 'config.php';

// Fetch list of Pokemon and their Pokedex entries

$pokemon = array();

$sql = file_get_contents('sql/get_list_pokemon.sql');
$stmt = $database->prepare($sql);
$stmt->execute();

$pmon = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pmon as $p) {
    $pokemon[] = new Pokemon(
        $p['pokemon_id'],
        $p['pokedex_id'],
        $p['nickname'],
        $p['level'],
        $p['added_hp'],
        $p['added_attack'],
        $p['added_defense'],
        $p['added_special_attack'],
        $p['added_special_defense'],
        $p['added_speed']
    );
}

$sql = file_get_contents('sql/get_list_pokemon_dexes.sql');
$stmt = $database->prepare($sql);
$stmt->execute();

$dexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($dexes as $dex) {
    new Pokedex(
        $dex['pokedex_id'],
        $dex['pokedex_no'],
        $dex['species'],
        $dex['form'],
        $dex['family'],
        $dex['base_hp'],
        $dex['base_attack'],
        $dex['base_defense'],
        $dex['base_special_attack'],
        $dex['base_special_defense'],
        $dex['base_speed'],
        $dex['type_name_1'],
        $dex['type_name_2']
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hello, world!</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="assets/css/material-kit.css?v=2.0.6" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
<!--<nav class="navbar bg-danger fixed-top navbar-expand-lg">-->
<!--    <div class="container">-->
<!--        <div class="navbar-translate">-->
<!--            <a class="navbar-brand" href="https://demos.creative-tim.com/material-kit/index.html">-->
<!--                Material Kit </a>-->
<!--            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                <span class="sr-only">Toggle navigation</span>-->
<!--                <span class="navbar-toggler-icon"></span>-->
<!--                <span class="navbar-toggler-icon"></span>-->
<!--                <span class="navbar-toggler-icon"></span>-->
<!--            </button>-->
<!--        </div>-->
<!--        <div class="collapse navbar-collapse">-->
<!--            <ul class="navbar-nav ml-auto">-->
<!--                <li class="nav-item">-->
<!--                    <a href="#" class="nav-link">-->
<!--                        <i class="material-icons">apps</i> Template-->
<!--                    </a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
<!--</nav>-->
<div class="container">
    <h2 class="title text-center">Pokemon Battler</h2>
    <div class="row">
        <?php foreach ($pokemon as $pmon) : ?>
        <div class="col-sm-6 col-lg-4 pokemon-card">
            <div class="card card-profile">
                <div class="card-header card-header-image bg-t-normal-gradient">
                    <img class="img" src="assets/img/pokemon-profiles/<?php echo $pmon->getPokedex()->getPokedexNo() ?>.png" alt="<?php echo $pmon->getPokedex()->getSpecies() ?>">
                </div>

                <div class="card-body">
                    <h4 class="card-title"><?php echo $pmon->nickname; ?> - Lvl. <?php echo $pmon->level; ?></h4>
                    <h6 class="card-category text-gray"><?php echo $pmon->getPokedex()->getDisplayTypes(); ?> Type</h6>
                </div>
                <div class="card-footer justify-content-center">
                    <a href="edit.php?pokemon=<?php echo $pmon->pokemon_id; ?>" class="btn btn-default btn-round">
                        Edit
                    </a>
                    <a href="#pablo" class="btn btn-danger btn-round">
                        Battle
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<footer class="footer footer-default">
    <div class="container">
        <nav class="float-left">
            <ul>
                <li>
                    <a href="https://www.creative-tim.com/">
                        Creative Tim
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright float-right">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="https://www.creative-tim.com/" target="blank">Creative Tim</a> for a better web.
        </div>
    </div>
</footer>

<script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="./assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="./assets/js/plugins/moment.min.js"></script>
<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="./assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="./assets/js/material-kit.js?v=2.0.6" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.min.css" rel="stylesheet"
      type="text/css"/>
<script>
    //Remove JQuery UI conflicts with Bootstrap
    $.widget.bridge('uibutton', $.ui.button);
    $.widget.bridge('uitooltip', $.ui.tooltip);
</script>
</body>

</html>
