<?php
require 'config.php';

// Fetch list of Pokemon and their Pokedex entries

$pokemon = array();

if (isset($_GET['search'])) {
    $sql = file_get_contents('sql/search_pokemon.sql');
    $stmt = $database->prepare($sql);
    $stmt->execute(array(
            "search" => $_GET['search']
    ));
}
else {
    $sql = file_get_contents('sql/get_list_pokemon.sql');
    $stmt = $database->prepare($sql);
    $stmt->execute();
}

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
<div class="container">
    <h2 class="title text-center">Pokemon Battler</h2>
    <form id="search-form" method="get">
        <div class="form-group col-md-4 offset-md-4">
            <input type="file" multiple="" class="inputFileHidden">
            <div class="input-group">
                <input type="text" class="form-control inputFileVisible" name="search" placeholder="Search by name or type">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-fab btn-round btn-danger">
                        <i class="material-icons">search</i>
                    </button>
                </span>
            </div>
        </div>
    </form>
    <form id="battle-select" action="battle.php" method="get">
        <div class="row">
            <?php foreach ($pokemon as $pmon) : ?>
            <div class="col-sm-6 col-lg-4 pokemon-card">
                <div class="card card-profile">
                    <div class="card-header card-header-image bg-t-<?php echo strtolower($pmon->getPokedex()->getTypeName1()); ?>-gradient">
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
                        <a class="btn btn-danger btn-round">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input battle-select-check" type="checkbox" name="pokemonIds[]" value="<?php echo $pmon->pokemon_id; ?>"> Battle
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </form>
    <div class="text-center">
        <a href="edit.php" class="btn btn-lg btn-outline-danger btn-round"><i class="material-icons">add</i> Add New Pokemon</a>
    </div>
</div>
<footer class="footer footer-default">
    <div class="container">
        <nav class="text-center">
            <ul>
                <li>
                    Will Stephenson, CSC301 Final Project
                </li>
            </ul>
        </nav>
        <div class="copyright text-center">
            Pokémon © 2002-2019 Pokémon. © 1995-2019 Nintendo/Creatures Inc./GAME FREAK inc. ™, ® and Pokémon character names are trademarks of Nintendo.
        </div>
    </div>
</footer>

<nav class="navbar bg-danger fixed-bottom">
    <div class="container">
        <strong id="pokemon-count">0</strong> Pokemon Selected
        <button class="btn btn-round btn-outline btn-outline-light" id="battle-submit" onclick="onClickBattle()" disabled>Battle!</button>
    </div>
</nav>

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
<script src="assets/js/script.js"></script>
</body>

</html>
