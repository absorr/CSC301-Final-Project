SELECT DISTINCT d.pokedex_id, pokedex_no, species, form, family, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, type_name_1, type_name_2
FROM final_pokedex d
JOIN final_pokemon fp on d.pokedex_id = fp.pokedex_id