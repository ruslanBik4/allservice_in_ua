<?php

class Query
{
    /**
     * @var resource
     */
    private $ch;

    public function __construct()
    {
        $this->ch = curl_init('http://allservice.in.ua/isenka/query.php');

        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Выполнить люой SQL запрос.
     *
     * @param string $sql
     * @return array
     */
    public function runSql($sql)
    {
        return $this->getFromGoApi('sql=' . $sql);
    }

    /**
     * Выполнить процедуру.
     *
     * @param string $procedure
     * @return array
     */
    public function callProcedure($procedure)
    {
        return $this->getFromGoApi('call=' . $sql);
    }

    /**
     * @param string $tableName
     * @param array $values
     * @return array
     */
    public function runInsert($tableName, array $values)
    {
        $query = 'insert=' . $tableName;

        foreach ($values as $key => $value) {
            
            $query .= '&' . $key . '=' . $value;
        }

        return $this->getFromGoApi($query);
    }

    /**
     * Записать в выбранную таблицу.
     *
     * @param string $tablename
     * @param array $values
     * @param string $where
     * @return array
     */
    public function runUpdate($tablename, array $values, $where)
    {
        $query = 'update=' . $tablename . '&where=' . $where . '&';

        $size = sizeof($values)-1;
        $count = 0;

        foreach ($values as $key => $value) {
            if ($count == $size) {
                $query .= $key . '=' . $value;
            } else {
                $query .= $key . '=' . $value . '&';
            }

            $count++;
        }

        return $this->getFromGoApi($query);
    }

    private function getFromGoApi($command)
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $command);

        $result = json_decode(curl_exec($this->ch), true);

        foreach ($result as &$res) {
            $res = json_decode($res, true);
        }

        if (sizeof($result) > 1) {
            return $result;
        }

        return $result[0];
    }
}