<?php

$RESET_DB = true;

require_once 'src/initialize.php';

use Variables\Provider;
use Config\Config;
use Utils\View;
use Utils\PDO;

use Entity\Image;
use Entity\User;
use Entity\Book;

use Manager\UserManager;
use Manager\BookManager;
use Manager\ImageManager;

$provider = Provider::I();

$config = Config::getInstance();
$config->load(__DIR__);

$userManager = UserManager::getInstance();
$bookManager = BookManager::getInstance();
$imageManager = ImageManager::getInstance();

$image = new Image();
$image->src = '/assets/img/the-kinfolk.png';
$image->alt = 'the kinfolk';
$image->name = 'the kinfolk';
$imageManager->insert($image);

$user = new User();
$user->name = 'John Doe';
$user->email = 'jhon.doe@mail.com';
$user->password = 'Abcd.1234';
$userManager->persist($user);

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
