<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use App\Repository\ImagePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddPonkaToImageHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

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
    /**
     * @var ImagePostRepository
     */
    private $imagePostRepository;

    public function __construct(
        PhotoPonkaficator $ponkaficator,
        PhotoFileManager $photoManager,
        EntityManagerInterface $entityManager,
        ImagePostRepository $imagePostRepository
    ) {
        $this->ponkaficator = $ponkaficator;
        $this->photoManager = $photoManager;
        $this->entityManager = $entityManager;
        $this->imagePostRepository = $imagePostRepository;
    }

    public function __invoke(AddPonkaToImage $addPonkaToImage)
    {
        /*
         * Start Ponkafication!
         */
        $imagePostId = $addPonkaToImage->getImagePostId();
        $imagePost = $this->imagePostRepository->find($imagePostId);

        if (!$imagePost) {

            if ($this->logger) {
                $this->logger->alert(sprinf('Image post %d was missing!', $imagePostId));
            }
            return;
        }

        if (rand(0, 10) < 8 || true) {
            throw new \Exception('I failed randomly!!!');
        }

        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoManager->read($imagePost->getFilename())
        );
        $this->photoManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();
        $this->entityManager->flush();
        /*
         * You've been Ponkafied!
         */
    }
}