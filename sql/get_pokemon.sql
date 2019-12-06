SELECT pokemon_id, nickname, p.pokedex_id, level, added_attack, added_defense, added_special_attack, added_special_defense, added_speed, added_hp,
       pokedex_no, species, form, family, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, type_name_1, type_name_2,
       m1.move_id as move_id_1, m1.name as move_name_1, m1.type as move_type_1,
       m2.move_id as move_id_2, m2.name as move_name_2, m2.type as move_type_2,
       m3.move_id as move_id_3, m3.name as move_name_3, m3.type as move_type_3,
       m4.move_id as move_id_4, m4.name as move_name_4, m4.type as move_type_4
FROM final_pokemon p
JOIN final_pokedex fp on p.pokedex_id = fp.pokedex_id
LEFT JOIN final_moves m1 on p.move_id_1 = m1.move_id
LEFT JOIN final_moves m2 on p.move_id_2 = m2.move_id
LEFT JOIN final_moves m3 on p.move_id_3 = m3.move_id
LEFT JOIN final_moves m4 on p.move_id_4 = m4.move_id
WHERE pokemon_id = :pokemon_id;