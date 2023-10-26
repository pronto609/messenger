<?php

namespace App\Message;

class AddPonkaToImage
{
    /**
     * @var int
     */
    private $imagePostId;

    public function __construct(int $imagePostId)
    {
        $this->imagePostId = $imagePostId;
    }

    /**
     * @return int
     */
    public function getImagePostId(): int
    {
        return $this->imagePostId;
    }
}