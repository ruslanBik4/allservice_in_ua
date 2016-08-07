<?php

class roomProcessing
{
    protected $data;

    protected $dataAfterProcessing;

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

        foreach ($this->data as $key => $value) {
            $params = explode(':', $key);
            if ($params[0] !== $previousNameOfTable) {
                $counter++;
                $previousNameOfTable = $tableName[] = $params[0];
            }
            $mas[$params[0]][$params[1]] = $value;
        }

        $this->dataAfterProcessing = $mas;

        $this->writeToDB();
    }

    protected function writeToDB()
    {

        $query = new Query();
        $id = NULL;

        // Производим запись в базу
        foreach($this->dataAfterProcessing as $table => $fields) {

            // Формируем запрос типа
            // insert into ref_users ( name, login, pass_sha1 ) values ( 'misha', 'mishamart', 'pass' )

            $sql = "insert into $table (";
            $comma = $values = '';


            foreach($fields as $fieldName => $value) {

                if($fieldName == 'pass_sha1'){
                    $value = passwordProcessing::encryptPass($value);

                    debug::VD($value, '$value'.__FILE__.' '.__LINE__);
                }

                $sql .= "$comma $fieldName";

                if(preg_match('/id_*/',$fieldName)){
                    $values .= "$comma '$result[0]'";
                } else {
                    $values .= "$comma '$value'";
                }
                $comma = ',';
            }
            $sql .= " ) values ( $values )";
            echo '<br>' . ($sql). '<br>';

            // Отправка запроса
            $result = $query->runSql($sql);
        }
    }

    // Функция сверки данных с таблицами, на данный момент в процессе реконструкции
    protected function collationOfData(){
        // Следует проверить цикл
//        echo 'Осуществляем сверку массивов mas[0], mas[1] ... mas[n] с таблицами<br>';
//
//        for($i = 0; $i<count($tableName); $i++)
//        {
//            echo 'Имя таблицы '.$tableName[$i].'<br>';
//            $table = new formCreatorClass($tableName[$i]);
//            $proverka = $table->sverka($mas[$i]);
//
//            switch($proverka){
//                case true:
//                    echo 'Массив прошел проверку и может быть обработан';
//                    break;
//                case false:
//                    echo 'Массив не прошел проверку';;
//            }
//            echo '<br>';
//        }
    }

}