<?php

namespace App\Controller;

use App\Entity\Habitat;
use App\Entity\Image;
use App\Form\HabitatType;
use App\Repository\HabitatRepository;
use App\Repository\ImageRepository;
use App\ServiceImages\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/habitat')]
class HabitatController extends AbstractController
{
    #[Route('/', name: 'app_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $habitatRepository): Response
    {
        $habitat = $habitatRepository->findAll();

        return $this->render('habitat/index.html.twig', [
            'habitats' => $habitatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_habitat_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        PictureService $pictureService,

        ): Response
    {
        $habitat = new Habitat();

        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $images = $form->get('images')->getData();

            foreach($images as $image){

                $folder = 'habitat_images/';
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $habitat->addImage($img);
            }
            
            $entityManager->persist($habitat);
            $entityManager->flush();

            return $this->redirectToRoute('app_habitat_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('habitat/new.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitat_show', methods: ['GET'])]
    public function show(Habitat $habitat): Response
    {
        return $this->render('habitat/show.html.twig', [
            'habitat' => $habitat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_habitat_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Habitat $habitat, 
        HabitatRepository $habitatRepository,
        EntityManagerInterface $entityManager,
        ImageRepository $imagesRepository,
        PictureService $pictureService,
        int $id=null
    ): Response
    {
        $habitat = $habitatRepository->findBy(['id' => $id])[0];
        $oldImages = $imagesRepository->findBy(['habitat' => $id]);

        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($oldImages as $oldImage) {
                $entityManager->remove($oldImage);            
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){

                $folder = 'habitat_images/';
                // On génère un nouveau nom de fichier
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $habitat->addImage($img);
        
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitat/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitat_delete', methods: ['POST'])]
    public function delete(Request $request, Habitat $habitat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitat->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($habitat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_habitat_index', [], Response::HTTP_SEE_OTHER);
    }
}
