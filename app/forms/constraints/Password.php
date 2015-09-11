<?php

class Password implements Constraint
{
    private $message = 'Password must contain 8 characters and at least one number, one letter and one unique character such as "! # $ % &amp; @".';

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
        return preg_match('/^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[!#$%&?@ "]).*$/i', $value);
    }
}
