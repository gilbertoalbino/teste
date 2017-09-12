<?php
/**
 * Development Environment Configs
 */
$app['environment'] = "dev";
$app['debug'] = true;
$app['log.level'] = Monolog\Logger::DEBUG;
$app['api'] = '/api';