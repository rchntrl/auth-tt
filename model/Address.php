<?php

/**
 * Class Address
 *
 * @property String $Country
 * @property String $Region
 * @property String $City
 * @property User $User
 */
class Address extends DataObject
{
    protected static $db = array(
        'Country', 'Region', 'City',
    );

    protected static $belongs_to = array(
        'User' => 'User'
    );

    public function getString() {
        return $this->Country . ', ' . $this->City;
    }
}
