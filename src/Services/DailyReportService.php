<?php

namespace App\Services;

use MongoDB\Client;

class DailyReportService
{
    private $client;

    public function __construct(MongoDBService $mongoDBService)
    {
        $this->client = $mongoDBService->getClient();
    }

    public function addDailyReport(array $report): void
    {
        $dailyReports = $this->client->zooDB->daily_reports;
        $result = $dailyReports->insertOne($report);
    }
}
