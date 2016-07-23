<?php
require_once 'FieldsInfoRepository.php';
setlocale(LC_CTYPE, "ru_RU.UTF-8");
echo '<pre>';


// При создании объекта, путь к файлу 'get_fields_info' указывается как аргумент
// Стандартное значение аругмента '../get_fields_info'
$fieldsInfo = new FieldsInfoRepository('get_fields_info_windows.exe');

$all = $fieldsInfo->getAll(); // Информация о всех таблицах (временно не работает (пока приходит неправильный JSON))

$category = $fieldsInfo->getTable('category'); // Информация о таблице 'category'

var_dump($all);

var_dump($category);