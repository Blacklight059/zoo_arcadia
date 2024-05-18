<?php

namespace App\Controller;

use App\Services\MongoDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    private MongoDBService $mongoDBService;

    public function __construct(MongoDBService $mongoDBService)
    {
        $this->mongoDBService = $mongoDBService;
    }

    #[Route('/report', name: 'report_list')]
    public function list(): Response
    {
        // Vérifier si l'utilisateur a le rôle "ROLE_VETERINARIAN"
        $this->denyAccessUnlessGranted('ROLE_VETERINARIAN');

        // Fetch reports from MongoDB
        $reports = $this->mongoDBService->getAllReports();

        return $this->render('report/list.html.twig', [
            'reports' => $reports,
        ]);
    }

    #[Route('/report/add', name: 'report_add')]
    public function add(Request $request): Response
    {
        // Vérifier si l'utilisateur a le rôle "ROLE_VETERINARIAN"
        $this->denyAccessUnlessGranted('ROLE_VETERINARIAN');

        if ($request->isMethod('POST')) {
            $report = $request->request->all();
            $report['date'] = new \DateTime(); // Ajouter la date du passage automatiquement
            $this->mongoDBService->saveDailyReport($report);

            return $this->redirectToRoute('report_list');
        }

        return $this->render('report/add.html.twig');
    }

    #[Route('/report/{id}', name: 'report_view', requirements: ['id' => '\d+'])]
    public function view(int $id): Response
    {
        // Vérifier si l'utilisateur a le rôle "ROLE_VETERINARIAN"
        $this->denyAccessUnlessGranted('ROLE_VETERINARIAN');

        $report = $this->mongoDBService->getReportById($id);

        return $this->render('report/view.html.twig', [
            'report' => $report,
        ]);
    }

    
}
