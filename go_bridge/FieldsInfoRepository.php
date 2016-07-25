<?php

class FieldsInfoRepository extends AbstractGoClient
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


    protected function defaultPath()
    {
        $filePath = dirname(__DIR__) . '/isenka/get_fields_info';

        if (!$this->isUnix()) {
            $filePath .= '.exe';
        }

        return $filePath;
    }
}