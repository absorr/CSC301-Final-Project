<?php

include_once '../_common/config.php';

$json = json_decode(file_get_contents('ptu_pokedex_1_05.json'), true);
$sql = "INSERT INTO final_pokedex (pokedex_no, species, form, family, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, type_name_1, type_name_2) VALUES (:pokedex_no, :species, :form, :family, :base_hp, :base_attack, :base_defense, :base_special_attack, :base_special_defense, :base_speed, :type_name_1, :type_name_2)";
$stmt = $database->prepare($sql);

foreach($json as $dexNo => $pokemon) {
    $data = array(
        "pokedex_no" => $dexNo,
        "species" => $pokemon["Species"],
        "form" => $pokemon["Form"],
        "family" => $pokemon["EvolutionStages"][0]["Species"],
        "base_hp" => $pokemon["BaseStats"]["HP"],
        "base_attack" => $pokemon["BaseStats"]["Attack"],
        "base_defense" => $pokemon["BaseStats"]["Defense"],
        "base_special_attack" => $pokemon["BaseStats"]["SpecialAttack"],
        "base_special_defense" => $pokemon["BaseStats"]["SpecialDefense"],
        "base_speed" => $pokemon["BaseStats"]["Speed"],
        "type_name_1" => $pokemon["Types"][0],
        "type_name_2" => sizeof($pokemon["Types"]) > 1 ? $pokemon["Types"][1] : ""
    );

    $stmt->execute($data);
}