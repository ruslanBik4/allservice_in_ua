<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 23.07.2016
 * Time: 15:04
*/
require_once '../autoload.php';

function insertField(array $fieldsPrams){
    $type = $fieldsPrams['DATA_TYPE'];
    switch ($type){
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
    
    if(strpos($fieldsPrams['COLUMN_NAME'], 'id_') !== FALSE)
    try {
        
        global $data;
        
        $table1 = substr( $fieldsPrams['COLUMN_NAME'], 3);
       
        $result = '';
        $arr = $data->getTable( $table1 );
        foreach ($arr  as $value)
            if(is_array($value)) {
                $result .= insertField( $value );
            }
            else
               echo $value;
    }
    catch( Exception $e) {
        echo $e->GetMessage();
    }
    else {
    
        $fieldName = $fieldsPrams['COLUMN_NAME'];
        $fieldTitle = ($fieldsPrams['TITLE'] ? : ($fieldsPrams['COLUMN_COMMENT'] ? : $fieldsPrams['COLUMN_NAME']) );
        $fieldValue = $fieldsPrams['COLUMN_DEFAULT'];
        $result = "<label>{$fieldTitle}</label><br><input type={$fieldType} name={$fieldName} value={$fieldValue}><br>";
    }
    
    return $result;
}

// TITLE - в лейбр если есть
// Если TITLE пустой, то в лэйбл COLUMN_COMMENT

$data = new FieldsInfoRepository();
$param = 'doc_clients_services_parameters';
$table = $data->getTable($param);
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

