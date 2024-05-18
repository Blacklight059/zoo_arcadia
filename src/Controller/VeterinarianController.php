<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\VeterinarianType;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/veterinarian')]
class VeterinarianController extends AbstractController
{
    #[Route('/', name: 'app_veterinarian_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $veterinarians = $userRepository->findByRole('ROLE_VETERINARIAN');

        return $this->render('veterinarian/index.html.twig', [
            'veterinarians' => $veterinarians,
        ]);
    }

    #[Route('/new', name: 'app_veterinarian_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerService $mailer,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $veterinarian = new User();
        $form = $this->createForm(VeterinarianType::class, $veterinarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $veterinarian->setPassword(
                $userPasswordHasher->hashPassword(
                    $veterinarian,
                    $form->get('plainPassword')->getData()
                )
            );
            $veterinarian->setRoles(['ROLE_VETERINARIAN']);

            $entityManager->persist($veterinarian);
            $entityManager->flush();

            $mailer->sendAccountCreationEmail($veterinarian->getEmail());

            $this->addFlash('success', 'Le compte a été créé avec succès. Un email a été envoyé avec les détails du compte.');

            return $this->redirectToRoute('app_veterinarian_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('veterinarian/new.html.twig', [
            'veterinarian' => $veterinarian,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_veterinarian_show', methods: ['GET'])]
    public function show(User $veterinarian): Response
    {
        return $this->render('veterinarian/show.html.twig', [
            'veterinarian' => $veterinarian,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_User_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $veterinarian,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(VeterinarianType::class, $veterinarian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $veterinarian->setPassword(
                $userPasswordHasher->hashPassword(
                    $veterinarian,
                    $form->get('plainPassword')->getData()
                )
            );
            $veterinarian->setRoles(['ROLE_VETERINARIAN']);

            $entityManager->flush();

            return $this->redirectToRoute('app_veterinarian_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('veterinarian/edit.html.twig', [
            'veterinarian' => $veterinarian,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_veterinarian_delete', methods: ['POST'])]
    public function delete(Request $request, User $veterinarian, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$veterinarian->getId(), $request->request->get('_token'))) {
            $entityManager->remove($veterinarian);
            $entityManager->flush();
            $this->addFlash('success', 'Le vétérinaire a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_veterinarian_index', [], Response::HTTP_SEE_OTHER);
    }
}
