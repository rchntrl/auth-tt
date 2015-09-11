<?php

/**
 * Class Registry
 *
 * @property Request $request
 * @property Config $config
 * @property Auth $auth
 */
class Registry extends Object
{

    public function __construct(Config $config) {
        $this->set('request', new Request());
        $this->set('config', $config);
        $this->set('auth', new Auth($this));
    }

    function set($key, $var)
    {
        if (isset($this->fields[$key]) == true) {
            throw new Exception('Unable to set var `' . $key . '`. Already set.');
        }
        $this->fields[$key] = $var;
        return true;
    }

    function get($key)
    {
        if (isset($this->fields[$key]) == false) {
            return null;
        }
        return $this->fields[$key];
    }

    function remove($key)
    {
        unset($this->fields[$key]);
    }
}
