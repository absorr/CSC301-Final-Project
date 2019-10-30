<?php

include_once '../_common/config.php';

$json = json_decode(file_get_contents('ptu_pokedex_1_05.json'), true);
$sql = "INSERT INTO final_type_rel (type_name_def, type_name_atk, eff_multiplier) VALUES (:def, :atk, :multi)";
$stmt = $database->prepare($sql);

foreach (array_keys($json) as $atk) {
    foreach ($atk as $def => $multi) {
        $data = array(
            "atk" => $atk,
            "def" => $def,
            "multi" => $multi
        );
        $stmt->execute($data);
    }
}