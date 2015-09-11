<?php

class UniqueUserEmail implements Constraint
{
    private $message = 'This email already exists.';

    /**
     * @var User
     */
    private $changedRecord;

    public function __construct($message = null, User &$changedRecord)
    {
        if ($message) {
            $this->message = $message;
        }
        $this->changedRecord = $changedRecord;
    }

    public function getMessage(&$data = array())
    {
        return $this->message;
    }

    public function validate($value)
    {
        $records = DB::getDataList(get_class($this->changedRecord), array('Email' => $value));
        foreach ($records as $record) {
            if ($record->getId() == $this->changedRecord->getId()) {
                continue;
            }
            return false;
        }
        return true;
    }
}
