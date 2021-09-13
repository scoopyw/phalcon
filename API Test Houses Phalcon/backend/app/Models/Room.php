<?php namespace Models;

use Phalcon\Mvc\Model;

class Room extends Model
{
    public ?int $id = null;

    public ?string $name = null;

    public function initialize() {
        $this->setSource('room');
    }

}
