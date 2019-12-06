SELECT pokemon_id, nickname, p.pokedex_id, level, added_attack, added_defense, added_special_attack, added_special_defense, added_speed, added_hp,
       pokedex_no, species, form, family, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, type_name_1, type_name_2,

       m1.move_id as move_id_1, m1.name as move_name_1, m1.effect as move_effect_1, m1.freq as move_freq_1, m1.class as move_class_1,
       m1.`range` as move_range_1, m1.contest_type as move_contest_type_1, m1.contest_effect as move_contest_effect_1,
       m1.crits_on as move_crits_on_1, m1.type as move_type_1, m1.triggers as move_triggers_1, m1.db as move_db_1,

       m2.move_id as move_id_2, m2.name as move_name_2, m2.effect as move_effect_2, m2.freq as move_freq_2, m2.class as move_class_2,
       m2.`range` as move_range_2, m2.contest_type as move_contest_type_2, m2.contest_effect as move_contest_effect_2,
       m2.crits_on as move_crits_on_2, m2.type as move_type_2, m2.triggers as move_triggers_2, m2.db as move_db_2,

       m3.move_id as move_id_3, m3.name as move_name_3, m3.effect as move_effect_3, m3.freq as move_freq_3, m3.class as move_class_3,
       m3.`range` as move_range_3, m3.contest_type as move_contest_type_3, m3.contest_effect as move_contest_effect_3,
       m3.crits_on as move_crits_on_3, m3.type as move_type_3, m3.triggers as move_triggers_3, m3.db as move_db_3,

       m4.move_id as move_id_4, m4.name as move_name_4, m4.effect as move_effect_4, m4.freq as move_freq_4, m4.class as move_class_4,
       m4.`range` as move_range_4, m4.contest_type as move_contest_type_4, m4.contest_effect as move_contest_effect_4,
       m4.crits_on as move_crits_on_4, m4.type as move_type_4, m4.triggers as move_triggers_4, m4.db as move_db_4,

       base_speed + added_speed as initiative
FROM final_pokemon p
         JOIN final_pokedex fp on p.pokedex_id = fp.pokedex_id
         LEFT JOIN final_moves m1 on p.move_id_1 = m1.move_id
         LEFT JOIN final_moves m2 on p.move_id_2 = m2.move_id
         LEFT JOIN final_moves m3 on p.move_id_3 = m3.move_id
         LEFT JOIN final_moves m4 on p.move_id_4 = m4.move_id
WHERE pokemon_id IN (%s)
ORDER BY initiative desc;