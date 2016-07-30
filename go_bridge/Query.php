<?php

class Query extends BridgeClient
{
    /* @var string */
    protected $sql;

    public function showLastSql()
    {
        return $this->sql;
    }

    public function runSql($sql = null)
    {
        $this->sql = $sql;

        return $this->go->execute($this->sql);
    }

    public function select($select, $from, $where = null, $value = null) {
        $this->sql = "SELECT {$select} FROM {$from}";

        if ($where) {
            if (!$value) {
                throw new Exception("No value was given for WHERE condition.");
            }

            $this->sql .= " WHERE {$where} = '{$value}'";
        }

        return $this->go->execute($this->sql);
    }

    public function requestSql()
    {
        $select = '*';
        $from = 'category';
        $where = '';

        if (isset($_REQUEST['select'])) {
            $select = $_REQUEST['select'];
        }

        if (isset($_REQUEST['from'])) {
            $from = $_REQUEST['from'];
        }

        if (isset($_REQUEST['where'])) {
            $where = 'WHERE ' . $_REQUEST['where'];
        }

        $this->sql = "SELECT {$select} FROM {$from} {$where}";

        return escapeshellarg($this->sql);
    }

    /**
     * @return string
     */
    protected function defaultFile()
    {
        return 'query';
    }
}