<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 28.07.2016
 * Time: 13:30
 */
// Подгрюзил и создал экзмеляр класса investorClass
// из него буду иcпользовать метод sanitizeString($string)
// что бы обезопасить содержимое
require_once '../investors/connectionData.php';
require_once '../autoload.php';
$params = array($host, $user, $password, $database);
$investor = new investorClass($params);

foreach ($_POST as $key => $value){
    $_POST[$key] = $investor->sanitizeString($_POST[$key]);
}

$tableName = array_shift($_POST);

$table = new FormCreatorClass($tableName);
$proverka = $table->sverka($_POST);
switch($proverka){
    case true:
        echo 'Массив POST прошел проверку и может быть обработан';
        break;
    case false:
        echo 'Массив POST не прошел проверку';;
}


