<?php
require 'config.php';

if (!isset($_GET['pokemonIds'])) {
    http_response_code(400);
    die("BAD REQUEST");
}

$pokemon = loadPokemonBattleData($_GET['pokemonIds'], $database);

$csMap = array();
foreach ($pokemon as $pmon) {
    $csMap[$pmon->pokemon_id] = array(
            "atk" => 0,
            "def" => 0,
            "spatk" => 0,
            "spdef" => 0,
            "speed" => 0
    );
}

$_SESSION['COMBAT_STAGES'] = $csMap;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pokemon Battler</title>
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
    <div class="row">
        <?php foreach ($pokemon as $pmon) : ?>
            <div class="col-sm-6 col-lg-4 pokemon-card" data-pokemon-id="<?php echo $pmon->pokemon_id ?>">
                <div class="card card-profile">
                    <div class="progress-container progress-danger progress-health">
                        <span class="progress-badge">Health</span>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger" role="progressbar"
                                 aria-valuenow="<?php echo $pmon->getMaxHealth() ?>"
                                 aria-valuemin="0"
                                 aria-valuemax="<?php echo $pmon->getMaxHealth() ?>"
                                 style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="card-header card-header-image bg-t-<?php echo strtolower($pmon->getPokedex()->getTypeName1()); ?>-gradient">
                        <img class="img" src="assets/img/pokemon-profiles/<?php echo $pmon->getPokedex()->getPokedexNo() ?>.png" alt="<?php echo $pmon->getPokedex()->getSpecies() ?>">
                    </div>

                    <div class="card-body">
                        <h4 class="card-title"><?php echo $pmon->nickname; ?> - Lvl. <?php echo $pmon->level; ?></h4>
                        <h6 class="card-category text-gray"><?php echo $pmon->getPokedex()->getDisplayTypes(); ?> Type</h6>
                    </div>
                    <hr/>
                    <div class="card-footer move-list">
                        <?php if (!empty($pmon->move1->move_id)) : ?>
                        <div class="row">
                            <div class="col text-left">
                                <strong class="move-name"><?php echo $pmon->move1->name ?></strong>
                                <small class="move-type bg-t-<?php echo strtolower($pmon->move1->type) ?>"><?php echo strtoupper($pmon->move1->type) ?></small>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-outline-danger btn-sm btn-round" data-move-id="<?php echo $pmon->move1->move_id ?>" data-index="1">Use</button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pmon->move2->move_id)) : ?>
                        <div class="row">
                            <div class="col text-left">
                                <strong class="move-name"><?php echo $pmon->move2->name ?></strong>
                                <small class="move-type bg-t-<?php echo strtolower($pmon->move2->type) ?>"><?php echo strtoupper($pmon->move2->type) ?></small>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-outline-danger btn-sm btn-round" data-move-id="<?php echo $pmon->move2->move_id ?>" data-index="2">Use</button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pmon->move3->move_id)) : ?>
                        <div class="row">
                            <div class="col text-left">
                                <strong class="move-name"><?php echo $pmon->move3->name ?></strong>
                                <small class="move-type bg-t-<?php echo strtolower($pmon->move3->type) ?>"><?php echo strtoupper($pmon->move3->type) ?></small>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-outline-danger btn-sm btn-round" data-move-id="<?php echo $pmon->move3->move_id ?>" data-index="3">Use</button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pmon->move4->move_id)) : ?>
                        <div class="row">
                            <div class="col text-left">
                                <strong class="move-name"><?php echo $pmon->move4->name ?></strong>
                                <small class="move-type bg-t-<?php echo strtolower($pmon->move4->type) ?>"><?php echo strtoupper($pmon->move4->type) ?></small>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-outline-danger btn-sm btn-round" data-move-id="<?php echo $pmon->move4->move_id ?>" data-index="4">Use</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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

<div class="modal fade bd-example-modal-sm" id="modalSelectTarget" tabindex="-1" role="dialog" aria-labelledby="move-modal-name" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-danger text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>

                        <h4 class="card-title" id="move-modal-name">Move Name</h4>
                        <p><strong>Type:</strong> <span id="move-modal-type">--</span> <strong>Class:</strong> <span id="move-modal-class">--</span> <strong>DB:</strong> <span id="move-modal-db">0</span></p>
                        <blockquote id="move-modal-desc">Move Description</blockquote>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="battle-form">
                        <?php foreach ($pokemon as $pmon): ?>
                        <div class="form-check form-check-radio">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="target" value="<?php echo $pmon->pokemon_id ?>" >
                                <?php echo $pmon->nickname ?>
                                <span class="circle"><span class="check"></span></span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-round" onclick="onSelectTarget()">Use</button>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script>
    var MOVES = {}, HEALTH = {};

    <?php foreach ($pokemon as $pmon): ?>
    HEALTH[<?php echo $pmon->pokemon_id ?>] = <?php echo $pmon->getMaxHealth() ?>;

    <?php if (!empty($pmon->move1->move_id)) : ?>
    MOVES['<?php echo $pmon->move1->move_id ?>'] = <?php echo json_encode($pmon->move1) ?>;
    <?php endif; ?>
    <?php if (!empty($pmon->move2->move_id)) : ?>
    MOVES['<?php echo $pmon->move2->move_id ?>'] = <?php echo json_encode($pmon->move2) ?>;
    <?php endif; ?>
    <?php if (!empty($pmon->move3->move_id)) : ?>
    MOVES['<?php echo $pmon->move3->move_id ?>'] = <?php echo json_encode($pmon->move3) ?>;
    <?php endif; ?>
    <?php if (!empty($pmon->move4->move_id)) : ?>
    MOVES['<?php echo $pmon->move4->move_id ?>'] = <?php echo json_encode($pmon->move4) ?>;
    <?php endif; ?>
    <?php endforeach; ?>
</script>
</body>

</html>
