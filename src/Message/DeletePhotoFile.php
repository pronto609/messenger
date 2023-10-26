<?php

namespace App\Message;

use App\Entity\ImagePost;

class DeletePhotoFile
{

    /**
     * @var string
     */
    private $imagePostFile;

    public function __construct(string $imagePostFile)
    {
        $this->imagePostFile = $imagePostFile;
    }

    /**
     * @return string
     */
    public function getImagePostFile(): string
    {
        return $this->imagePostFile;
    }

}