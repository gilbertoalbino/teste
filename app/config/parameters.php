<?php
/**
 * Database Parameters
 */
$app['driver'] = 'pdo_mysql';
$app['host'] = '127.0.0.1';
$app['port'] = 'null';
$app['dbname'] = 'serasa';
$app['user'] = 'root';
$app['password'] = '';

/**
 * Security
 */
$app['serverName'] = "serasa.localhost";
$app['jwtExpire'] = 680000;

/**
 * JWT's signing key.
 */
$app['secret'] = base64_encode("secret");
$app['algorithm'] = "HS512";