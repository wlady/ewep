<?php

/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 16.08.16
 * Time: 08:01
 */

namespace EWallet\Middlewares;

use EWallet\BaseContainer;

class AuthMiddleware
{
    use BaseContainer;

    public function __invoke($request, $response, $next)
    {
        if (!$this->container->auth->check()) {
            return $response->withStatus(401);
        }
        $response = $next($request, $response);
        return $response;
    }
}
