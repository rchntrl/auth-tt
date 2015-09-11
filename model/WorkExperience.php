<?php

/**
 * Class WorkExperience
 *
 * @property $Title
 * @property $Organization
 * @property $Description
 * @property User $User
 */
class WorkExperience extends DataObject
{
    protected static $db = array(
        'Title',
        'Organization',
        'StartDateMonth',
        'StartDateYear',
        'EndDateMonth',
        'EndDateYear',
        'Description',
    );

    protected static $belongs_to = array(
        'User' => 'User'
    );

    protected static $sort = array(
        'StartDateYear' => 'DESC',
        'Id' => 'DESC'
    );
}
