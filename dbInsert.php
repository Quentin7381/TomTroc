<?php

$RESET_DB = false;

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

// ----- TESTS ----- //

// ----- JEU DE LIVRES ----- //

$image = new Image();
$image->src = '/assets/img/the-kinfolk.png';
$image->alt = 'the kinfolk';
$image->name = 'the kinfolk';
$image->persist();

$user = new User();
$user->name = 'John Doe';
$user->email = 'jhon.doe@mail.com';
$user->password = 'Abcd.1234';
var_dump($user->password);
$user->persist();

$book = new Book();
$book->title = 'The Kinfolk Home';
$book->author = 'Nathan Williams';
$book->description = 'The Kinfolk Home welcomes readers into 35 homes around the world that reflect some of the key principles of slow living: cultivating community, simplifying our lives and reclaiming time for what matters most.';
$book->cover = $image;
$book->seller = $user;

for ($i = 0; $i < 10; $i++) {
    $entity = clone $book;
    $entity->available = $i % 2 === 0;
    $entity->persist();
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

// ----- UTILISATEURS DU SITE ----- //

$user2 = new User();
$user2->name = 'Jane Doe';
$user2->email = 'jane.doe@example.com';
$user2->password = 'Abcd.1234';
$user2->persist();

// ----- MESSAGE DU SITE ----- //

$message = new Message();
$message->sender = $user;
$message->receiver = $user2;
$message->checked = false;

for ($i = 0; $i < 10; $i++) {
    $entity = clone $message;
    $entity->date = time() - $i * 3600;
    $entity->content = 'Hello World ' . $i;
    $entity->persist();
}