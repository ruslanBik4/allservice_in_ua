<?php

class roomProcessing
{
    protected $data;

    protected $dataAfterProcessing;

    protected $debug;

    public function __construct($debug = false)
    {
        $this->debug = ($debug)? true : false;
        debug::VD($_POST, '$_POST '.__FILE__.' '.__LINE__ , $this->debug);
        $this->data = $_POST;
        $this->processing();
    }

    protected function processing()
    {
        $previousNameOfTable = '';

        // формируем массив вида $mas = [имя таблицы] [name of input] [значение]
        foreach ($this->data as $key => $value) {
            $params = explode(':', $key);
            if ($params[0] !== $previousNameOfTable) {
                $previousNameOfTable = $params[0];
            }
            if($params[1] == 'pass_sha1'){
                $value = hash("sha256", $value);
            }
            $this->dataAfterProcessing[$params[0]][$params[1]] =  $value;
        }
        $this->writeToDB();
    }

    protected function writeToDB(){
        $query = new Query();
        $lastId = [];

        foreach($this->dataAfterProcessing as $table => $fields) {
            $tableName = $table;
            if (count($lastId) > 0)
                foreach ($fields as $key => $value){
                    if(preg_match('/id_*/',$key)){
                        $fields[$key] = $lastId[$key];
                    }
                }
            debug::VD($tableName, '$tableName'.__FILE__.' '.__LINE__, $this->debug);
            debug::VD($fields, '$fields'.__FILE__.' '.__LINE__, $this->debug);
            $result = $query->runInsert($tableName, $fields);
            debug::VD($result, '$result'.__FILE__.' '.__LINE__, $this->debug);
            $lastId["id_$table"] = $result;
        }
    }
}
