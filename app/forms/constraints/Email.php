<?php

class Email implements Constraint
{

    private $message = 'Enter valid email text, please';

    public function __construct($message = null)
    {
        if ($message) {
            $this->message = $message;
        }
    }

    public function getMessage(&$data = array())
    {
        return $this->message;
    }

    public function validate($value)
    {
        if (empty($value)) {
            return true;
        }
        return preg_match('/^(([a-zA-Z]|[0-9])|([-]|[_]|[.]))+[@](([a-zA-Z0-9])|([-])){2,63}[.](([a-zA-Z0-9]){2,63})+$/i', $value);
    }
}