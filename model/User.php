<?php

/**
 * Class User
 *
 * @property $Created
 * @property String $Email
 * @property String $Password
 * @property String $Salt
 * @property String $RelationshipStatus
 * @property Address $Location
 * @property DataList|WorkExperience[] $WorkPositions
 * @property DataList|Education[] $EducationList
 */
class User extends DataObject
{

    private $BirthDate;

    protected static $db = array(
        'Created', 'FirstName', 'LastName', 'MiddleName',
        'Email', 'Password', 'Salt',
        'RelationshipStatus',
        // contacts
        'Phone', 'Skype',
        'MoreAboutMe',
        'ImagePath',
    );

    protected static $has_one = array(
        'Location' => 'Address',
    );

    protected static $has_many = array(
        'WorkPositions' => 'WorkExperience',
        'EducationList' => 'Education',
    );

    /**
     * @var String
     */
    private $PlainPassword;

    public function getUsername()
    {
        return $this->Email;
    }

    public function getFullName()
    {
        return $this->FirstName . ' ' . $this->LastName . ' ' . $this->MiddleName;
    }

    public function equals(User &$user)
    {
        return $this->Emai == $user->Email;
    }

    /**
     * @return String
     */
    public function getBirthDate()
    {
        if (empty($this->BirthDate)) {
            return '';
        }
        $date = new DateTime($this->BirthDate);
        return $date->format('d.m.Y');
    }

    /**
     * @param String $BirthDate
     */
    public function setBirthDate($BirthDate)
    {
        $date = new DateTime($BirthDate);
        $dateString = $date->format('Y-m-d');
        if ($this->BirthDate != $dateString) {
            $this->BirthDate = $dateString;
            $this->affectedFields['BirthDate'] = $this->BirthDate;
        }
    }

    /**
     * @return String
     */
    public function getPlainPassword() {
        return $this->PlainPassword;
    }

    /**
     * @param String $PlainPassword
     *
     * @return $this
     */
    public function setPlainPassword($PlainPassword)
    {
        $this->PlainPassword = $PlainPassword;
        if (!empty($this->PlainPassword)) {
            $this->Salt = md5(time());
            $this->Password = hash('sha512', $this->PlainPassword . $this->Salt);
            $this->affectedFields['Password'] = $this->Password;
            $this->affectedFields['Salt'] = $this->Salt;
        }
        return $this;
    }

    /**
     * @return String
     */
    public function getRelationshipStatus() {
        $choices = static::getRelationshipChoices();
        return @$choices[$this->RelationshipStatus] ?: 'Not specified';
    }

    /**
     * @return Array
     */
    public static function getRelationshipChoices() {
        return array(
            'single' => 'Single',
            'in-a-relationship' => 'In a relationship',
            'engaged' => 'Engaged',
            'married' => 'Married',
        );
    }
}
