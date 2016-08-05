<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 05.08.2016
 * Time: 10:57
 */
class roomProcessing
{
    protected $data;

    public function __construct()
    {
        $this->data = $_POST;
        $this->processing();
    }

    protected function processing()
    {

        $previousNameOfTable = '';

        // счетчик
        $counter = -1;

        // формируем массив вида $mas = [имя таблицы] [name of input)] [значение]

        foreach ($this->data as $key => $value){
            $params = explode(':', $key);
            if($params[0] !== $previousNameOfTable){
                $counter++;
                $previousNameOfTable = $tableName[] = $params[0];
            }
            $mas[$params[0]][$params[1]] = $value;
        }



        $query = new Query();

        // Производим запись в базу
        foreach($mas as $table => $fields) {

            $sql = "insert into $table (";
            $comma = $values = '';

            foreach($fields as $fieldName => $value) {
                $sql .= "$comma $fieldName";
                $values .= "$comma '$value'";
                $comma = ',';
            }
            $sql .= " ) values ( $values )";
            echo '<br>' . ($sql). '<br>';

            $result = $query->runSql($sql);
        }

        // Следует проверить цикл
        echo 'Осуществляем сверку массивов mas[0], mas[1] ... mas[n] с таблицами<br>';

        for($i = 0; $i<count($tableName); $i++)
        {
            echo 'Имя таблицы '.$tableName[$i].'<br>';
            $table = new formCreatorClass($tableName[$i]);
            $proverka = $table->sverka($mas[$i]);

            switch($proverka){
                case true:
                    echo 'Массив прошел проверку и может быть обработан';
                    break;
                case false:
                    echo 'Массив не прошел проверку';;
            }
            echo '<br>';
        }
    }

}