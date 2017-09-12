<?php
/**
 * This file is used as a proxy to Nginx and PHP Built-in server.
 */
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * CORS Pre-flight Request settings.
 */
$app->before(function (Request $request) {
    if ($request->getMethod() === 'OPTIONS') {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
        $response->setStatusCode(200);

        return $response->send();
    }
}, Application::EARLY_EVENT);

/**
 * CORS Response settings.
 */
$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
});

/**
 * Default Request accepts JSON is defined before Request.
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

/**
 * Error Handling is logged to Monolog.
 */
$app->error(function (\Exception $e, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    $app['monolog']->addError($e->getTraceAsString());

    return new JsonResponse(
        array(
            'statusCode' => $code,
            'message' => $e->getMessage(),
            'stacktrace' => $e->getTraceAsString(),
        )
    );
});

/**
 * Ta-da !!!
 */
return $app;
