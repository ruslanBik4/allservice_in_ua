<?php
require_once 'GoBridge.php';

class FieldsInfoRepository
{
    /* @var GoBridge */
    private $go;

    public function __construct($go_file = '../get_fields_info')
    {
        $this->go = new GoBridge($go_file);
    }

    /*
     * Вывести информацию о всех таблицах.
     */
    public function getAll()
    {
        return $this->go->execute();

        // Когда будет рабочий JSON
        //return json_decode($this->go->execute());
    }

    /*
     * Вывести таблицу по названию
     */
    public function getTable($tablename)
    {
        return $this->go->execute($tablename);
    }
}