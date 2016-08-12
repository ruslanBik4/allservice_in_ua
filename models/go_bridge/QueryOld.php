<?php

class QueryOld extends AbstractBridgeClient
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

    /**
     * Записать в выбранную таблицу.
     *
     * @param string $tablename
     * @param array $values
     * @return array
     */
    public function runInsert($tablename, array $values)
    {
        $string = 'insert=' . $tablename . '&';

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

        return $this->go_bridge->execute($string, true);
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
        $string = 'update=' . $tablename . '&';

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
        var_dump($string); die;
        return $this->go_bridge->execute($string, true);
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