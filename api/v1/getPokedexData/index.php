<?php
include_once '../../../config.php';

$pokedexNo = $_GET['pokedexNo'];

$sql = file_get_contents('../../sql/get_pokedex_info.sql');
$stmt = $database->prepare($sql);
$stmt->execute(array(
    "pokedexNo" => $pokedexNo
));

$dexInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($dexInfo[0], JSON_PRETTY_PRINT);