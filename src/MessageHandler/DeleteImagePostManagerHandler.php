<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Message\DeleteImagePost;
use App\Message\DeletePhotoFile;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteImagePostManagerHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus
    ) {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function __invoke(DeleteImagePost $deleteImagePost)
    {
        $imagePost = $deleteImagePost->getImagePost();
        $imagePostFileName = $imagePost->getFilename();

        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $deletePhotoFile = new DeletePhotoFile($imagePostFileName);
        $this->messageBus->dispatch($deletePhotoFile);
    }
}