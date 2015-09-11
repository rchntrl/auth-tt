<?php

class Form
{
    /**
     * @var DataObject|Array
     */
    protected $record;

    /**
     * @var  Array
     */
    protected $errors = array();

    /**
     * @var array
     */
    protected $fieldsMap = array();


    public function setFieldsMap($data)
    {
        $this->fieldsMap = array_merge($this->fieldsMap, $data);
        return $this;
    }

    public function __construct(DataObject $obj)
    {
        $this->record = $obj;
    }

    public function handleRequest(Request $request)
    {
        $translator = new Translator(@$_COOKIE['lang']);
        foreach ($this->fieldsMap as $field => $constraints) {
            /** @var Constraint $constraint */
            foreach ($constraints as $constraint) {
                if (!$constraint->validate($request->getValue($field))) {
                $msg = $constraint->getMessage($data);
                    $this->errors[$field][] = $translator->trans($msg, $data);
                }
            }
        }
        if ($this->isValid()) {
            $this->record->setFromData($request->getRequestData());
        } else {
            $this->record = $request->getRequestData();
        }
        return $this;
    }

    public function isValid()
    {
        return count($this->errors) == 0;
    }

    public function hasErrors($field)
    {
        return isset($this->errors[$field]);
    }

    public function getErrors($field = null)
    {
        if ($field) {
            return isset($this->errors[$field]) ? $this->errors[$field] : array();
        }
        return $this->errors;
    }

    public function getValue($field)
    {
        if ($this->record instanceof DataObject) {
            if (property_exists($this->record, $field)) {
                return htmlspecialchars($this->record->{'get' . $field}());
            }
            return in_array($field, $this->record->getDatabaseMap()) ? htmlspecialchars($this->record->$field) : '';
        }
        return htmlspecialchars(@$this->record[$field]);
    }

    public function getData()
    {
        return $this->record;
    }
}
