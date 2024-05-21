<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Image;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use App\Repository\ImageRepository;
use App\Services\AnimalVisitService;
use App\ServiceImages\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/animal')]
class AnimalController extends AbstractController
{
    private AnimalVisitService $animalVisitService;

    public function __construct(AnimalVisitService $animalVisitService)
    {
        $this->animalVisitService = $animalVisitService;
    }

    #[Route('/', name: 'app_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        $animals = $animalRepository->findAll();
        return $this->render('animal/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_animal_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        PictureService $pictureService,

    ): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){

                $folder = 'animal_images/';
                // On génère un nouveau nom de fichier
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $animal->addImage($img);
            }
            
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_animal_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Animal $animal, 
        AnimalRepository $animalRepository,
        ImageRepository $imagesRepository,
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
        int $id=null
    ): Response
    {
        $animal = $animalRepository->findBy(['id' => $id])[0];
        $oldImages = $imagesRepository->findBy(['animal' => $id]);

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($oldImages as $oldImage) {
                $entityManager->remove($oldImage);            
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){

                $folder = 'animal_images/';
                // On génère un nouveau nom de fichier
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $animal->addImage($img);
        
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'animalsDetail', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        // Increment the visit counter for the animal
        $this->animalVisitService->incrementVisit($animal->getFirstname());

        // Get the number of visits
        $visits = $this->animalVisitService->getVisits($animal->getFirstname());

        return $this->render('animal/show.html.twig', [
            'animal' => $animal,
            'visits' => $visits,
        ]);
    }
}
