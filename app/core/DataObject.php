<?php

/**
 * Class DataObject
 *
 * Parent model class
 */
class DataObject extends Object implements JsonSerializable
{

    /**
     * Database field definitions.
     * This is a map of the field names
     *
     * @var array
     */
    protected static $db = null;
    protected static $has_many = null;
    protected static $has_one = null;
    protected static $belongs_to = null;
    protected static $sort = null;

    /**
     * Will be used to update changed fields. See DB class
     *
     * @var Array
     */
    protected $affectedFields;

    public function __construct()
    {
        if (empty($this->Id)) {
            $this->Id = null;
            foreach (static::getDatabaseMap() as $field) {
                $this->fields[$field] = null;
            }
        }
    }

    public static function getDatabaseMap()
    {
        return static::$db;
    }

    /**
     * @return Int
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param $Id
     */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

    /**
     * @param Array $source
     * @return $this
     */
    public function setFromData($source)
    {
        foreach ($source as $key => $val) {
            if (property_exists($this, $key) && $this->{'get' . $key}() != $val) {
                $this->{'set' . $key}($val);
            } else if (in_array($key, static::getDatabaseMap()) && $this->$key != $val) {
                $this->{$key} = $val;
                $this->affectedFields[$key] = $val;
            }
        }
        return $this;
    }

    /**
     *
     * @return Array
     */
    public function getChangedFields()
    {
        if (empty($this->Id)) {
            // only $db fields
            foreach (static::getDatabaseMap() as $field) {
                if (!empty($this->$field)) {
                    $this->affectedFields[$field] = $this->fields[$field];
                }
            }
        }
        return $this->affectedFields;
    }

    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
        if ($value instanceof DataObject) {
            $className = get_class($value);
            $field = strtolower($className . '_id');
            $this->fields[$field] = $value->getId();
            $this->affectedFields[$field] = $value->getId();
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name];
        }

        if (isset(static::$has_many[$name])) {
            $field = strtolower(get_class($this) . '_id');
            $className = static::$has_many[$name];
            $this->fields[$name] = DB::getDataList($className, array($field => $this->getId()), null, $className::$sort);
            return $this->fields[$name];
        }
        if (isset(static::$belongs_to[$name])) {
            $className = static::$belongs_to[$name];
            $field = strtolower($className . '_id');
            $this->fields[$name] = DB::getObjectByID($className, $this->$field);
            return $this->fields[$name];
        }
        if (isset(static::$has_one[$name])) {
            $className = static::$has_one[$name];
            $field = strtolower(get_class($this) . '_id');
            $this->fields[$name] = DB::getObjectBy($className, array($field => $this->getId()));
            return $this->fields[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function jsonSerialize() {
        return $this->fields;
    }
}
