<?php
include 'config.php';

// Fetch list of Pokemon species
$sql = file_get_contents('sql/get_list_pokedex_names.sql');
$stmt = $database->prepare($sql);
$stmt->execute();

$DexNames = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch list of move names
$sql = file_get_contents('sql/get_list_move_names.sql');
$stmt = $database->prepare($sql);
$stmt->execute();

$MoveNames = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If submitting form, save Pokemon
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $params = array(
        "nickname" => $_POST['name'],
        "pokedex_id" => $_POST['pokedexId'],
        "level" => $_POST['level'],
        "added_attack" => $_POST['add-atk'],
        "added_defense" => $_POST['add-def'],
        "added_special_attack" => $_POST['add-spatk'],
        "added_special_defense" => $_POST['add-spdef'],
        "added_speed" => $_POST['add-spd'],
        "added_hp" => $_POST['add-hp'],
        "move_id_1" => $_POST['move1'],
        "move_id_2" => $_POST['move2'],
        "move_id_3" => $_POST['move3'],
        "move_id_4" => $_POST['move4']
    );

    $sql = isset($_POST['pokemonId']) && !empty($_POST['pokemonId']) ? file_get_contents('sql/update_pokemon.sql') : file_get_contents('sql/add_pokemon.sql');
    $stmt = $database->prepare($sql);
    $stmt->execute($params);

    $pokemon = new Pokemon(
        $database->lastInsertId(),
        $_POST['pokedexId'],
        $_POST['name'],
        $_POST['level'],
        $_POST['add-hp'],
        $_POST['add-atk'],
        $_POST['add-def'],
        $_POST['add-spatk'],
        $_POST['add-spdef'],
        $_POST['add-spd']
    );

    $sql = file_get_contents('sql/get_pokedex_info.sql');
    $stmt = $database->prepare($sql);
    $stmt->execute(array(
        "pokedexNo" => $_POST['pokedexId']
    ));

    $dexInfo = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

    new Pokedex(
        $dexInfo['pokedex_id'],
        $dexInfo['pokedex_no'],
        $dexInfo['species'],
        $dexInfo['form'],
        $dexInfo['family'],
        $dexInfo['base_hp'],
        $dexInfo['base_attack'],
        $dexInfo['base_defense'],
        $dexInfo['base_special_attack'],
        $dexInfo['base_special_defense'],
        $dexInfo['base_speed'],
        $dexInfo['type_name_1'],
        $dexInfo['type_name_2']
    );
} elseif (isset($_GET['pokemon'])) {
    $sql = file_get_contents('sql/get_pokemon.sql');
    $stmt = $database->prepare($sql);
    $stmt->execute(array(
            "pokemon_id" => $_GET['pokemon']
    ));

    $p = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $pokemon = new Pokemon(
        $p[0]['pokemon_id'],
        $p[0]['pokedex_id'],
        $p[0]['nickname'],
        $p[0]['level'],
        $p[0]['added_hp'],
        $p[0]['added_attack'],
        $p[0]['added_defense'],
        $p[0]['added_special_attack'],
        $p[0]['added_special_defense'],
        $p[0]['added_speed']
    );

    $pokemon->move1 = new Move($p[0]['move_id_1'], $p[0]['move_name_1'], $p[0]['move_type_1']);
    $pokemon->move2 = new Move($p[0]['move_id_2'], $p[0]['move_name_2'], $p[0]['move_type_2']);
    $pokemon->move3 = new Move($p[0]['move_id_3'], $p[0]['move_name_3'], $p[0]['move_type_3']);
    $pokemon->move4 = new Move($p[0]['move_id_4'], $p[0]['move_name_4'], $p[0]['move_type_4']);

    new Pokedex(
        $p[0]['pokedex_id'],
        $p[0]['pokedex_no'],
        $p[0]['species'],
        $p[0]['form'],
        $p[0]['family'],
        $p[0]['base_hp'],
        $p[0]['base_attack'],
        $p[0]['base_defense'],
        $p[0]['base_special_attack'],
        $p[0]['base_special_defense'],
        $p[0]['base_speed'],
        $p[0]['type_name_1'],
        $p[0]['type_name_2']
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
    <h2 class="title text-center">Manage Pokemon</h2>
    <div class="card card-profile ml-auto mr-auto" style="max-width: 500px">
        <form method="post">
            <div class="card-header card-header-danger">
                <h4 class="card-title">Add New Pokemon</h4>
            </div>

            <input type="hidden" id="pokemonId" name="pokemonId"/>
            <input type="hidden" id="pokedexId" name="pokedexId"/>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-9">
                        <label for="name">Pokemon's Name</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="20" value="<?php if (isset($pokemon)) echo $pokemon->nickname; ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="level">Level</label>
                        <input type="number" class="form-control" id="level" name="level" value="<?php if (isset($pokemon)) echo $pokemon->level; ?>" required>
                    </div>
                </div>
                <hr/>
                <h6>Pokedex Lookup</h6>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="dex">Pokedex No</label>
                        <input type="text" class="form-control" id="dex" name="dex" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getPokedexNo(); ?>" required>
                    </div>
                    <div class="form-group col-md-8">
                        <div class="row dex-data-container">
                            <div class="col-sm-4">
                                <img id="dex-img" src="assets/img/pokemon-profiles/<?php echo (isset($pokemon)) ? $pokemon->getPokedex()->getPokedexNo() : "000"; ?>.png" />
                            </div>
                            <div class="col-sm-8">
                                <label>Species</label><br/>
                                <strong id="dex-species"><?php echo (isset($pokemon)) ? $pokemon->getPokedex()->getSpecies() : "N/A"; ?></strong><br/>
                                <label>Type(s)</label><br/>
                                <strong id="dex-types"><?php echo (isset($pokemon)) ? $pokemon->getPokedex()->getDisplayTypes() : "N/A"; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <section id="section-stats">
                    <h6>Stats</h6>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="base-hp">Base HP</label>
                            <input type="number" class="form-control" id="base-hp" name="base-hp" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getBaseHp(); ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="add-hp">Added HP</label>
                            <input type="number" class="form-control" id="add-hp" name="add-hp" value="<?php if (isset($pokemon)) echo $pokemon->added_hp; ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total-hp">Total HP</label><br/>
                            <strong id="total-hp" class="stat-total"><?php echo (isset($pokemon)) ? $pokemon->added_hp + $pokemon->getPokedex()->getBaseHp() : "0"; ?></strong>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="base-atk">Base Attack</label>
                            <input type="number" class="form-control" id="base-atk" name="base-atk" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getBaseAttack(); ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="add-atk">Added Attack</label>
                            <input type="number" class="form-control" id="add-atk" name="add-atk" value="<?php if (isset($pokemon)) echo $pokemon->added_attack; ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total-atk">Total Attack</label><br/>
                            <strong id="total-atk" class="stat-total"><?php echo (isset($pokemon)) ? $pokemon->added_attack + $pokemon->getPokedex()->getBaseAttack() : "0"; ?></strong>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="base-def">Base Defense</label>
                            <input type="number" class="form-control" id="base-def" name="base-def" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getBaseDefense(); ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="add-def">Added Defense</label>
                            <input type="number" class="form-control" id="add-def" name="add-def" value="<?php if (isset($pokemon)) echo $pokemon->added_defense; ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total-def">Total Defense</label><br/>
                            <strong id="total-def" class="stat-total"><?php echo (isset($pokemon)) ? $pokemon->added_defense + $pokemon->getPokedex()->getBaseDefense() : "0"; ?></strong>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="base-spatk">Base Special Attack</label>
                            <input type="number" class="form-control" id="base-spatk" name="base-spatk" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getBaseSpecialAttack(); ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="add-spatk">Added Special Attack</label>
                            <input type="number" class="form-control" id="add-spatk" name="add-spatk" value="<?php if (isset($pokemon)) echo $pokemon->added_special_attack; ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total-spatk">Total Special Attack</label><br/>
                            <strong id="total-spatk" class="stat-total"><?php echo (isset($pokemon)) ? $pokemon->added_special_attack + $pokemon->getPokedex()->getBaseSpecialAttack() : "0"; ?></strong>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="base-spdef">Base Special Defense</label>
                            <input type="number" class="form-control" id="base-spdef" name="base-spdef" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getBaseSpecialDefense(); ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="add-spdef">Added Special Defense</label>
                            <input type="number" class="form-control" id="add-spdef" name="add-spdef" value="<?php if (isset($pokemon)) echo $pokemon->added_special_defense; ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total-spdef">Total Special Defense</label><br/>
                            <strong id="total-spdef" class="stat-total"><?php echo (isset($pokemon)) ? $pokemon->added_special_defense + $pokemon->getPokedex()->getBaseSpecialDefense() : "0"; ?></strong>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="base-spd">Base Speed</label>
                            <input type="number" class="form-control" id="base-spd" name="base-spd" value="<?php if (isset($pokemon)) echo $pokemon->getPokedex()->getBaseSpeed(); ?>" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="add-spd">Added Speed</label>
                            <input type="number" class="form-control" id="add-spd" name="add-spd" value="<?php if (isset($pokemon)) echo $pokemon->added_speed; ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total-spd">Total Speed</label><br/>
                            <strong id="total-spd" class="stat-total"><?php echo (isset($pokemon)) ? $pokemon->added_speed + $pokemon->getPokedex()->getBaseSpeed() : "0"; ?></strong>
                        </div>
                    </div>
                </section>
                <hr/>
                <section id="section-moves">
                    <h6>Moves</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control input-move" id="move1-name" name="move1-name" value="<?php if (isset($pokemon) && isset($pokemon->move1)) echo $pokemon->move1->name; ?>">
                            <input type="hidden" id="move1" name="move1" value="<?php if (isset($pokemon) && isset($pokemon->move1)) echo $pokemon->move1->move_id; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control input-move" id="move2-name" name="move2-name" value="<?php if (isset($pokemon) && isset($pokemon->move2)) echo $pokemon->move2->name; ?>">
                            <input type="hidden" id="move2" name="move2" value="<?php if (isset($pokemon) && isset($pokemon->move2)) echo $pokemon->move2->move_id; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control input-move" id="move3-name" name="move3-name" value="<?php if (isset($pokemon) && isset($pokemon->move3)) echo $pokemon->move3->name; ?>">
                            <input type="hidden" id="move3" name="move3" value="<?php if (isset($pokemon) && isset($pokemon->move3)) echo $pokemon->move3->move_id; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control input-move" id="move4-name" name="move4-name" value="<?php if (isset($pokemon) && isset($pokemon->move4)) echo $pokemon->move4->name; ?>">
                            <input type="hidden" id="move4" name="move4" value="<?php if (isset($pokemon) && isset($pokemon->move4)) echo $pokemon->move4->move_id; ?>">
                        </div>
                    </div>
                </section>
            </div>
            <div class="card-footer justify-content-center">
                <button type="submit" class="btn btn-danger btn-round">
                    Save
                </button>
            </div>
        </form>
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
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.min.css" rel="stylesheet"
      type="text/css"/>
<script>
    //Remove JQuery UI conflicts with Bootstrap
    $.widget.bridge('uibutton', $.ui.button);
    $.widget.bridge('uitooltip', $.ui.tooltip);

    //Add PHP provided data
    const DEX_NAMES = [
        <?php for($i = 0; $i < sizeof($DexNames); $i++) : ?>
        {
            label: "<?php echo $DexNames[$i]['pokedex_no'].'-'.$DexNames[$i]['species'] ?>",
            value: "<?php echo $DexNames[$i]['pokedex_no'] ?>"
        }<?php if ($i != sizeof($DexNames) - 1) echo ',' ?>
        <?php endfor; ?>
    ];

    const MOVE_NAMES = [
        <?php for($i = 0; $i < sizeof($MoveNames); $i++) : ?>
        {
            label: "<?php echo $MoveNames[$i]['name'].' ('.$MoveNames[$i]['type'].')' ?>",
            value: "<?php echo $MoveNames[$i]['name'] ?>",
            id: "<?php echo $MoveNames[$i]['move_id'] ?>"
        }<?php if ($i != sizeof($MoveNames) - 1) echo ',' ?>
        <?php endfor; ?>
    ];

    $("#dex").autocomplete({
        source: DEX_NAMES
    }).blur(function () {
        lookupPokemon($(this).val());
    });

    $(".input-move").autocomplete({
        source: MOVE_NAMES,
        select: function (e, ui) {
            $(this).siblings().first().val(ui.item.id);
        }
    });

    $("#section-stats input").change(function () {
        var sum = 0;
        var rowElem = $(this).closest(".form-row");

        rowElem.find("input").each(function () {
            sum += parseInt($(this).val());
        });

        rowElem.find("strong").html(sum);
    });

    function lookupPokemon(pokedexNo) {
        $.ajax('api/v1/getPokedexData', {
            data: {
                'pokedexNo': pokedexNo
            },
            success: function(value) {
                var json = JSON.parse(value);
                $("#dex-species").html(json['species']);
                $("#pokedexId").val(json['pokedex_id']);
                if (json['type_name_2']) {
                    $("#dex-types").html(json['type_name_1'] + '/' + json['type_name_2']);
                } else {
                    $("#dex-types").html(json['type_name_1']);
                }
                $("#dex-img").attr("src", "assets/img/pokemon-profiles/" + pokedexNo + ".png");

                $("#base-hp").val(json['base_hp']);
                $("#base-atk").val(json['base_attack']);
                $("#base-def").val(json['base_defense']);
                $("#base-spatk").val(json['base_special_attack']);
                $("#base-spdef").val(json['base_special_defense']);
                $("#base-spd").val(json['base_speed']);
            }
        })
    }
</script>
</body>

</html>
