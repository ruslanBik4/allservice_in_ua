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
    if(strpos($fieldsPrams['COLUMN_NAME'], 'id_')){
        insertField($array )
    };
    $fieldName = $fieldsPrams['COLUMN_NAME'];
    $fieldTitle = ($fieldsPrams['TITLE'])? $fieldsPrams['TITLE']: $fieldsPrams['COLUMN_COMMENT'];
    $fieldValue = $fieldsPrams['COLUMN_DEFAULT'];
    $result = "<label>{$fieldTitle}</label><br><input type={$fieldType} name={$fieldName} value={$fieldValue}><br>";
    return $result;
}

// TITLE - в лейбр если есть
// Если TITLE пустой, то в лэйбл COLUMN_COMMENT

$data = new FieldsInfoRepository('get_fields_info.exe');
//$param = 'doc_clients_services_parameters';
$param = 'category';
$all = $data->getAll(); // Информация о всех таблицах (временно не работает (пока приходит неправильный JSON))
$table = $data->getTable($param);
echo '<pre>';
//var_dump($table);
echo '</pre>';
$print = '<form>';
foreach ($table as $value){
    if(!is_array($value)) continue;
    $print.= insertField($value);
}
$print.='</form>';
echo $print;


