<?php

namespace Serasa\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

/**
 * Class AuthController controls the Application Auth System.
 * @package Serasa\Controller
 */
class AuthController
{
    /**
     * Validates if a user is registered
     * and provides a JWT to be Beared on Authentication Header.
     *
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Application $app, Request $request)
    {
        $username = $request->get('user');
        $password = $request->get('pass');

        $response = [
            'message' => 'Unauthorized'
        ];

        $status = 403;

        if ($username && $password) {

            $sql = sprintf("
                SELECT * FROM users 
                WHERE username = ? AND password = ?
            ");

            /**
             * Basid md5 encryption here for the sake of simplicity!
             * @todo upgrade to PHP password_hash.
             */
            $user = $app['db']->fetchAssoc(
                $sql, [
                    $username, md5($password)
                ]
            );

            if ($user) {

                $tokenId = $this->generateToken();
                $issuedAt = time();
                $notBefore = $issuedAt;
                $expire = $notBefore + $app['jwtExpire'];
                $serverName = $app['serverName'];

                $data = [
                    'iat' => $issuedAt,
                    'jti' => $tokenId,
                    'iss' => $serverName,
                    'nbf' => $notBefore,
                    'exp' => $expire,
                    'data' => [
                        'userId' => $user['id'],
                        'userName' => $user['username'],
                    ]
                ];

                $secretKey = base64_decode($app['secret']);

                $algorithm = $app['algorithm'];

                $jwt = JWT::encode($data, $secretKey, $algorithm);

                $response = [
                    'token' => $jwt,
                    'message' => 'Authorized'
                ];

                $status = 200;
            }
        }

        return new JsonResponse($response, $status);
    }

    /**
     * Utility tool to generate a secure token based on PHP better available function.
     *
     * @return string
     */
    private function generateToken()
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $token = bin2hex(openssl_random_pseudo_bytes(32));
        }

        if (!$token && function_exists('random_bytes')) {
            $token = bin2hex(random_bytes(32));
        }

        if (!$token && function_exists('mcrypt_create_iv')) {
            $token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        }

        return base64_encode($token);
    }
}
