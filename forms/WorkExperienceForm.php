<?php

class WorkExperienceForm extends Form
{
    public function __construct(WorkExperience &$obj)
    {
        parent::__construct($obj);
        $this->fieldsMap = array(
            'Organization' => array(
                new NotBlank(),
                new Limit(null, 255),
            ),
            'Title' => array(
                new NotBlank(),
                new Limit(null, 255),
            ),
            'StartDateYear' => array(
                new Numeric()
            ),
            'EndDateYear' => array(
                new Numeric()
            ),
        );
    }
}