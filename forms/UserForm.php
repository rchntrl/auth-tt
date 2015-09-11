<?php

class UserForm extends Form
{
    public function __construct(User &$obj)
    {
        parent::__construct($obj);
        $this->fieldsMap = array(
            'FirstName' => array(
                new NotBlank(),
                new Limit(null, 255),
            ),
            'LastName' => array(
                new NotBlank(),
                new Limit(null, 255),
            ),
            'MiddleName' => array(
                new NotBlank(),
                new Limit(null, 255),
            ),
            'Email' => array(
                new NotBlank(),
                new Limit(null, 255),
                new Email(),
                new UniqueUserEmail(null, $this->record),
            ),
            'PlainPassword' => array(
                new Limit(null, 255),
                new Password(),
            ),
            'BirthDate' => array(
                new Date(),
            ),
        );
    }
}
