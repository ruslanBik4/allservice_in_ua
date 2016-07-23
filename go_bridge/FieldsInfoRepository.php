<?php
require_once 'GoBridge.php';

class FieldsInfoRepository
{
    /* @var GoBridge */
    private $go;

    public function __construct($go_file = '../isenka/get_fields_info_windows.exe')
    {
        $this->go = new GoBridge($go_file);
    }

    /*
     * Вывести информацию о всех таблицах.
     */
    public function getAll()
    {
        return $this->go->execute();
    }

    /*
     * Вывести таблицу по названию
     */
    public function getTable($tablename)
    {
        return $this->go->execute($tablename);
    }
}