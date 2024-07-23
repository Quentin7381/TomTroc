<?php

namespace Manager;

use Manager\AbstractManager;
use PDO;
use Entity\Picture;

class PictureManager extends AbstractManager
{

    public function exists($picture) : bool|Picture
    {
        $sql = 'SELECT * FROM picture WHERE name = :name OR id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $picture->name, 'id' => $picture->id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $picture->fromDb($result);
            return $picture;
        }

        return false;
    }

    public function getByName($name) : Picture|bool
    {
        $sql = 'SELECT * FROM picture WHERE name = :name';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $picture = new Picture();
            $picture->fromDb($result);
            return $picture;
        }

        return false;
    }
}
