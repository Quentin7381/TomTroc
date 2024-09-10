<?php

require 'src/initialize.php';

use Variables\Provider;
use Config\Config;

use Entity\Image;
use Entity\User;
use Entity\Book;
use Entity\Picture;
use Entity\Message;
use Utils\Database;

$provider = Provider::I();

$config = Config::getInstance();
$config->load(__DIR__);

$db = Database::getInstance();
$db->truncate();

// ----- IMAGES ----- //

$image = new Image();
$image->src = '/assets/img/cover/the-kinfolk.png';
$image->alt = 'the kinfolk';
$image->name = 'the kinfolk';
$image->persist();

$image2 = new Image();
$image2->src = '/assets/img/cover/i-love-coding.png';
$image2->alt = 'i love coding';
$image2->name = 'i love coding';
$image2->persist();

$image3 = new Image();
$image3->src = '/assets/img/cover/i-love-biology.jpg';
$image3->alt = 'i love biology';
$image3->name = 'i love biology';
$image3->persist();

// ----- UTILISATEURS DU SITE ----- //

$user = new User();
$user->name = 'John Doe';
$user->email = 'jhon.doe@example.com';
$user->password = 'Abcd.1234';
var_dump($user->password);
$user->persist();

$user2 = new User();
$user2->name = 'Jane Doe';
$user2->email = 'jane.doe@example.com';
$user2->password = 'Abcd.1234';
$user2->persist();

$user3 = new User();
$user3->name = 'Alice Doe';
$user3->email = 'alice.doe@example.com';
$user3->password = 'Abcd.1234';
$user3->persist();

// ----- JEU DE LIVRES ----- //

$book = new Book();
$book->title = 'The Kinfolk Home';
$book->author = 'Nathan Williams';
$book->description = 'The Kinfolk Home welcomes readers into 35 homes around the world that reflect some of the key principles of slow living: cultivating community, simplifying our lives and reclaiming time for what matters most.';
$book->cover = $image;
$book->seller = $user;

$book2 = new Book();
$book2->title = 'I love coding';
$book2->author = 'John Doe';
$book2->description = 'This book is about coding';
$book2->cover = $image2;
$book2->seller = $user2;

$book3 = new Book();
$book3->title = 'I love biology';
$book3->author = 'Jane Doe';
$book3->description = 'This book is about biology';
$book3->cover = $image3;
$book3->seller = $user3;

$books = [$book, $book2, $book3, $book, $book2, $book3, $book, $book2, $book3, $book, $book2, $book3, $book];

$i = 0;
foreach ($books as $book) {
    $book->available = ++$i % 2 === 0;
    $book = clone $book;
    $book->persist();
}

// ----- IMAGES DU SITE ----- //

$images = glob('assets/img/*');
foreach ($images as $path) {
    $image = new Image();
    $image->src = '/assets/img/' . basename($path);
    $image->alt = basename($path);
    $image->name = pathinfo($path, PATHINFO_FILENAME);
    $image->persist();
}

// ----- PICUTRES DU SITE ----- //

$picture = new Picture();
$picture->name = 'banner';
$picture->addImage('banner');
$picture->addImage('banner--phone', 768);
$picture->persist();

// ----- MESSAGE DU SITE ----- //

$message = new Message();
$message->sender = $user;
$message->receiver = $user2;
$message->checked = false;

for ($i = 0; $i < 10; $i++) {
    $entity = clone $message;
    $entity->date = time() - $i * 3600;
    $entity->content = 'Hello World ' . $i;

    if ($i % 2 === 0) {
        $entity->sender = $user2;
        $entity->receiver = $user;
    }

    $entity->persist();
}

for ($i = 10; $i < 20; $i++) {
    $entity = clone $message;
    $entity->date = time() - $i * 3600;
    $entity->content = 'Hello World ' . $i;
    $entity->receiver = $user3;

    if ($i % 2 === 0) {
        $entity->sender = $user3;
        $entity->receiver = $user;
    }

    $entity->persist();
}