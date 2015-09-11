<?php

class Numeric implements Constraint
{
    private $message = 'Enter valid number, please';

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
        return is_numeric($value);
    }
}
