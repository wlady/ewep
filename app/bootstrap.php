<?php

include 'define.sysdirs.php';

define('A_DAY_CACHE_TIME',     86400);
define('MAX_CACHE_TIME',       3600); // 1 hour
define('HALF_HOUR_CACHE_TIME', 1800); // 30 mins
define('MIN_CACHE_TIME',       600);

define('MOBIKASSA_URL',        'https://93.127.10.197:59960/entirex/xmlrt');

if (!file_exists(TMPDIR)) {
    mkdir(TMPDIR);
}
if (!file_exists(CACHE_DIR)) {
    mkdir(CACHE_DIR);
}
if (!defined('PATH_SEPARATOR')) {
    define('PATH_SEPARATOR', getenv('COMSPEC') ? ';' : ':');
}

include_once HOMEDIR.'/vendor/autoload.php';
if (!file_exists(TWIG_CACHE_DIR)) {
    mkdir(TWIG_CACHE_DIR);
}

$config = [];
$config['displayErrorDetails'] = false;
$config['addContentLengthHeader'] = false;

//$config['db']['host']   = "localhost";
//$config['db']['user']   = "user";
//$config['db']['pass']   = "password";
//$config['db']['dbname'] = "ewapp";

// we are using SQLite temporarily
$config['db']['path'] = __DIR__.'/tmp.sqlite';

umask(0002);
