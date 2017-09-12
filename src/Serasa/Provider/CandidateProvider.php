<?php

namespace Serasa\Provider;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

/**
 * Class CandidateProvider validates the Authenticated Users
 * and provides the API routes to be mounted by the Application.
 *
 * @package Serasa\Provider
 */
class CandidateProvider implements ControllerProviderInterface
{
    /**
     * If the user provides a valid token,
     * access is granted and routes are created.
     *
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $candidates = $app['controllers_factory'];

        $candidates->before(function (Request $request) use ($app) {

            $header = $request->headers->get('Authorization');

            if ($header) {

                if (strpos($header, 'Bearer ') === false) {
                    return new JsonResponse(
                        array('message' => 'Unauthorized'),
                        401
                    );
                }

                $jwt = str_replace('Bearer ', '', $header);
                $secretKey = base64_decode($app['secret']);

                try {
                    $token = JWT::decode($jwt, $secretKey, [$app['algorithm']]);
                } catch (Exception $e) {
                    return new JsonResponse(
                        array('message' => 'Unauthorized'),
                        401
                    );
                }
            } else {
                return new JsonResponse(
                    array('message' => 'Bad Request'),
                    400
                );
            }
        });

        /**
         * Lists all candidates
         */
        $candidates->get('/', 'Serasa\Controller\CandidateController::index');

        /**
         * Finds a given candidate by ID
         */
        $candidates->get('/{id}', 'Serasa\Controller\CandidateController::show');

        /**
         * Creates a new Candidate
         */
        $candidates->post('/', 'Serasa\Controller\CandidateController::store');

        /**
         * Updates a given candidate by ID
         */
        $candidates->put('/{id}', 'Serasa\Controller\CandidateController::update');

        /**
         * Deletes a given candidate by ID
         */
        $candidates->delete('/{id}', 'Serasa\Controller\CandidateController::destroy');

        return $candidates;
    }
}
