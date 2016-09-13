<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 15.08.16
 * Time: 10:02
 */

namespace EWallet\Mobikassa;

use EWallet\BaseContainer;

class Command
{
    use BaseContainer;

    /**
     * @param $params
     * @return mixed
     */
    public function loginUser($params)
    {
        return $this->container->view->fetch('commands/LoginUser.tpl', ['user' => $params]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function createUser($params)
    {
        return $this->container->view->fetch('commands/CreateUser.tpl', ['user' => $params]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getRegion($params)
    {
        return $this->container->view->fetch('commands/GetRegion.tpl', ['params' => $params]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getStreet($params)
    {
        return $this->container->view->fetch('commands/GetStreet.tpl', ['params' => $params]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function offGetCity($params)
    {
        return $this->container->view->fetch('commands/OffGetCity.tpl', ['params' => $params]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function offGetStreet($params)
    {
        return $this->container->view->fetch('commands/OffGetStreet.tpl', ['params' => $params]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function offGetAddress($params)
    {
        return $this->container->view->fetch('commands/OffGetAddress.tpl', ['params' => $params]);
    }

}
