<meta charset="utf-8">
<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 22:43
 */
require_once '../autoload.php';

echo "Для входа в личный кабинет заполните форму:<br>";
$data = new FieldsInfoRepository('get_fields_info_windows.exe');
$param = 'ref_clients';
$table = $data->getTable($param);
//
echo '<pre>';
var_dump($table);
echo '</pre>';