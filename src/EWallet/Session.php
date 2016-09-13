<?php

namespace EWallet;

class Session
{
    protected $data = [];
    protected $name = '';

    public function __construct($name, $container)
    {
        $this->name = md5($name);
        $name = $this->name ?: session_name();
        session_name($name);
        if ($container->request->isXHR()) {
            session_cache_limiter('nocache');
        } else {
            session_cache_limiter('private_no_expire');
            session_cache_expire(30);
        }
        if (!session_id()) {
            if (!strcasecmp(ini_get('session.save_handler'), 'files') && defined('SESSION_PATH')) {
                session_save_path(SESSION_PATH);
            }
            $cookies = $container->request->getCookieParams();
            if (!empty($cookies[$this->name])) {
                session_id($cookies[$this->name]);
            }
        }
        session_start();
        $this->load();
    }

    private function load()
    {
        if (isset($_SESSION[$this->name])) {
            $this->data = unserialize($_SESSION[$this->name]);
        }
    }

    private function save()
    {
        $_SESSION[$this->name] = serialize($this->data);
    }

    public function isExists($name)
    {
        return isset($this->data[$name]) ? true : false;
    }

    public function get($name)
    {
        return $this->isExists($name) ? $this->data[$name] : false;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
        $this->save();
    }

    public function delete($name)
    {
        if ($this->isExists($name)) {
            $this->data[$name] = '';
            unset($this->data[$name]);
            $this->save();
        }
    }

    public function purge()
    {
        if (count($this->data)) {
            foreach ($this->data as $key => $item) {
                $this->delete($key);
            }
        }
    }

    public function getSID()
    {
        return session_id();
    }
}
