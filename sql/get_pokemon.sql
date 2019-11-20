SELECT pokemon_id, nickname, p.pokedex_id, level, added_attack, added_defense, added_special_attack, added_special_defense, added_speed, added_hp,
       pokedex_no, species, form, family, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, type_name_1, type_name_2
FROM final_pokemon p
JOIN final_pokedex fp on p.pokedex_id = fp.pokedex_id
WHERE pokemon_id = :pokemon_id;