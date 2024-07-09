<?php

namespace Manager;

use Manager\AbstractManager;
use PDO;
use Entity\Image;
use Entity\AbstractEntity;

class ImageManager extends AbstractManager
{

    public function exists($image) : bool|Image
    {
        $sql = 'SELECT * FROM image WHERE name = :name OR id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $image->name, 'id' => $image->id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $image->fromDb($result);
            return $image;
        }

        return false;
    }

    public function getByName($name) : Image|bool
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
}
