<?php

namespace Entity;

class Book extends AbstractEntity {

    protected string $title;
    protected string $author;
    protected string $description;
    protected Image $cover;
    protected User $seller;

}
