<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ImageRepository;
use App\Repository\ServiceRepository;
use App\ServiceImages\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
        
        ): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){

                $folder = 'service_images/';
                // On génère un nouveau nom de fichier
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $service->addImage($img);
            }
            
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Service $service,
        ServiceRepository $serviceRepository,
        ImageRepository $imagesRepository,
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
        int $id=null
        ): Response
    {
        $service = $serviceRepository->findBy(['id' => $id])[0];
        $oldImages = $imagesRepository->findBy(['service' => $id]);

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($oldImages as $oldImage) {
                $entityManager->remove($oldImage);            
            }

            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach($images as $image){

                $folder = 'service_images/';
                // On génère un nouveau nom de fichier
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $service->addImage($img);
        
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        
        }

    
        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
    
    

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
