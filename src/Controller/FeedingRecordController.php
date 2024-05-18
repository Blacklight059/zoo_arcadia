<?php

namespace App\Controller;

use App\Services\MongoDBService;
use Symfony\Component\HttpFoundation\Response;

class FeedingRecordController
{
    private $mongoDBService;

    public function __construct(MongoDBService $mongoDBService)
    {
        $this->mongoDBService = $mongoDBService;
    }

    public function addFeedingRecord(): Response
    {
        // Logic to add feeding record
        return new Response('Feeding record added successfully');
    }
}
