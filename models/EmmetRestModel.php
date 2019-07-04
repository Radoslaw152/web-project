<?php


class EmmetRestModel implements JsonSerializable

{

    private $emmet;

    public function __construct($emmet)
    {
        $this->emmet = $emmet;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}