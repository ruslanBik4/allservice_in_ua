<?php

class FieldsInfoRepository extends BridgeClient
{
    /**
     * Показать информацию о всех таблицах.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->go->execute();
    }

    /**
     * Показать информацию о выбранной таблице.
     *
     * @param string $tablename
     * @return array
     */
    public function getTable($tablename)
    {
        return $this->go->execute($tablename);
    }

    /**
     * @return string
     */
    protected function defaultFile()
    {
        return 'get_fields_info';
    }
}