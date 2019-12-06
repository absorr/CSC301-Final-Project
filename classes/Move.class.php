<?php


class Move
{
    public $move_id;
    public $name;
    public $effect;
    public $freq;
    public $class;
    public $range;
    public $contest_type;
    public $contest_effect;
    public $crits_on;
    public $type;
    public $triggers;
    public $db;

    public function __construct($move_id, $name, $type, $effect = null, $freq = null, $class = null, $range = null,
                                $contest_type = null, $contest_effect = null, $crits_on = null, $triggers = null, $db = null)
    {
        $this->move_id = $move_id;
        $this->name = $name;
        $this->effect = $effect;
        $this->freq = $freq;
        $this->class = $class;
        $this->range = $range;
        $this->contest_type = $contest_type;
        $this->contest_effect = $contest_effect;
        $this->crits_on = $crits_on;
        $this->type = $type;
        $this->triggers = $triggers;
        $this->db = $db;
    }
}