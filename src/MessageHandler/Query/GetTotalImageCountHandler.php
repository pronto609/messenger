<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetTotalImageCount;
use App\Repository\ImagePostRepository;

class GetTotalImageCountHandler
{
    /**
     * @var ImagePostRepository
     */
    private $imagePostRepository;

    public function __construct(ImagePostRepository $imagePostRepository)
    {
        $this->imagePostRepository = $imagePostRepository;
    }

    public function __invoke(GetTotalImageCount $getTotalImageCount)
    {
        return count($this->imagePostRepository->findAll());
    }
}