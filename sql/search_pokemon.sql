SELECT pokemon_id, nickname, p.pokedex_id, level, added_attack, added_defense, added_special_attack, added_special_defense, added_speed, added_hp
FROM final_pokemon p
JOIN final_pokedex fp on p.pokedex_id = fp.pokedex_id
WHERE nickname LIKE :search
OR fp.type_name_1 LIKE :search
OR fp.type_name_2 LIKE :search;