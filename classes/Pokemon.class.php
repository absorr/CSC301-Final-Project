<?php


class Pokemon
{
    public $pokemon_id;
    public $nickname;
    public $pokedex_id;
    public $level;
    public $health;
    public $added_attack;
    public $added_defense;
    public $added_special_attack;
    public $added_special_defense;
    public $added_speed;
    public $added_hp;
    public $move1;
    public $move2;
    public $move3;
    public $move4;

    public function __construct($pokemon_id, $pokedex_id, $nickname, $level, $added_hp, $added_attack, $added_defense,
            $added_special_attack, $added_special_defense, $added_speed) {
        $this->pokemon_id = $pokemon_id;
        $this->nickname = $nickname;
        $this->pokedex_id = $pokedex_id;
        $this->level = $level;
        $this->added_attack = $added_attack;
        $this->added_defense = $added_defense;
        $this->added_special_attack = $added_special_attack;
        $this->added_special_defense = $added_special_defense;
        $this->added_speed = $added_speed;
        $this->added_hp = $added_hp;
    }

    public function getPokedex() {
        return Pokedex::getPokedexEntryById($this->pokedex_id);
    }

    public function getHp() {
        return $this->added_hp + $this->getPokedex()->getBaseHp();
    }

    public function getAttack() {
        return $this->added_attack + $this->getPokedex()->getBaseAttack();
    }

    public function getDefense() {
        return $this->added_defense + $this->getPokedex()->getBaseDefense();
    }

    public function getSpecialAttack() {
        return $this->added_special_attack + $this->getPokedex()->getBaseSpecialAttack();
    }

    public function getSpecialDefense() {
        return $this->added_special_defense + $this->getPokedex()->getBaseSpecialDefense();
    }

    public function getSpeed() {
        return $this->added_speed + $this->getPokedex()->getBaseSpeed();
    }

    public function getMaxHealth() {
        return $this->level + ($this->getHp() * 3) + 10;
    }
}