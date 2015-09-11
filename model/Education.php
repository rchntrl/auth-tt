<?php

/**
 * Class Education
 *
 * @property $Status
 * @property $Institution
 * @property $Description
 * @property User $User
 */
class Education extends DataObject
{
    protected static $db = array(
        'Status',
        'City',
        'Institution',
        'GraduationYear',
        'Department',
        'StudyProgram',
    );

    protected static $belongs_to = array(
        'User' => 'User'
    );

    protected static $sort = array(
        'GraduationYear' => 'DESC',
        'Id' => 'DESC'
    );
}
