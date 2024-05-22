<?php

namespace App\Services;

use App\Document\AnimalVisit;
use Doctrine\ODM\MongoDB\DocumentManager;

class AnimalVisitService
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function incrementVisit(string $animalName): void
    {

        $animalVisit = $this->documentManager
            ->getRepository(AnimalVisit::class)
            ->findOneBy(['animalName' => $animalName]);

        if (!$animalVisit) {
            $animalVisit = new AnimalVisit();
            $animalVisit->setAnimalName($animalName);
        }

        $animalVisit->incrementVisits();
        $this->documentManager->persist($animalVisit);
        $this->documentManager->flush();

    }

    public function getVisits(string $animalName): int
    {
        $animalVisit = $this->documentManager
            ->getRepository(AnimalVisit::class)
            ->findOneBy(['animalName' => $animalName]);

        return $animalVisit ? $animalVisit->getVisits() : 0;
    }
}
