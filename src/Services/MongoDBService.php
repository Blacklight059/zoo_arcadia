<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\BSON\ObjectID;

class MongoDBService
{
    private $client;
    private $database;

    public function __construct()
    {
        $this->client = new Client("mongodb://localhost:27017");
        $this->database = $this->client->selectDatabase('zooarcadia');
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function saveDailyReport($report)
    {
        $collection = $this->database->selectCollection('daily_reports');
        $result = $collection->insertOne($report);
        return $result->getInsertedId();
    }

    public function getReportsByAnimalId($animalId)
    {
        $collection = $this->database->selectCollection('daily_reports');
        $cursor = $collection->find(['animal_id' => $animalId]);
        $reports = [];
        foreach ($cursor as $document) {
            $reports[] = $document;
        }
        return $reports;
    }

        public function getAllReports()
    {
        $collection = $this->database->selectCollection('daily_reports');
        $cursor = $collection->find();
        $reports = [];
        foreach ($cursor as $document) {
            $reports[] = $document;
        }
        return $reports;
    }

    public function getReportById($id)
    {
        $collection = $this->database->selectCollection('daily_reports');
        $report = $collection->findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);
        return $report;
    }
}
