<?php

namespace App\Services;

use App\Document\AnimalVisit;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;

class AnimalVisitService
{
    private DocumentManager $documentManager;
    private LoggerInterface $logger;

    public function __construct(DocumentManager $documentManager, LoggerInterface $logger)
    {
        $this->documentManager = $documentManager;
        $this->logger = $logger;
    }

    public function incrementVisit(string $animalName): void
    {
        $this->logger->info("Incrementing visit for animal: $animalName");

        $animalVisit = $this->documentManager
            ->getRepository(AnimalVisit::class)
            ->findOneBy(['animalName' => $animalName]);

        if (!$animalVisit) {
            $animalVisit = new AnimalVisit();
            $animalVisit->setAnimalName($animalName);
            $this->logger->info("Creating new visit record for animal: $animalName");
        }

        $animalVisit->incrementVisits();
        $this->documentManager->persist($animalVisit);
        $this->documentManager->flush();

        $this->logger->info("Visit count for animal $animalName is now: {$animalVisit->getVisits()}");
    }

    public function getVisits(string $animalName): int
    {
        $animalVisit = $this->documentManager
            ->getRepository(AnimalVisit::class)
            ->findOneBy(['animalName' => $animalName]);

        return $animalVisit ? $animalVisit->getVisits() : 0;
    }
}
