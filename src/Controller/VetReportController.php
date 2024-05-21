<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\FoodConsumption;
use App\Entity\VetReport;
use App\Form\VetReportType;
use App\Repository\FoodConsumptionRepository;
use App\Repository\VetReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/veterinarian/vet/report')]
class VetReportController extends AbstractController
{
    #[Route('/', name: 'app_vet_report_index', methods: ['GET'])]
    public function index(VetReportRepository $vetReportRepository): Response
    {
        return $this->render('vet_report/index.html.twig', [
            'vet_reports' => $vetReportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vet_report_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vetReport = new VetReport();
        $form = $this->createForm(VetReportType::class, $vetReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vetReport);
            $entityManager->flush();

            return $this->redirectToRoute('app_vet_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vet_report/new.html.twig', [
            'vet_report' => $vetReport,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_vet_report_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VetReport $vetReport, EntityManagerInterface $entityManager): Response
    {
        if (!$vetReport) {
            throw $this->createNotFoundException('Le rapport vétérinaire demandé n\'existe pas.');
        }

        $form = $this->createForm(VetReportType::class, $vetReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vet_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vet_report/edit.html.twig', [
            'vet_report' => $vetReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vet_report_delete', methods: ['POST'])]
    public function delete(Request $request, VetReport $vetReport, EntityManagerInterface $entityManager): Response
    {
        if (!$vetReport) {
            throw $this->createNotFoundException('Le rapport vétérinaire demandé n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete'.$vetReport->getId(), $request->get('_token'))) {
            $entityManager->remove($vetReport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vet_report_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/filter', name: 'app_vet_report_filter', methods: ['GET'])]
    public function filter(Request $request, VetReportRepository $vetReportRepository): Response
    {
        $criteria = [];
        if ($animalName = $request->query->get('animalName')) {
            $criteria['animal.firstname'] = $animalName;
        }
        if ($date = $request->query->get('date')) {
            $criteria['visitDate'] = new \DateTime($date);
        }

        $reports = $vetReportRepository->findByCriteria($criteria);

        return $this->render('vet_report/_reports.html.twig', [
            'reports' => $reports,
        ]);
    }

    #[Route('/list', name: 'app_vet_report_list', methods: ['GET'])]
    public function list(VetReportRepository $vetReportRepository): Response
    {
        return $this->render('vet_report/list.html.twig', [
            'vet_reports' => $vetReportRepository->findAll(),
        ]);
    }

    #[Route('/foodList', name: 'app_food_consumption_foodList', methods: ['GET'])]
    public function foodList(FoodConsumptionRepository $foodConsumptionRepository): Response
    {
        return $this->render('vet_report/foodList.html.twig', [
            'food_consumptions' => $foodConsumptionRepository->findAll(),
        ]);
    }

    #[Route('/{id}/food-consumption', name: 'vet_report_by_animal', methods: ['GET'])]
    public function byAnimal(Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $consumptions = $entityManager->getRepository(FoodConsumption::class)
            ->findBy(['animal' => $animal]);

        return $this->render('vet_report/by_animal.html.twig', [
            'animal' => $animal,
            'consumptions' => $consumptions,
        ]);
    }
}
