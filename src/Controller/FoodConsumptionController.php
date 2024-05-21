<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\FoodConsumption;
use App\Form\FoodConsumption1Type;
use App\Repository\FoodConsumptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/food/consumption')]
class FoodConsumptionController extends AbstractController
{
    #[Route('/', name: 'app_food_consumption_index', methods: ['GET'])]
    public function index(FoodConsumptionRepository $foodConsumptionRepository): Response
    {
        return $this->render('food_consumption/index.html.twig', [
            'food_consumptions' => $foodConsumptionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_food_consumption_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $foodConsumption = new FoodConsumption();
        $form = $this->createForm(FoodConsumption1Type::class, $foodConsumption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($foodConsumption);
            $entityManager->flush();

            return $this->redirectToRoute('app_food_consumption_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('food_consumption/new.html.twig', [
            'food_consumption' => $foodConsumption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_consumption_show', methods: ['GET'])]
    public function show(FoodConsumption $foodConsumption): Response
    {
        return $this->render('food_consumption/show.html.twig', [
            'food_consumption' => $foodConsumption,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_food_consumption_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FoodConsumption $foodConsumption, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FoodConsumption1Type::class, $foodConsumption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_food_consumption_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('food_consumption/edit.html.twig', [
            'food_consumption' => $foodConsumption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_food_consumption_delete', methods: ['POST'])]
    public function delete(Request $request, FoodConsumption $foodConsumption, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodConsumption->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($foodConsumption);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_food_consumption_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/animal/{id}', name: 'food_consumption_by_animal', methods: ['GET'])]
    public function byAnimal(Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $consumptions = $entityManager->getRepository(FoodConsumption::class)
            ->findBy(['animal' => $animal]);

        return $this->render('food_consumption/by_animal.html.twig', [
            'animal' => $animal,
            'consumptions' => $consumptions,
        ]);
    }

    
}
