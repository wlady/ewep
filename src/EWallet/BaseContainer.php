<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 14.08.16
 * Time: 17:39
 */

namespace EWallet;

use Slim\Container;

trait BaseContainer
{
    protected $container;
    protected $db;

    /**
     * BaseContainer constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->db = $this->container['db'];
        self::init();
        return $this;
    }

    public function init()
    {
    }
}