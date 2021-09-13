<?php namespace Models;

use Phalcon\Mvc\Model;

class HouseRoom extends Model
{
    public ?int $house = null;

    public ?int $room = null;

    public function initialize() {
        $this->setSource('house_room');
    }

}
