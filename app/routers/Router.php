<?php

$routers = glob(APPDIR . '/routers/*.router.php');
foreach ($routers as $router) {
    require $router;
}
