<?php

include_once '../config.php';

$json = json_decode(file_get_contents('moves.json'), true);
$sql = "UPDATE final_moves SET db=:db WHERE name=:name";
$stmt = $database->prepare($sql);

foreach ($json as $name => $moveJson) {
    if (!isset($moveJson['DB'])) continue;

    $data = array(
        "db" => $moveJson['DB'],
        "name" => $name
    );
    $stmt->execute($data);
}