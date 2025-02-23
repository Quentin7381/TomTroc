<?php

namespace Manager;

use Manager\AbstractManager;
use PDO;
use Entity\Image;
use Entity\AbstractEntity;

class ImageManager extends AbstractManager
{

    // public function exists($image) : bool|Image
    // {
    //     $sql = 'SELECT * FROM image WHERE name = :name OR id = :id';
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute(['name' => $image->name, 'id' => $image->id]);
        
    //     $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($result) {
    //         $image->fromDb($result);
    //         return $image;
    //     }

    //     return false;
    // }

    public function getByName(string $name) : Image|bool
    {
        $sql = 'SELECT * FROM image WHERE name = :name';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $image = new Image();
            $image->fromDb($result);
            return $image;
        }

        return false;
    }

    public function delete(AbstractEntity|int $id) : void
    {
        $entity = $id instanceof Image ? $id : $this->getById($id);
        parent::delete($id);
        @unlink($entity->src);
    }

    public function toDb(AbstractEntity $entity) : array
    {
        $array = parent::toDb($entity);
        $ignore = ['content'];
        foreach ($ignore as $key) {
            unset($array[$key]);
        }
        return $array;
    }



    public static function typeof_name()
    {
        return 'varchar(255) UNIQUE';
    }
}
