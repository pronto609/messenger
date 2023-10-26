<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Message\DeleteImagePost;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImagePostManagerHandler implements MessageHandlerInterface
{
    /**
     * @var PhotoFileManager
     */
    private $photoManager;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        PhotoFileManager $photoManager,
        EntityManagerInterface $entityManager
    ) {
        $this->photoManager = $photoManager;
        $this->entityManager = $entityManager;
    }

    public function __invoke(DeleteImagePost $deleteImagePost)
    {
        $this->photoManager->deleteImage($deleteImagePost->getImagePost()->getFilename());

        $this->entityManager->remove($deleteImagePost->getImagePost());
        $this->entityManager->flush();
    }
}