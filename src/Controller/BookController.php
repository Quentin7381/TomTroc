<?php

namespace Controller;

use Entity\Book;
use Entity\Image;
use Entity\User;
use Utils\PDO;

class BookController extends AbstractController
{

    protected static $instance;
    protected $baseUrl = '/book';
    protected function initRoutes()
    {

    }

    public function provide_lasts()
    {
        $pdo = PDO::getInstance();
        $sql = "SELECT * FROM book ORDER BY created DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $data = [
            'stmt' => $stmt,
            'fetch' => $stmt->fetch(PDO::FETCH_ASSOC)
        ];

        $generator = new \Utils\Generator($data);
        $generator->current_set_callback(function ($data, $position) {
            $fetch = $data['fetch'];

            if ($fetch === false) {
                return false;
            }

            $book = new Book();
            $book->fromDb($fetch);
            return $book;
        });

        $generator->valid_set_callback(function (&$data, $position) {
            return $data['fetch'] !== false;
        });

        $generator->next_set_callback(function (&$data, $position) {
            $data['fetch'] = $data['stmt']->fetch(PDO::FETCH_ASSOC);
        });

        return $generator;
    }
}
