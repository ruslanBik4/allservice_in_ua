<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 23.07.2016
 * Time: 15:04
*/
require_once '../autoload.php';

function insertField(array $fieldsParams){
    
    
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
        <option selected > Чуго-то есть</option> 
        </select><div id='$table1'> ";
       
        $arr = $data->getTable( $table1 );
        foreach ($arr  as $value)
            if(is_array($value)) {
                $result .= insertField( $value );
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

// TITLE - в лейбр если есть
// Если TITLE пустой, то в лэйбл COLUMN_COMMENT
//
$data = new FieldsInfoRepository('get_fields_info_windows.exe');
$param = 'doc_clients_services_parameters';
$table = $data->getTable($param);
//
echo '<pre>';
//var_dump($table);
echo '</pre>';
$print = '<form>';
try
{
foreach ($table as $value){
    if(!is_array($value)) continue;
    $print.= insertField($value);
}
$print.='</form>';
echo $print;
}
catch(Exception $e) {
    echo $e->GetMessage();
}

