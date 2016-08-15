<?php

class prepareAndRunRequest
{
    // Массив $_POST до обработки
    protected $data;
    // Массив $_POST после обработки вида [имя таблицы] [name of input] [значение]
    protected $dataAfterProcessing;
    // Режим отладки: true - отладка, false - обычный
    protected $debug;
    // Объект Query
    protected $query;
    // Номер записи для обновления, он же служит флагом обновления
    protected $where = null;

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
        $this->query = new Query();
        try{
            if($this->where){
                echo $this->runUpdate();
            } else {
                echo $this->runInsert();
            }
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

    /**
     * Преобразуем исходный массив
     * $_POST[имя_табялицы:имя_поля] => [значение] в многомерный массив вида
     *       [имя таблицы] [name of input] [значение]
     */
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

            // Определяем будет ли у нас update данных в базе.
            // Если да - определяем параметр where для обновления
            if ($params[1] == 'id'){
                $this->where = $value;
            }

            $this->dataAfterProcessing[$params[0]][$params[1]] =  $value;
        }
    }

    /**
     * Метод для поочередной записи данных в разные таблицы, при возникновении ошибки записи выбрасывает исключение
     * @return string
     * @throws Exception
     */
    protected function runInsert(){
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
            $result = $this->query->runInsert($tableName, $fields);
            $this->debugger($result, '$result'.__FILE__.' '.__LINE__, $this->debug);
            if(!is_int($result)){
                throw new Exception("Ошибка записи {$result}");
            }
            $lastId["id_{$tableName}"] = $result;
        }
        return 'Запись в базу прошла успешно';
    }

    /**
     * Метод для обновления записи в БД, при возникновении ошибки обновления выбрасывает исключение
     * @return string
     * @throws Exception
     */
    protected function runUpdate(){
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
            $result = $this->query->runUpdate($tableName, $fields, $this->where);
            $this->debugger($result, '$result'.__FILE__.' '.__LINE__, $this->debug);
            if(!is_int($result)){
                throw new Exception("Ошибка обновления {$result}");
            }
            $lastId["id_{$tableName}"] = $this->where;
        }
        return 'Обновление данных в базе прошло успешно';
    }
}
