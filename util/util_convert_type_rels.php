<?php

include_once '../_common/config.php';

$json = json_decode(file_get_contents('type-effects.json'), true);
$sql = "INSERT INTO final_type_rel (type_name_def, type_name_atk, eff_multiplier) VALUES (:def, :atk, :multi)";
$stmt = $database->prepare($sql);

foreach ($json as $atk => $atkJson) {
    foreach ($atkJson as $def => $multi) {
        $data = array(
            "atk" => $atk,
            "def" => $def,
            "multi" => $multi
        );
        $stmt->execute($data);
    }
}