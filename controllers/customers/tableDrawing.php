<?php
class tableDrawing
{

    protected $tableName;
    protected $tableFromDB;

    /**
     * Консттруктор принимает название таблицы и записывает в свойство $this->tableName
     * tableDrawing constructor.
     * @param $tableName
     */
    public function __construct($tableName){
        $this->tableName = $tableName;
    }

    /**
     * Метод получает все содержимое таблицы $this->tableName по запросу SELECT и записывает в свойство $this->tableFormDB
     * вызывает метод draw() и возвращет готовую таблицу
     * @return string
     */
    public function getTable(){
        $query = new QueryOld();
        $sql = "SELECT * FROM {$this->tableName}";
        $this->tableFromDB = $query->runSql($sql);
        return $this->draw();

    }

    /**
     * Метод возвращет готовую таблицу на основе данных записанные в свойстве $this->tableFormDB
     * @return string
     */
    public function draw(){

        $header = 0;
        $table = '<table style="border: 1px solid black;">';

        foreach ($this->tableFromDB as $array){
            if($header == 0){
                $table.= '<tr>';
                foreach ($array as $key => $value){
                    $table.= "<td style='border: 1px solid black;'>{$key}</td>";
                }
                $table.= '<tr>';
                $header = 1;
            }
            $table.= '<tr>';
            foreach ($array as $key => $value){
                $table.= "<td style='border: 1px solid black;'>{$value}</td>";
            }
            $table.= '</tr>';
        }
        $table.= '</table>';

        return $table;
    }

}
