<?php

namespace App\Controller;

use App\Document\Review;
use App\Form\ReviewType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/reviews', name: 'review_list', methods: ['GET'])]
    public function list(DocumentManager $dm): Response
    {
        $reviews = $dm->getRepository(Review::class)->findAll();

        return $this->render('review/list.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    #[Route('/review/validate/{id}', name: 'review_validate', methods: ['POST'])]
    public function validateReview(DocumentManager $dm, Review $review): Response
    {
        $review->setValidate(true);
        $dm->flush();

        return $this->redirectToRoute('review_list');
    }

    #[Route('/review/delete/{id}', name: 'review_delete', methods: ['POST'])]
    public function deleteReview(DocumentManager $dm, Review $review): Response
    {
        $dm->remove($review);
        $dm->flush();

        return $this->redirectToRoute('review_list');
    }

    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function homepage(DocumentManager $dm): Response
    {
        $validatedReviews = $dm->getRepository(Review::class)->findBy(['validate' => true]);

        return $this->render('home/index.html.twig', [
            'reviews' => $validatedReviews,
        ]);
    }
}
