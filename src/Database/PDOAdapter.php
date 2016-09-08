<?php

namespace PhutureProof\Database;

class PDOAdapter extends \PDO
{
    public function __construct($driver, $hostname, $username, $password, $database)
    {
        parent::__construct("{$driver}:dbname={$database};host={$hostname}", $username, $password);
    }

    public function runPrepared($sql, $values = [], $returnData = false)
    {
        // method overloading (?!)
        if ($values === true) {
            $values = [];
            $returnData = true;
        }
        $statement = $this->prepare($sql);
        $statement->execute($values);
        if ($returnData) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }
        return true;
    }
}
