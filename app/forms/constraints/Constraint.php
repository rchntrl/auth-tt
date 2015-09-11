<?php

interface Constraint {
    /**
     * Will be displayed as form validation error
     *
     * @param array $data used to pass params to Translator
     * @return String
     */
    public function getMessage(&$data);

    /**
     * @param $value
     * @return Boolean
     */
    public function validate($value);
}