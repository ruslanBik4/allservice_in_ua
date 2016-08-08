<?php

class Query extends AbstractBridgeClient
{
    /**
     * Выполнить люой SQL запрос.
     *
     * @param string $sql
     * @return array
     */
    public function runSql($sql)
    {
        $this->go_bridge->setQueryString('sql');

        return $this->go_bridge->execute($sql);
    }

    public function insert($tablename, array $values)
    {
        $string = 'insert=' . $tablename;

        $size = sizeof($values)-1;
        $count = 0;

        foreach ($values as $key => $value) {
            if ($count == $size) {
                $string .= $key . '=' . $value;
            } else {
                $string .= $key . '=' . $value . '&';
            }

            $count++;
        }

        var_dump($string);
    }

    /**
     * Выполнить процедуру.
     *
     * @param string $procedure
     * @return array
     */
    public function callProcedure($procedure)
    {
        $this->go_bridge->setQueryString('call');

        return $this->go_bridge->execute($procedure);
    }

    /**
     * @return string
     */
    protected function filename()
    {
        return 'query';
    }
}