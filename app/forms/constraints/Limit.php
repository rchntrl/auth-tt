<?php

class Limit implements Constraint{

    private $maxMessage = 'The text length is more than %length%.';

    private $minMessage = 'The text length is less than %length%.';

    /**
     * @var Int
     */
    private $maxLength;

    /**
     * @var Int
     */
    private $minLength;

    /**
     * @var Array
     */
    private $message;

    public function __construct($minLength = null, $maxLength = null)
    {
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
    }

    public function getMessage(&$data = array())
    {
        $data = $this->message['data'];
        return $this->message['message'];
    }

    public function validate($value)
    {
        if (empty($value)) {
            return true;
        }
        if ($this->maxLength && $this->maxLength < strlen($value)) {
            $this->message['message'] = $this->maxMessage;
            $this->message['data'] = array('%length%' => $this->maxLength);
            return false;
        }
        if ($this->minLength && $this->minLength > strlen($value)) {
            $this->message['message'] = $this->minMessage;
            $this->message['data'] = array('%length%' => $this->minLength);
            return false;
        }
        return true;
    }

    /**
     * @param string $maxMessage
     */
    public function setMaxMessage($maxMessage)
    {
        $this->maxMessage = $maxMessage;
    }

    /**
     * @param string $minMessage
     */
    public function setMinMessage($minMessage)
    {
        $this->minMessage = $minMessage;
    }
}
