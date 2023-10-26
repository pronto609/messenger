<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddPonkaToImageHandler implements MessageHandlerInterface
{

    /**
     * @var PhotoPonkaficator
     */
    private $ponkaficator;
    /**
     * @var PhotoFileManager
     */
    private $photoManager;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        PhotoPonkaficator $ponkaficator,
        PhotoFileManager $photoManager,
        EntityManagerInterface $entityManager
    ) {
        $this->ponkaficator = $ponkaficator;
        $this->photoManager = $photoManager;
        $this->entityManager = $entityManager;
    }

    public function __invoke(AddPonkaToImage $addPonkaToImage)
    {
        /*
         * Start Ponkafication!
         */
        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoManager->read($addPonkaToImage->getImagePost()->getFilename())
        );
        $this->photoManager->update($addPonkaToImage->getImagePost()->getFilename(), $updatedContents);
        $addPonkaToImage->getImagePost()->markAsPonkaAdded();
        $this->entityManager->persist($addPonkaToImage->getImagePost());
        $this->entityManager->flush();
        /*
         * You've been Ponkafied!
         */
    }
}