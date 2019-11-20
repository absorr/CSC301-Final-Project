<?php


class Pokedex
{
    static private $CACHE = array();

    /**
     * @param $id
     * @return Pokedex
     */
    static function getPokedexEntryById($id) {
        return self::$CACHE[$id];
    }

    protected $pokedex_id;
    protected $pokedex_no;
    protected $species;
    protected $form;
    protected $family;
    protected $base_hp;
    protected $base_attack;
    protected $base_defense;
    protected $base_special_attack;
    protected $base_special_defense;
    protected $base_speed;
    protected $type_name_1;
    protected $type_name_2;

    public function __construct($pokedex_id, $pokedex_no, $species, $form, $family, $base_hp, $base_attack, $base_defense,
                                $base_special_attack, $base_special_defense, $base_speed, $type_name_1, $type_name_2) {

        if (array_key_exists($pokedex_id, self::$CACHE)) {
            return self::getPokedexEntryById($pokedex_id);
        }

        $this->pokedex_id = $pokedex_id;
        $this->pokedex_no = $pokedex_no;
        $this->species = $species;
        $this->form = $form;
        $this->family = $family;
        $this->base_hp = $base_hp;
        $this->base_attack = $base_attack;
        $this->base_defense = $base_defense;
        $this->base_special_attack = $base_special_attack;
        $this->base_special_defense = $base_special_defense;
        $this->base_speed = $base_speed;
        $this->type_name_1 = $type_name_1;
        $this->type_name_2 = $type_name_2;

        self::$CACHE[$pokedex_id] = $this;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPokedexId()
    {
        return $this->pokedex_id;
    }

    /**
     * @return mixed
     */
    public function getPokedexNo()
    {
        return $this->pokedex_no;
    }

    /**
     * @return mixed
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return mixed
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @return mixed
     */
    public function getBaseHp()
    {
        return $this->base_hp;
    }

    /**
     * @return mixed
     */
    public function getBaseAttack()
    {
        return $this->base_attack;
    }

    /**
     * @return mixed
     */
    public function getBaseDefense()
    {
        return $this->base_defense;
    }

    /**
     * @return mixed
     */
    public function getBaseSpecialAttack()
    {
        return $this->base_special_attack;
    }

    /**
     * @return mixed
     */
    public function getBaseSpecialDefense()
    {
        return $this->base_special_defense;
    }

    /**
     * @return mixed
     */
    public function getBaseSpeed()
    {
        return $this->base_speed;
    }

    /**
     * @return mixed
     */
    public function getTypeName1()
    {
        return $this->type_name_1;
    }

    /**
     * @return mixed
     */
    public function getTypeName2()
    {
        return $this->type_name_2;
    }

    public function getDisplayTypes() {
        return empty($this->type_name_2) ?
            $this->type_name_1 :
            $this->type_name_1 . '/' . $this->type_name_2;
    }


}