<?php

namespace Test\UserStories;

require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\Image;
use Manager\ImageManager;
use Test\Reflection;
use Test\ReflectionInstance;
use Test\TestInit;
use Test\ErrorCatcher;

class ImageTest extends TestInit
{

    public function setUp(): void
    {
        parent::setUp();
        $manager = ImageManager::getInstance();
        $manager->prepareEntityTable();
    }

    # En tant que developpeur, je peux ajouter une image locale à la base de données
    function test_developper_can_add_local_image_to_database()
    {

        // ----- Test ----- //

        $image = new Image();
        $image->name = 'test';
        $image->extension = 'png';
        $image->alt = 'test';
        $image->content = 'blabla';

        $manager = ImageManager::getInstance();
        $id = $manager->insert($image);

        ## L'image est bien récupérable
        $image = $manager->getById($id);
        $this->assertNotEmpty($image);

        ## L'image est bien enregistrée en local
        $this->assertFileExists($image->src);
    }

    # En tant que developpeur, je peux ajouter une image distante à la base de données
    function test_developper_can_add_remote_image_to_database()
    {
        // ----- Test ----- //

        $image = new Image();
        $image->src = 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';
        $image->alt = 'test';
        $image->name = 'test';

        $manager = ImageManager::getInstance();
        $id = $manager->insert($image);

        ## L'image est bien récupérable
        $image = $manager->getById($id);
        $this->assertNotEmpty($image);
    }

    # En tant que développeur, lorsque je sauvegarde une image dont le nom existe déjà, j'obtiens une exception
    function test_developper_cannot_save_image_with_same_name()
    {
        // ----- Test ----- //

        $image1 = new Image();
        $image1->name = 'test';
        $image1->extension = 'png';
        $image1->alt = 'test';

        $image2 = clone $image1;

        $manager = ImageManager::getInstance();
        $manager->insert($image1);

        $this->expectException(\Manager\Exception::class);
        $manager->insert($image2);
    }

    # En tant que développeur, lorsque je tente de sauvegarder une image sans nom, j'obtiens une exception
    function test_developper_cannot_save_image_without_name()
    {
        // ----- Test ----- //

        $image = new Image();
        $image->extension = 'png';
        $image->alt = 'test';

        $manager = ImageManager::getInstance();

        $this->expectException(\Manager\Exception::class);
        $manager->insert($image);
    }

    # En tant que développeur, je peux accéder à une image ...
    function test_developper_can_access_image()
    {
        // ----- Test ----- //

        $image = new Image();
        $image->name = 'test';
        $image->extension = 'png';
        $image->alt = 'test';
        $image->content = 'blabla';

        $manager = ImageManager::getInstance();
        $id = $manager->insert($image);

        ## ... via son id
        $image = $manager->getById($id);
        $this->assertNotEmpty($image);

        ## ... via son nom
        $image = $manager->search([
            'name' => [
                'value' => 'test',
                'operator' => '='
            ]
            ]);
        $this->assertNotEmpty($image);
    }

    # En tant que développeur, je peux modifier une image
    function test_developper_can_update_image()
    {
        // ----- Test ----- //

        $image = new Image();
        $image->name = 'test';
        $image->extension = 'png';
        $image->alt = 'test';
        $image->content = 'blabla';

        $manager = ImageManager::getInstance();
        $id = $manager->insert($image);

        $image = $manager->getById($id);
        $image->alt = 'test2';
        $manager->update($image);

        $image = $manager->getById($id);
        $this->assertEquals('test2', $image->alt);
    }

    # En tant que développeur, je peux supprimer une image
    function test_developper_can_delete_image()
    {
        // ----- Test ----- //

        $image = new Image();
        $image->name = 'test';
        $image->extension = 'png';
        $image->alt = 'test';
        $image->content = 'blabla';

        $manager = ImageManager::getInstance();
        $id = $manager->insert($image);
        $manager->delete($id);

        $image = $manager->getById($id);
        $this->assertEmpty($image);
    }

}
