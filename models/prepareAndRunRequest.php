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
    // Отвечает за действие: signin - авторизация, signup - регистрация, update - обновление данных
    protected $action;

    /**
     * * Конструктор принимает параметр $debug и $action:
     * $debug:
     *  - false (по умолчанию) - обычный режим;
     *  - true - активизирует метод debugger() для отладки
     * prepareAndRunRequest constructor.
     * $action:
     *  - signin - авторизация
     *  - signup - регистрация
     *  - update - обновление данных
     * @param string $action
     * @param bool $debug
     */
    public function __construct($action = '', $debug = false)
    {
        $this->debug = ($debug)? true : false;
        $this->debugger($_POST);
        $this->data = $_POST;
        $this->processing();
        $this->query = new Query();
        try{
            // авторизация
            if($action == 'signin') {
                $this->runPasswordVerification();
                header("Location: http://allservice_in_ua.loc/customers/office");
            }
            // регистрация
            elseif($action == 'signup'){
                echo $this->runInsert();
            }
            // обновление
            elseif($action == 'update') {
                $this->runUpdate();
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

            if($params[1] == 'pass_sha1' && $this->action == 'singup'){
                // Желательное хеширование
                $value = passwordProcessing::encryptPass($value);
                var_dump($value);

                // Хеширование от Руслана
                //$value = hash("sha256", $value);
            }

            // Определяем будет ли у нас update данных в базе.
            // Если да - определяем параметр where для обновления
            if ($params[1] == 'id'){
                $this->where = $value;
            }

            // Планирую добавить маркер сверки данных
//            if(strpos(($params[1]), 'id_')){
//                $this->relation = true;
//            }

            $this->dataAfterProcessing[$params[0]][$params[1]] =  $value;
        }
    }

    /**
     * Метод проводит сверку пароля, если ок, перебрасывает на страницу личного кабинета
     * @return string
     * @throws Exception
     */
    protected function runPasswordVerification(){
        foreach($this->dataAfterProcessing as $tableName => $fields)
        {
                $sql = "SELECT pass_sha1 FROM {$tableName} WHERE login='{$fields['login']}'";
                // Получаем из базы хъш пароля в виде array(['pass_sha1'] => хэш_пароля)
                $hash = $this->query->runSql($sql);
                // Проводим проверку, совпадает ли наш хэш, с хэшем из базу
                // $password_verification = true - совпадают,
                // $password_verification = false - не совпадают.
                $password_verification = passwordProcessing::verificationPassAndHash($fields['pass_sha1'],$hash['pass_sha1']);

                if(!$password_verification){
                    throw new Exception("Логин и пароль не совпадает");
                }
        }
        return 'Авторизация прошла успешно';
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
