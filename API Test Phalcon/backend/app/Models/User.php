<?php namespace Models;

use Phalcon\Mvc\Model;

class User extends Model
{
    public ?int $id = null;

    public ?string $username = null;

    public ?string $password = null;

    public function initialize() {
        $this->setSource('user');
    }

}
