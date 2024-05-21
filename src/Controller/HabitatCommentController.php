<?php

namespace App\Controller;

use App\Entity\HabitatComment;
use App\Form\HabitatCommentType;
use App\Repository\HabitatCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/habitat/comment')]
class HabitatCommentController extends AbstractController
{
    #[Route('/', name: 'app_habitat_comment_index', methods: ['GET'])]
    public function index(HabitatCommentRepository $habitatCommentRepository): Response
    {
        return $this->render('habitat_comment/index.html.twig', [
            'habitat_comments' => $habitatCommentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_habitat_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $habitatComment = new HabitatComment();
        $form = $this->createForm(HabitatCommentType::class, $habitatComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($habitatComment);
            $entityManager->flush();

            return $this->redirectToRoute('app_habitat_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitat_comment/new.html.twig', [
            'habitat_comment' => $habitatComment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitat_comment_show', methods: ['GET'])]
    public function show(HabitatComment $habitatComment): Response
    {
        return $this->render('habitat_comment/show.html.twig', [
            'habitat_comment' => $habitatComment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_habitat_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HabitatComment $habitatComment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HabitatCommentType::class, $habitatComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_habitat_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitat_comment/edit.html.twig', [
            'habitat_comment' => $habitatComment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitat_comment_delete', methods: ['POST'])]
    public function delete(Request $request, HabitatComment $habitatComment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitatComment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($habitatComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_habitat_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}
