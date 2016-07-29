<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 23:06
 */
class FormCreatorClass
{
    // имя таблицы переданное пользователем
    public $name;
    // экземляр класса FieldsInfoRepository, который работает с ГО
    public $data;
    // все данные о колонках таблицы полученные из ГО
    public $tableColumn;
    
    

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
        $this->data = new FieldsInfoRepository();
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
        try
        {
            foreach ($this->tableColumn as $value){
                if(!is_array($value)) continue;
                $print.= $this->insertField($value);
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
     * Функция принимает массивом все параметры одного поля,
     * формирует label, input и возвращет в виде строки
     * @param array $fieldsParams
     * @return string
     */
    public function insertField(array $fieldsParams){

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
// Имеет смысл при задании категорий
//                $result = 'Поля из ' . $table1. ': ' . "<label>{$fieldTitle}</label>
//        <select name={$fieldName} onchange='alert( \"div.$table1.show() \")'>
//        <option value=0 > Новый </option>
//        <option selected > Что-то есть</option>
//        </select><div id='$table1'> ";

                $arr = $this->data->getTable( $table1 );
                foreach ($arr  as $value)
                    if(is_array($value)) {
                        $result .= $this->insertField( $value );
                    }
                    else
                        echo $value;
//     Имеет смысл при задании категорий
//                $result .= '<div>';
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
    
}







// Временное пристанище старого (рабочего и не рабочего) кода

// Старый вариант рабочий с использованием case
//        switch ($type){
//            case 'text':
//            case 'char':
//            case 'varchar':
//                $fieldType = 'text';
//                break;
//            case 'int':
//                $fieldType = 'number';
//                break;
//            case 'tinyint':
//                $fieldType = 'checkbox';
//                break;
//        }