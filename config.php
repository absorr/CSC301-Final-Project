<?php

function class_autoloader($class) {
    include_once 'classes/' . $class . '.class.php';
}

spl_autoload_register('class_autoloader');

$database = new PDO('mysql:host=csweb.hh.nku.edu;dbname=db_fall19_stephensow1', 'stephensow1', '63fcdc7a');

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