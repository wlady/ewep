<?php

if (!defined('HOMEDIR')) {
    define('HOMEDIR',          rtrim(realpath(__DIR__.'/../'), DIRECTORY_SEPARATOR));
}
if (!defined('TMPDIR')) {
    define('TMPDIR',           HOMEDIR.'/tmp/');
}
if (!defined('SESSION_PATH')) {
    define('SESSION_PATH',     TMPDIR);
}
if (!defined('DOCUMENTROOTDIR')) {
    define('DOCUMENTROOTDIR', 'httpdocs');
}
define('APPDIR',              __DIR__. DIRECTORY_SEPARATOR);
define('CACHE_DIR',           TMPDIR.'.xcache');
define('TWIG_CACHE_DIR',      TMPDIR.'.tpl');
