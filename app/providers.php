<?php
/**
 * Default Application Providers
 */

use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

/**
 * Default Controller Service.
 */
$app->register(new ServiceControllerServiceProvider());

/**
 * Default Cache Service.
 */
$app->register(
    new HttpCacheServiceProvider(), [
        'http_cache.cache_dir' => __DIR__ . '/../app/cache'
    ]
);

/**
 * Doctrine DB Service.
 */
$app->register(
    new DoctrineServiceProvider(), [
        'db.options' => [
            'driver' => $app['driver'],
            'host' => $app['host'],
            'port' => $app['port'],
            'dbname' => $app['dbname'],
            'user' => $app['user'],
            'password' => $app['password'],
            'charset' => 'utf8mb4',
        ],
    ]
);

/**
 * Monolog Logging Service
 */
$now = new DateTime('now');
$app->register(
    new MonologServiceProvider(), [
        'monolog.logfile' => sprintf(
            '%s/../app/logs/%s/%s.log',
            __DIR__,
            $app['environment'],
            $now->format('Y-m-d')
        ),
        'monolog.level' => $app['log.level'],
        'monolog.name' => 'application',
    ]
);