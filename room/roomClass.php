<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 23:06
 */
class roomClass
{
    public $tableColumn;
    public $data;
    public $name;

    public function __construct($tableName = '')
    {
        $this->name = $tableName;
        $this->data = new FieldsInfoRepository();
        $this->tableColumn = $this->data->getTable($tableName);
    }

    public function formCreation(){
        $print = '';
        try
        {
            foreach ($this->tableColumn as $value){
                if(!is_array($value)) continue;
                $print.= $this->insertField($value);
            }
//            if($this->name == 'ref_clients'){
//                $print.= $this->authorizationBySms();
//            }
            return $print;
        }
        catch(Exception $e) {
            echo $e->GetMessage();
        }
    }

//    private function authorizationBySms(){
//        $result = "<input type='button' value='Вход с помощью смс'>";
//        return $result;
//    }
    
    public function insertField(array $fieldsParams){

        if ($fieldsParams['COLUMN_NAME'] === 'id')
            return '';

        $type = $fieldsParams['DATA_TYPE'];
        $fieldType = '';
        switch ($type){
            case 'text':
            case 'varchar':
                $fieldType = 'text';
                break;
            case 'int':
                $fieldType = 'number';
                break;
            case 'tinyint':
                $fieldType = 'checkbox';
                break;
        }

        $fieldName = $fieldsParams['COLUMN_NAME'];

        if(strpos($fieldsParams['COLUMN_NAME'], 'id_') !== FALSE)
            try {

                global $data;

                $table1 = substr( $fieldsParams['COLUMN_NAME'], 3);

                $fieldTitle = ($fieldsParams['TITLE'] ? : $fieldsParams['COLUMN_NAME']);

                $result = 'Поля из ' . $table1. ': ' . "<label>{$fieldTitle}</label>
        <select name={$fieldName} onchange='alert( \"div.$table1.show() \")'> 
        <option value=0 > Новый </option> 
        <option selected > Что-то есть</option> 
        </select><div id='$table1'> ";

                $arr = $this->data->getTable( $table1 );
                foreach ($arr  as $value)
                    if(is_array($value)) {
                        $result .= $this->insertField( $value );
                    }
                    else
                        echo $value;

                $result .= '<div>';
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