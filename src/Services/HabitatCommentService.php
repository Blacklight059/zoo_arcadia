<?php

namespace App\Services;

use MongoDB\Client;

class HabitatCommentService
{
    private $client;

    public function __construct(MongoDBService $mongoDBService)
    {
        $this->client = $mongoDBService->getClient();
    }

    public function addHabitatComment(array $comment): void
    {
        $habitatComments = $this->client->zooDB->habitat_comments;
        $result = $habitatComments->insertOne($comment);
    }
}
