<?php

namespace App\Controller;

use App\Entity\Veterinarian;
use App\Form\VeterinarianType;
use App\Repository\VeterinarianRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/veterinarian')]
class VeterinarianController extends AbstractController
{
    #[Route('/', name: 'app_veterinarian_index', methods: ['GET'])]
    public function index(VeterinarianRepository $veterinarianRepository): Response
    {
        return $this->render('veterinarian/index.html.twig', [
            'veterinarians' => $veterinarianRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_veterinarian_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        MailerService $mailer

    ): Response
    {
        $veterinarian = new Veterinarian();
        $form = $this->createForm(VeterinarianType::class, $veterinarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($veterinarian);
            $entityManager->flush();

            $mailer->sendAccountCreationEmail($veterinarian->getEmail());

            $this->addFlash('success', 'Le compte a été créé avec succès. Un email a été envoyé avec les détails du compte.');

            return $this->redirectToRoute('app_veterinarian_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('veterinarian/new.html.twig', [
            'veterinarian' => $veterinarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_veterinarian_show', methods: ['GET'])]
    public function show(Veterinarian $veterinarian): Response
    {
        return $this->render('veterinarian/show.html.twig', [
            'veterinarian' => $veterinarian,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_veterinarian_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Veterinarian $veterinarian, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VeterinarianType::class, $veterinarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_veterinarian_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('veterinarian/edit.html.twig', [
            'veterinarian' => $veterinarian,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_veterinarian_delete', methods: ['POST'])]
    public function delete(Request $request, Veterinarian $veterinarian, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$veterinarian->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($veterinarian);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_veterinarian_index', [], Response::HTTP_SEE_OTHER);
    }
}
