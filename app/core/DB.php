<?php

class DB
{
    /**
     * @var PDO db
     */
    public static $pdo;

    protected static $selectPatterns = array(
        '/%fields%/',
        '/%table%/',
        '/%filter%/',
        '/%sort%/',
        '/%limit%/',
    );

    public static function connect(Config $config)
    {
        self::$pdo = new PDO("mysql:host=" . $config->dbHost . ";dbname=" . $config->dbName, $config->dbUser, $config->dbPass);
    }

    protected static function pdoSet($source)
    {
        $set = '';
        foreach ($source as $field => $val) {
            $set .= "`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
        }
        return substr($set, 0, -2);
    }

    protected static function pdoClause($source)
    {
        $clause = '';
        foreach ($source as $field => $val) {
            $clause .= "`" . str_replace("`", "``", $field) . "`" . "= :$field, ";
        }
        return substr($clause, 0, -2);
    }

    protected static function pdoSort($sort)
    {
        $queryPart = 'ORDER BY ';
        $arr = array();
        foreach ($sort as $key => $val) {
            if (!is_numeric($key)) {
                $arr[] = $key . ' ' . $val;
            } else {
                $arr[] = $val. ' ASC';
            }
        }
        return $queryPart . implode(', ', $arr);
    }

    /**
     * @param DataObject $obj
     * @param Array|null $error
     * @return bool
     */
    public static function create(DataObject &$obj, &$error = null)
    {
        try {
            $values = $obj->getChangedFields();
            $sql = 'INSERT INTO ' . get_class($obj) . ' SET ' . self::pdoSet($values);
            $stm = self::$pdo->prepare($sql);
            if (!$stm->execute($values)) {
                $error['code'] = $stm->errorCode();
                $error['message'] = $stm->errorInfo();
                return false;
            }
            $obj->setId(self::$pdo->lastInsertId());
            return true;
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * @param DataObject $obj
     * @param Array|null $error
     * @return bool
     */
    public static function update(DataObject &$obj, &$error = null)
    {
        if (empty($obj->Id)) {
            trigger_error('New Data Object cannot be updated'); exit();
        }
        if ($obj->getChangedFields()) {
            $values = $obj->getChangedFields();
            $sql = 'UPDATE ' . get_class($obj) . ' SET ' . self::pdoSet($values) . ' WHERE id = :id';
            try {
                $stm = self::$pdo->prepare($sql);
                $values['id'] = $obj->getId();
                if (!$stm->execute($values)) {
                    $error['code'] = $stm->errorCode();
                    $error['message'] = $stm->errorInfo();
                    return false;
                }
                return true;
            } catch (\PDOException $ex) {
                throw $ex;
            }
        }
        return true;
    }

    /**
     * @param $className
     * @param $id
     * @return DataObject
     */
    public static function getObjectByID($className, $id)
    {
        $replacements = array(
            'fields' => "*",
            'table' => $className,
            'filter' => 'WHERE id = :id',
            'sort' => '',
            'limit' => 'LIMIT 1'
        );
        $subject = 'SELECT %fields% FROM %table% %filter% %limit%';
        $sth = self::$pdo->prepare(preg_replace(self::$selectPatterns, $replacements, $subject));
        $sth->bindParam('id', $id);
        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_CLASS, $className);
        return $sth->fetch();
    }

    /**
     * @param $className
     * @param $filter
     * @return DataObject
     */
    public static function getObjectBy($className, $filter)
    {
        $replacements = array(
            'fields' => "*",
            'table' => $className,
            'filter' => $filter ? 'WHERE ' . self::pdoClause($filter) : '',
            'sort' => '',
            'limit' => 'LIMIT 1'
        );
        $subject = 'SELECT %fields% FROM %table% %filter% %limit%';
        try {
            $sth = self::$pdo->prepare(preg_replace(self::$selectPatterns, $replacements, $subject));
            $sth->execute($filter);
        } catch (\PDOException $ex) {
            throw $ex;
        }
        $sth->setFetchMode(PDO::FETCH_CLASS, $className);
        return $sth->fetch();
    }

    /**
     * @param String $className
     * @param Array|null $filter
     * @param Array|null $limit
     * @param Array|null $sort
     * @return DataList|DataObject[]
     * @throws Exception
     */
    public static function getDataList($className, $filter = null, $limit = null, $sort = null)
    {
        $replacements = array(
            'fields' => 'count(*)',
            'table' => $className,
            'filter' => $filter ? ' WHERE ' . self::pdoClause($filter) : '',
            'sort' => $sort ? self::pdoSort($sort) : '',
            'limit' => ''
        );
        $subject = "SELECT %fields% FROM %table% %filter% %sort% %limit%";
        $sql = preg_replace(self::$selectPatterns, $replacements, $subject);
        $sth = self::$pdo->prepare($sql);
        if (!$sth->execute($filter)) {
            throw new Exception(implode($sth->errorInfo()), (int)$sth->errorCode());
        }
        $totalSize = $sth->fetch(PDO::FETCH_NUM);

        $replacements['fields'] = "*";
        $replacements['limit'] = $limit ? " LIMIT " . implode(',', $limit) : '';
        $sql = preg_replace(self::$selectPatterns, $replacements, $subject);
        $sth = self::$pdo->prepare($sql);
        if (!$sth->execute($filter)) {
            throw new Exception(implode($sth->errorInfo()), $sth->errorCode());
        }
        $sth->setFetchMode(PDO::FETCH_CLASS, $className);

        $list = new DataList((int)reset($totalSize));
        while ($row = $sth->fetch()) {
            $list[] = $row;
        }
        return $list;
    }

    public static function remove(DataObject $record) {
        $replacements = array(
            'fields' => '',
            'table' => get_class($record),
            'filter' =>' WHERE Id = :id',
            'sort' => '',
            'limit' => ''
        );
        $subject = "DELETE FROM %table% %filter%";
        $sth = self::$pdo->prepare(preg_replace(self::$selectPatterns, $replacements, $subject));
        $id = $record->getId();
        $sth->bindParam('id', $id);
        if (!$sth->execute()) {
            throw new Exception(implode($sth->errorInfo()), $sth->errorCode());
        }
        return true;
    }
}
