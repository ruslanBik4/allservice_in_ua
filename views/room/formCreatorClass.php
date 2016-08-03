<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 23:06
 */
class formCreatorClass
{
    // имя таблицы переданное пользователем
    public $name;
    // экземляр класса FieldsInfoRepository, который работает с ГО
    public $data;
    // все данные о колонках таблицы полученные из ГО
    public $tableColumn;
    // Храним имена полей таблицы
    public $tableNames = array();
    
    

    /**
     * roomClass constructor.
     * Принимает наименование таблицы.
     * Создаем экземляр класса FieldsInfoRepository
     * Вызываем метод FieldsInfoRepository getTable для получения массивом
     * всех параметров таблицы, записываем в свойство tableColumn
     * @param string $tableName
     */
    public function __construct($tableName = '')
    {
        $this->name = $tableName;
        $this->data = new FieldsInfoRepositoryOLD();
        $this->tableColumn = $this->data->getTable($tableName);
        
    }


    /**
     * Функция принимает путь к обработчику, возвращает готовую форму
     * Если обработчик не передан, считаем обработчиком текущую файл
     * @param null $obrabotchik
     * @return string
     */
    public function formCreation($obrabotchik = null){
        $print = "<form method='post' action='{$obrabotchik}'>";
        // Создаем скрытое поле tableName - для передачи имени таблицы
        $print.= "<input type='hidden' name='tableName' value='{$this->name}'>";
        try
        {
            foreach ($this->tableColumn as $key => $value){
                if(!is_array($value)) continue;
                    foreach ($value as $x => $y){
                        if($x == "COLUMN_NAME"){
                            $print.=$this->insertField($y);
                        }
                    }
            }
            $print.= '<input type="submit">';
            $print.= '</form>';
            return $print;
        }
        catch(Exception $e) {
            echo $e->GetMessage();
        }
    }


    /**
     * Возращает номер поля (номер массива) по заданному имени поля $columnName
     * @param $columnName
     * @return int|string
     */
    public function getArrayFromColumnName($columnName){
        foreach ($this->tableColumn as $key=>$value){
            if(is_array($value))
            {
                if(array_search($columnName, $value)){
                    return $key;
                }
            }
        }
        return 'Такого имени поля в таблице не существует';
    }


    /**
     * Функция принимает COLUMN_NAME, ищет по нему номер
     * массива (через функцию getArrayFromColumnName). Вытаскиваем нужный массив по номеру.
     * В нем и будут все параметры поля. Формируем label, input и возвращет в виде строки
     * @param array $fieldsParams
     * @return string
     */
    public function insertField($fieldName){
        $nomerMassiva = $this->getArrayFromColumnName($fieldName);
        $fieldsParams = $this->tableColumn[$nomerMassiva];
        if ($fieldsParams['COLUMN_NAME'] === 'id')
            return '';

        $type = $fieldsParams['DATA_TYPE'];
        $fieldType = '';
        $types = array('text' => 'text', 'char' => 'text', 'varchar' => 'text', 'int'=>'number', 'tinyint'=>'checkbox' );
        foreach ($types as $key => $value){
            if($type == $key){
                $fieldType = $value;
            }
        }
        $fieldName = $fieldsParams['COLUMN_NAME'];
        if(strpos($fieldsParams['COLUMN_NAME'], 'id_') !== FALSE)
            try {
                global $data;
                $table1 = substr( $fieldsParams['COLUMN_NAME'], 3);
                $fieldTitle = ($fieldsParams['TITLE'] ? : $fieldsParams['COLUMN_NAME']);
                $result = '';
                //Вставка №1//
            }
            catch( Exception $e) {
                echo $e->GetMessage();
            }
        else {

            $fieldTitle = ($fieldsParams['TITLE'] ? : ($fieldsParams['COLUMN_COMMENT'] ? : $fieldsParams['COLUMN_NAME']) );
            $fieldValue = $fieldsParams['COLUMN_DEFAULT'];
            $result = "<label>{$fieldTitle}</label><br><input type={$fieldType} name={$fieldName} value={$fieldValue}><br>";
        }
        return $result;
    }

    /**
     * Функция проверяет соответсвуют ли ключи массива пост ($params)
     * значениям COLUMN_NAME метаданным таблицы ($this->tableColumn)
     * Возвращает true если проверка пройдена
     * Возвращает false если выявили не соответствие
     * @param $params
     * @return bool
     */
    public function sverka($params)
    {
        reset($params);
        foreach($this->tableColumn as $key => $value)
        {
            if(!is_array($value)) continue;
            foreach ($value as $x => $y){
                if(($x == "COLUMN_NAME") && strpos($y, 'id')===FALSE){
                    if($y == key($params)){
                        next($params);
                    } else {
                        return false;
                    }

                }
            }

        }
        return true;
    }
}






// Вставка №1 - начало
// Имеет смысл при задании категорий
//                $result = 'Поля из ' . $table1. ': ' . "<label>{$fieldTitle}</label>
//        <select name={$fieldName} onchange='alert( \"div.$table1.show() \")'>
//        <option value=0 > Новый </option>
//        <option selected > Что-то есть</option>
//        </select><div id='$table1'> ";

// !!!Рекурсия не работает, так как нужно переопраделять $tableColumn
//                $arr = $this->data->getTable($table1);
//                echo '<pre>';
//                var_dump($arr);
//                echo '</pre>';
//                foreach ($arr as $key => $value){
//                    if(!is_array($value)) continue;
//                    foreach ($value as $x => $y){
//                        if($x == "COLUMN_NAME"){
//                            $result.=$this->insertField($y);
//                        }
//                    }
//                }



//                foreach ($arr  as $value)
//                    if(is_array($value)) {
//                        $result .= $this->insertField( $value );
//                    }
//                    else
//                        echo $value;

//     Имеет смысл при задании категорий
//                $result .= '<div>';
// Вставка №1 - конец








//      Старый рабочий вариант
//        try
//        {
//            foreach ($this->tableColumn as $value){
//                if(!is_array($value)) continue;
//                $print.= $this->insertField2($value);
//            }
//            $print.= '<input type="submit">';
//            $print.= '</form>';
//            return $print;
//        }
//        catch(Exception $e) {
//            echo $e->GetMessage();
//        }
