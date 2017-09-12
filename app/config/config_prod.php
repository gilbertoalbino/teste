<?php
/**
 * Production Environment Configs
 */
$app['environment'] = "prod";
$app['debug'] = false;
$app['log.level'] = Monolog\Logger::DEBUG;
$app['api'] = '/api';
