<?php

class FormMessage
{
    const ERROR = 0;
    const SUCCESS = 1;
    const INFO = 2;

    /**
     * @param Int $messageType
     * @param String $message
     */
    public static function sendMessage($messageType, $message)
    {
        $_SESSION['formMessage'] = $message;
        $_SESSION['formMessageType'] = $messageType;
    }

    public static function messageType()
    {
        return $_SESSION['formMessageType'];
    }

    public static function hasMessage()
    {
        return @$_SESSION['formMessage']  ? true : false;
    }

    /**
     * @return mixed
     */
    public static function showMessage()
    {
        $string = @$_SESSION['formMessage'];
        unset($_SESSION['formMessage']);
        unset($_SESSION['formMessageType']);
        return $string;
    }
}
