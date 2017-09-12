<?php

namespace Serasa\Controller;

use Serasa\Hydrator\CandidateHydrator;
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

    /**
     * Finds a given candidate by ID.
     *
     * @param $id
     * @param Application $app
     * @return JsonResponse
     */
    public function show($id, Application $app)
    {
        $message = 'Showing Candidate #' . $id;

        $sql = "SELECT * FROM candidates WHERE id = :id";

        $statement = $app['db']->prepare($sql);
        $statement->bindValue('id', $id);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, CandidateHydrator::class);

        $candidate = $statement->fetch();

        return new JsonResponse([
            'message' => $message,
            'candidate' => $candidate
        ], 200);
    }


    /**
     * Creates a new Candidate.
     *
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Application $app, Request $request)
    {
        $message = 'Creating Candidate';

        /**
         * Validates if any data inconsistence is found.
         */
        $validatorErrors = CandidateValidator::factory(
            $request->query->all(),
            CandidateValidator::IS_INSERT
        )->getErrors();

        /**
         * Application will stop and return an array of errors
         * matched by key => message to be used in error decoration.
         */
        if (!empty($validatorErrors)) {
            return new JsonResponse(['errors' => $validatorErrors], 400);
        }

        /**
         * Cleans up any dirty and removes not fillable columns.
         */
        $data = CandidateSanitizer::factory($request->query->all());

        /**
         * Checks if the candidate is already registered.
         */
        $sql = sprintf("
              SELECT * FROM candidates 
              WHERE fullname = :fullname 
              AND birthdate = :birthdate"
        );

        $statement = $app['db']->prepare($sql);
        $statement->bindValue('fullname', $data->fullname);
        $statement->bindValue('birthdate', $data->birthdate);
        $statement->execute();

        $candidate = $statement->fetch();

        if ($candidate) {
            return new JsonResponse([
                'message' => $message,
                'error' => 'Candidate is already registered.'
            ], 400);
        }

        /**
         * The candidate is created just with a name and a birthdate.
         * Scores values must be provided by update action.
         */
        $candidate = $app['db']->insert('candidates', [
            'fullname' => $data->fullname,
            'birthdate' => $data->birthdate
        ]);

        return new JsonResponse([
            'message' => $message,
            'candidate' => $candidate
        ], 200);
    }

}