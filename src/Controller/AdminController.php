<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Services\AnimalVisitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private AnimalVisitService $animalVisitService;

    public function __construct(AnimalVisitService $animalVisitService)
    {
        $this->animalVisitService = $animalVisitService;
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(AnimalRepository $animalRepository): Response
    {
        $animals = $animalRepository->findAll();
        $animalVisits = [];

        foreach ($animals as $animal) {
            $animalVisits[] = [
                'animal' => $animal,
                'visits' => $this->animalVisitService->getVisits($animal->getFirstname()),
            ];
        }

        return $this->render('admin/dashboard.html.twig', [
            'animalVisits' => $animalVisits,
        ]);
    }
}
