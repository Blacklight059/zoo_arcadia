<?php

namespace App\Controller;

use App\Document\Review;
use App\Form\ReviewType;
use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use App\Repository\OpeninghoursRepository;
use App\Repository\ServiceRepository;
use App\Services\AnimalVisitService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;


class HomepageController extends AbstractController
{
    private AnimalVisitService $animalVisitService;

    public function __construct(AnimalVisitService $animalVisitService)
    {
        $this->animalVisitService = $animalVisitService;

    }


    #[Route('/', name: 'app_homepage')]
    public function index(
        HabitatRepository $habitatRepository,
        ServiceRepository $serviceRepository,
        OpeninghoursRepository $openinghoursRepository, 
        Request $request, 
        DocumentManager $dm,
        LoggerInterface $logger
        ): Response
    {
        $logger->info(json_encode(json_decode($request->getContent(), true)));
        $openingHours = $openinghoursRepository->findAll();

        $reviews = $dm->getRepository(Review::class)->findBy(['validate' => true]);
        $habitats = $habitatRepository->findAll();
        $services = $serviceRepository->findAll();
        $role = null;

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'openingHours' => $openingHours,
            'habitats' => $habitats,
            'services' => $services,
            'role' => $role,
            'form' => $form->createView(),
            'reviews' => $reviews,

        ]);
    }

    #[Route('/submit-review', name: 'app_submit_review', methods: ['POST'])]
    public function submitReview(Request $request, DocumentManager $dm): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($review);
            $dm->flush();

            return $this->json(['message' => 'Merci d\'avoir laissé un avis'], 200);
        }

        return $this->json(['message' => 'Erreur lors de la soumission du formulaire'], 400);
    }

    #[Route('/services', name: 'app_services')]
    public function service(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('homepage/service.html.twig', [
            'controller_name' => 'ServicesController',
            'services' => $services
        ]);
    }

    #[Route('/services/{id}', name: 'serviceDetail')]
    public function servicesDetail(        
        serviceRepository $serviceRepository,
        int $id=null
    ): Response
    {
        $service = $serviceRepository->findBy(['id' => $id])[0];

        return $this->render('homepage/service_detail.html.twig', [
            'controller_name' => 'serviceDetail',
            'service' => $service,
        ]);
    }

    #[Route('/habitats', name: 'app_habitats')]
    public function habitats(Request $request, HabitatRepository $habitatRepository): Response
    {
        dump($request);
        $habitats = $habitatRepository->findAll();

        return $this->render('homepage/habitat.html.twig', [
            'controller_name' => 'HabitatsController',
            'habitats' => $habitats

        ]);
    }

    #[Route('/habitats/{id}', name: 'habitatDetail')]
    public function habitatsDetail(        
        habitatRepository $habitatRepository,
        int $id=null
    ): Response
    {
        $habitat = $habitatRepository->findBy(['id' => $id])[0];

        $animal = $habitat->getAnimals();
        return $this->render('homepage/habitat_detail.html.twig', [
            'controller_name' => 'habitatDetail',
            'habitat' => $habitat,
        ]);
    }

    #[Route('/animals/{id}', name: 'animalsDetail')]
    public function animalDetail(        
        AnimalRepository $animalRepository,
        int $id=null
    ): Response
    {
        $animal = $animalRepository->findBy(['id' => $id])[0];

        $vetReports = $animal->getVetReports()->toArray();
        usort($vetReports, function($a, $b) {
            return $b->getVisitDate() <=> $a->getVisitDate();
        });

        // Récupérer le dernier rapport vétérinaire s'il existe
        $latestReport = !empty($vetReports) ? $vetReports[0] : null;


        if (!$animal) {
            throw $this->createNotFoundException('The animal does not exist');
        }

        // Increment the visit counter for the animal
        $this->animalVisitService->incrementVisit($animal->getFirstname());

        // Get the number of visits
        $visits = $this->animalVisitService->getVisits($animal->getFirstname());

        return $this->render('homepage/animal_detail.html.twig', [
            'controller_name' => 'animalDetail',
            'animal' => $animal,
            'visits' => $visits,
            'latestReport' => $latestReport,
        ]);
    }

}

