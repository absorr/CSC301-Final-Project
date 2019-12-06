<?php
include_once '../../../config.php';

if (!isset($_GET['targetId']) || !isset($_GET['userId']) || !isset($_GET['moveIndex'])) {
    http_response_code(400);
    die("BAD REQUEST");
}

$pokemon = loadPokemonBattleData(array($_GET['targetId'], $_GET['userId']), $database, '../../../');

$user = $pokemon[$_GET['userId']];
$target = $pokemon[$_GET['targetId']];

if ($_GET['moveIndex'] == 1)
    $move = $user->move1;
elseif ($_GET['moveIndex'] == 2)
    $move = $user->move2;
elseif ($_GET['moveIndex'] == 3)
    $move = $user->move3;
else
    $move = $user->move4;

if ($move->class == "Status") {
    doMoveCSEffects($move, $user, $target);
    die();
}

// Get stats appropriate for move class
$atk = $move->class == "Physical" ? $user->getAttack() : $user->getSpecialAttack();
$def = $move->class == "Physical" ? $target->getDefense() : $target->getSpecialDefense();
// Increase damage base if Same Type Attack Bonus (STAB) applies
$db = intval($move->db) + ($user->getPokedex()->getTypeName1() == $move->type || $user->getPokedex()->getTypeName2() == $move->type ? 2 : 0);
// Get Combat Stage multipliers
$atk *= getCSMultiplier($_SESSION['COMBAT_STAGES'][$user->pokemon_id][$move->class == "Physical" ? 'atk' : 'spatk']);
$def *= getCSMultiplier($_SESSION['COMBAT_STAGES'][$target->pokemon_id][$move->class == "Physical" ? 'def' : 'spdef']);

// Calculate that damage!!!
$dmg = ((rollDB($db) + $atk) * getTypeEffectivity($move->type, $target, $database)) - $def;

// Pity damage
if ($dmg < 1) $dmg = 1;

echo $dmg;

doMoveCSEffects($move, $user, $target);

function rollDB($db) {
    switch ($db) {
        case 1:
            return roll(1, 6, 1);
        case 2:
            return roll(1, 6, 3);
        case 3:
            return roll(1, 6, 5);
        case 4:
            return roll(1, 8, 6);
        case 5:
            return roll(1, 8, 8);
        case 6:
            return roll(2, 6, 8);
        case 7:
            return roll(2, 6, 10);
        case 8:
            return roll(2, 8, 10);
        case 9:
            return roll(2, 10, 10);
        case 10:
            return roll(3, 8, 10);
        case 11:
            return roll(3, 10, 10);
        case 12:
            return roll(3, 12, 10);
        case 13:
            return roll(4, 10, 10);
        case 14:
            return roll(4, 10, 15);
        case 15:
            return roll(4, 10, 20);
        case 16:
            return roll(5, 10, 20);
        case 17:
            return roll(5, 12, 25);
        case 18:
            return roll(6, 12, 25);
        case 19:
            return roll(6, 12, 30);
        case 20:
            return roll(6, 12, 35);
        case 21:
            return roll(6, 12, 40);
        case 22:
            return roll(6, 12, 45);
        case 23:
            return roll(6, 12, 50);
        case 24:
            return roll(6, 12, 55);
        case 25:
            return roll(6, 12, 60);
        case 26:
            return roll(7, 12, 65);
        case 27:
            return roll(8, 12, 70);
        case 28:
            return roll(8, 12, 80);
        default:
            return 0;
    }
}

function roll($num, $die, $add) {
    $r = 0;

    for ($i=0; $i<$num; $i++) {
        $r += rand(0, $die) + 1;

        if ($die == 10) {
            $r--;    // d10's are 0-9
        }
    }

    return $r + $add;
}

function getTypeEffectivity($type, $pokemon, $database) {
    $sql = file_get_contents('../../../sql/get_type_effect.sql');
    $stmt = $database->prepare($sql);
    $stmt->execute(array(
        "type" => strtolower($type)
    ));

    $rels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $multiplier = 1;

    foreach ($rels as $rel) {
        if (strtolower($rel['type_name_def']) == strtolower($pokemon->getPokedex()->getTypeName1()) ||
            strtolower($rel['type_name_def']) == strtolower($pokemon->getPokedex()->getTypeName2()))
            $multiplier *= intval($rel['eff_multiplier']);
    }

    return $multiplier;
}
