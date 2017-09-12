<?php

namespace Serasa\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CandidateController controls the API Candidate CRUD actions.
 * @package Serasa\Controller
 */
class CandidateController
{
    /**
     * Lists all candidates summing up the scores.
     *
     * @param Application $app
     * @return JsonResponse
     */
    public function index(Application $app)
    {
        $message = 'Listing Candidates';

        $sql = "SELECT * FROM candidates";

        $statement = $app['db']->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, CandidateHydrator::class);

        $candidates = $statement->fetchAll();

        return new JsonResponse([
            'message' => $message,
            'candidates' => $candidates
        ], 200);
    } 
}