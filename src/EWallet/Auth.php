<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 16.08.16
 * Time: 08:03
 */

namespace EWallet;

class Auth
{
    use BaseContainer;

    public function check()
    {
        $user = $this->container->session->get('user');
        if (!$user || !$user->session) {
            return false;
        }
        return true;
    }
}
