<?php

class roomProcessing
{
    // Массив $_POST до обработки
    protected $data;
    // Массив $_POST после обработки вида [имя таблицы] [name of input] [значение]
    protected $dataAfterProcessing;
    // Режим отладки: true - отладка, false - обычный
    protected $debug;

    /**
     * Конструктор принимает параметр $debug:
     *  - если false (по умолчанию) - обычный режим;
     *  - если true - активизирует метод debugger() для отладки
     * roomProcessing constructor.
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = ($debug)? true : false;
        $this->debugger($_POST);
        $this->data = $_POST;
        $this->processing();
        try{
            echo $this->writeToDB();
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Метод для вывод на экран информации о переменной при отладки
     * @param $param
     */
    public function debugger($param){
            debug::VD($param,  __FILE__.' '.__LINE__ , $this->debug);
    }

    protected function processing()
    {
        // Имя предыдущей таблицы
        $previousNameOfTable = '';

        // формируем массив вида [имя таблицы] [name of input] [значение]
        foreach ($this->data as $key => $value) {
            $params = explode(':', $key);
            if ($params[0] !== $previousNameOfTable) {
                $previousNameOfTable = $params[0];
            }
            if($params[1] == 'pass_sha1'){
                // Желательное хеширование
                //$value = passwordProcessing::encryptPass($value);

                // Хеширование на данный момент от Руслана
                $value = hash("sha256", $value);
            }
            $this->dataAfterProcessing[$params[0]][$params[1]] =  $value;
        }
    }

    /**
     * Метод для поочередной записи данных в разные таблицы, при возникновении ошибки записи выбрасывает исключение
     * @return string
     * @throws Exception
     */
    protected function writeToDB(){
        $query = new Query();
        // Массив номеров записей в таблицах вида [id_имя_таблицы] => [id_номер_записи]
        $lastId = [];

        foreach($this->dataAfterProcessing as $tableName => $fields) {

            if (count($lastId) > 0)
                foreach ($fields as $key => $value){
                    if(preg_match('/id_*/',$key)){
                        $fields[$key] = $lastId[$key];
                    }
                }
            $this->debugger($tableName, '$tableName'.__FILE__.' '.__LINE__, $this->debug);
            $this->debugger($fields, '$fields'.__FILE__.' '.__LINE__, $this->debug);
            $result = $query->runInsert($tableName, $fields);
            $this->debugger($result, '$result'.__FILE__.' '.__LINE__, $this->debug);
            if(!is_int($result)){
                throw new Exception("Ошибка записи {$result}");
            }
            $lastId["id_{$tableName}"] = $result;
        }
        return 'Запись в базу прошла успешно';
    }
}
