<?php
/**
 * Application Routes
 */

/**
 * Default route for home redirects to login.
 */
$app->get('/', function () use ($app) {
    return $app->redirect($app['api'] . '/auth/login');
});

/**
 * The login is responsible for generating a JWT for use in the whole application auth system.
 */
$app->match($app['api'] . '/auth/login', '\Serasa\Controller\AuthController::login');