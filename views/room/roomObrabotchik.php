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
require_once '../../autoload.php';

// $investor = new investorClass([DBConfig::$HOST, DBConfig:$USER, DBConfig::$PASSWORD, DBConfig::$DATABASE]);

echo '<pre>Исходный массив POST<br>';
var_dump($_POST);
echo '</pre>';

// РАзбиваю ключи $_POST для получения имени таблицы, а также формурую для каждой таблицы отдельный массив
// mas[0], mas[1] ... mas[n]
$xold = '';
$i = -1;
$j = 0;
// По-прежнему ужасные имена переменных!!!
foreach ($_POST as $key => $value){
    $x = explode(':', $key);
    if($x[0] !== $xold){
        $i++;
        $xold = $tableName[] = $x[0];
    }
    $mas[$x[0]][$x[1]] = $value; // имена подмассивов (ключи) = имени таблицы
}
// заношу в базу

$query = new Query();

foreach($mas as $table => $fields) {  
    
    $sql = "insert into $table (";
    $comma = $values = '';

    foreach($fields as $fieldName => $value) {
        $sql .= "$comma $fieldName";
        $values .= "$comma '$value'";
        $comma = ',';
    }
    
    echo '<br>' . ($sql .= " ) values ( $values )"). '<br>';
    
    $result = $query->runSql($sql);
    
    var_dump($result);
    
}

echo '<pre>Преобразованный массив POST<br>';
var_dump($mas);
echo '</pre>';

echo '<pre>Массив имен таблиц<br>';
var_dump($tableName);
echo '</pre>';

echo 'Осуществляем сверку массивов mas[0], mas[1] ... mas[n] с таблицами<br>';
for($i = 0; $i<count($tableName); $i++)
{
    echo 'Имя таблицы '.$tableName[$i].'<br>';
    $table = new formCreatorClass($tableName[$i]);
    $proverka = $table->sverka($mas[$i]);

    switch($proverka){
    case true:
        echo 'Массив прошел проверку и может быть обработан';
        break;
    case false:
        echo 'Массив не прошел проверку';;
    }
    echo '<br>';
}











// Старая версия рабочая
//$tableName = array_shift($_POST);
//
//var_dump($tableName);

//$table = new FormCreatorClass($tableName);
//$proverka = $table->sverka($_POST);
//
//
//switch($proverka){
//    case true:
//        echo 'Массив POST прошел проверку и может быть обработан';
//        break;
//    case false:
//        echo 'Массив POST не прошел проверку';;
//}











// Старый рабочий вариант
//foreach ($_POST as $key => $value){
//    $_POST[$key] = $investor->sanitizeString($_POST[$key]);
//}
//
//$tableName = array_shift($_POST);
//
//var_dump($tableName);
//
//$table = new FormCreatorClass($tableName);
//$proverka = $table->sverka($_POST);
//switch($proverka){
//    case true:
//        echo 'Массив POST прошел проверку и может быть обработан';
//        break;
//    case false:
//        echo 'Массив POST не прошел проверку';;
//}


