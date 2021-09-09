<?php namespace Models;

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di;
use Phalcon\Mvc\Model;

class House extends Model
{
    public ?int $id = null;

    public ?string $name = null;

    public ?int $user = null;

    public array $rooms = [];

    public function initialize() {
        $this->setSource('house');
    }

    public function afterSave() {
        self::deleteRooms($this->id);
        if (is_array($this->rooms) && count($this->rooms) > 1) {
            foreach ($this->rooms as $room) {
                $houseRoom = new HouseRoom();
                $houseRoom->assign([
                    'house' => $this->id,
                    'room' => $room,
                ]);
                $houseRoom->create();
            }
        }
    }

    public function beforeDelete() {
        self::deleteRooms($this->id);
    }

    private static function deleteRooms($id) {
        $deleteQuery = <<<SQL
            DELETE FROM house_room WHERE house = :houseId;
SQL;
        /**
         * @var $db Mysql
         */
        $db = Di::getDefault()->get('db');
        $stmt = $db->prepare($deleteQuery);
        $stmt->execute([
            'houseId' => $id,
        ]);
    }

    private static function getBuilder() {
        return (new House())->getModelsManager()->createBuilder()
            ->from([
                'h' => House::class,
            ])
            ->columns([
                'id' => 'h.id',
                'name' => 'h.name',
                'rooms' => 'GROUP_CONCAT(r.name)',
            ])
            ->leftJoin(HouseRoom::class, 'hr.house = h.id', 'hr')
            ->leftJoin(Room::class, 'r.id = hr.room', 'r')
            ->groupBy('h.id');
    }

    public static function getAll() {
        return self::getBuilder()->getQuery()->execute();
    }

    public static function getHavingRooms($rooms) {
        return self::getBuilder()
            ->leftJoin(HouseRoom::class, 'hf.house = h.id', 'hf')
            ->leftJoin(Room::class, 'rf.id = hf.room', 'rf')
            ->inWhere('rf.name', $rooms)->getQuery()->execute();

    }

}
