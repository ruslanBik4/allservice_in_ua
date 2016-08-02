<?php

class Query extends BridgeClient
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