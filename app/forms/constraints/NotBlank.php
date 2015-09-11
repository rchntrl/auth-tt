<?php

class NotBlank implements Constraint {

    private $message = 'This is required field.';

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
        return !empty($value);
    }
}
