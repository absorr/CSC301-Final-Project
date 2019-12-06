<?php

function loadPokemonBattleData($pokemonIds, $database, $fileRoot = "") {
    // Fetch list of Pokemon and their Pokedex entries

    $pokemon = array();

    $inclause = implode(',',array_fill(0, count($pokemonIds), '?'));
    $sql = sprintf(file_get_contents($fileRoot.'sql/get_battle_pokemon.sql'), $inclause);
    $stmt = $database->prepare($sql);
    $stmt->execute($pokemonIds);

    $pmon = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pmon as $p) {
        $currentPmon = new Pokemon(
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

        $currentPmon->move1 = new Move(
            $p['move_id_1'],
            $p['move_name_1'],
            $p['move_type_1'],
            $p['move_effect_1'],
            $p['move_freq_1'],
            $p['move_class_1'],
            $p['move_range_1'],
            $p['move_contest_type_1'],
            $p['move_contest_effect_1'],
            $p['move_crits_on_1'],
            $p['move_triggers_1'],
            $p['move_db_1']
        );

        $currentPmon->move2 = new Move(
            $p['move_id_2'],
            $p['move_name_2'],
            $p['move_type_2'],
            $p['move_effect_2'],
            $p['move_freq_2'],
            $p['move_class_2'],
            $p['move_range_2'],
            $p['move_contest_type_2'],
            $p['move_contest_effect_2'],
            $p['move_crits_on_2'],
            $p['move_triggers_2'],
            $p['move_db_2']
        );

        $currentPmon->move3 = new Move(
            $p['move_id_3'],
            $p['move_name_3'],
            $p['move_type_3'],
            $p['move_effect_3'],
            $p['move_freq_3'],
            $p['move_class_3'],
            $p['move_range_3'],
            $p['move_contest_type_3'],
            $p['move_contest_effect_3'],
            $p['move_crits_on_3'],
            $p['move_triggers_3'],
            $p['move_db_3']
        );

        $currentPmon->move4 = new Move(
            $p['move_id_4'],
            $p['move_name_4'],
            $p['move_type_4'],
            $p['move_effect_4'],
            $p['move_freq_4'],
            $p['move_class_4'],
            $p['move_range_4'],
            $p['move_contest_type_4'],
            $p['move_contest_effect_4'],
            $p['move_crits_on_4'],
            $p['move_triggers_4'],
            $p['move_db_4']
        );

        $pokemon[$p['pokemon_id']] = $currentPmon;
    }

    $sql = file_get_contents($fileRoot.'sql/get_list_pokemon_dexes.sql');
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

    return $pokemon;
}

/**
 * @param Move $move
 * @param Pokemon $user
 * @param Pokemon $target
 */
function doMoveCSEffects($move, $user, $target) {
    if ($move->triggers) {
        $triggers = json_decode($move->triggers);

        foreach ($triggers as $trigger) {
            if (property_exists($target, 'type') && $trigger->type == "CS") {
                $targetId = $trigger->target == "SELF" ? $user->pokemon_id : $target->pokemon_id;
                foreach ($trigger->stat as $stat) {
                    if ($_SESSION['COMBAT_STAGES'][$targetId][$stat] < 6 && $_SESSION['COMBAT_STAGES'][$targetId][$stat] > -6)
                        $_SESSION['COMBAT_STAGES'][$targetId][$stat] += $trigger->value;
                }
            }
        }
    }
}

function getCSMultiplier($cs) {
    switch ($cs) {
        case -6: return 0.4;
        case -5: return 0.5;
        case -4: return 0.6;
        case -3: return 0.7;
        case -2: return 0.8;
        case -1: return 0.9;
        case 0: return 1;
        case 1: return 1.2;
        case 2: return 1.4;
        case 3: return 1.6;
        case 4: return 1.8;
        case 5: return 2;
        case 6: return 2.2;
    }
}