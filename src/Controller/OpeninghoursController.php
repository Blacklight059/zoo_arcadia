<?php

namespace App\Controller;

use App\Entity\Openinghours;
use App\Form\OpeninghoursType;
use App\Repository\OpeninghoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/openinghours')]
class OpeninghoursController extends AbstractController
{
    #[Route('/', name: 'app_openinghours_index', methods: ['GET'])]
    public function index(OpeninghoursRepository $openinghoursRepository): Response
    {
        return $this->render('openinghours/index.html.twig', [
            'openinghours' => $openinghoursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_openinghours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $openinghour = new Openinghours();
        $form = $this->createForm(OpeninghoursType::class, $openinghour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($openinghour);
            $entityManager->flush();

            return $this->redirectToRoute('app_openinghours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('openinghours/new.html.twig', [
            'openinghour' => $openinghour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_openinghours_show', methods: ['GET'])]
    public function show(Openinghours $openinghour): Response
    {
        return $this->render('openinghours/show.html.twig', [
            'openinghour' => $openinghour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_openinghours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Openinghours $openinghour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpeninghoursType::class, $openinghour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_openinghours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('openinghours/edit.html.twig', [
            'openinghour' => $openinghour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_openinghours_delete', methods: ['POST'])]
    public function delete(Request $request, Openinghours $openinghour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$openinghour->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($openinghour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_openinghours_index', [], Response::HTTP_SEE_OTHER);
    }
}
