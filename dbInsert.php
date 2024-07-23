<?php

$RESET_DB = true;

require_once 'src/initialize.php';

use Variables\Provider;
use Config\Config;

use Entity\Image;
use Entity\User;
use Entity\Book;
use Entity\Picture;

$provider = Provider::I();

$config = Config::getInstance();
$config->load(__DIR__);

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
$user->persist();

$book = new Book();
$book->title = 'The Kinfolk Home';
$book->author = 'Nathan Williams';
$book->description = 'The Kinfolk Home welcomes readers into 35 homes around the world that reflect some of the key principles of slow living: cultivating community, simplifying our lives and reclaiming time for what matters most.';
$book->cover = $image;
$book->seller = $user;

for ($i = 0; $i < 10; $i++) {
    $entity = clone $book;
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

$user = new User();
$user->name = 'John Doe';
$user->email = 'jhon.doe@example.com';
$user->password = 'Abcd.1234';
