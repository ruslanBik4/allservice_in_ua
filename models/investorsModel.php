<?php

class investorsModel
{
    // переменная для хранения подключения к базе
    private $connectionToBase;


    /**
     * Создаем подключение к юазе, храним в connectionToBase
     * investorsModel constructor.
     */
    public function __construct()
    {
        $this->connectionToBase = new Query();
    }


    /**
     * Получает из БД весь список инвесторов для посетителей и отдает в контроллер
     * @return array
     * @throws Exception
     */
    public function showInvestorsForVisitors()
    {
        $queryShowInvestors = "SELECT name, country FROM investors ORDER BY id";
        $result = $this->connectionToBase->runSql($queryShowInvestors);
        if (!$result) {
            throw new Exception ('Ошибка чтения данных');
        }
        return $result;
    }


    /**
     * Получает из БД весь список инвесторов для администратора и отдает в контроллер
     * @return array
     * @throws Exception
     */
    public function showInvestors()
    {
        $queryShowInvestors = "SELECT id, name, country FROM investors ORDER BY id DESC";
        $result = $this->connectionToBase->runSql($queryShowInvestors);
        if (!$result) {
            throw new Exception ('Ошибка чтения данных');
        }
        return $result;
    }

    /**
     * Добавляет запись в таблицу Инвесторов, возвращает true (запись успешна), false (не успешна)
     * $table - название таблицы
     * $data - данные для записи
     * @param $table
     * @param $data
     * @return bool
     */
    public function addInvestor($table, $data)
    {
        $result = $this->connectionToBase->runInsert($table, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Функция удаляет запись из базы и возвращает bool (true успешно), (false не успешно)
     * $id - номер даписи для удаления
     * @param $id
     * @return bool
     */
    public function deleteInvestor($id)
    {
        $queryDeleteInvestor = "DELETE FROM investors WHERE id='{$id}' ";
        $result = $this->connectionToBase->runSql($queryDeleteInvestor);
        if($result = true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получает по номеру записи $id данные для редактирования и возвращет в контроллер
     * @param $id
     * @return array
     * @throws Exception
     */
    public function correctInvestor($id){
        $queryShowInvestor = "SELECT id, name, country FROM investors WHERE id='{$id}'";
        $result = $this->connectionToBase->runSql($queryShowInvestor);
        if (!$result) {
            throw new Exception ('Ошибка чтения данных');
        }
        return $result;
    }

    /**
     * Обновляет отреактированную записб в таблице $table, данные $data, по номеру записи вида (string)'id=38'
     * @param $table
     * @param $data
     * @param $id
     * @return bool
     */
    public function updateInvestor($table, $data, $id){
        $result = $this->connectionToBase->runUpdate($table, $data, $id);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Обезвреживает строку, возвращает обработанную строку
     * @param $string
     * @return string
     */
    public function sanitizeString($string)
    {
        $string = strip_tags($string);
        $string = htmlentities($string);
        $string = stripslashes($string);
        return $string;
    }
}
