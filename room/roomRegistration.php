<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 22:43
 */
require_once '../autoload.php';


echo "Для регистрации заполните форму:<br><br>";
$query = "input_form_info('client_registration')";
// Вытаскивает
$json = QueryOLD::sqlCurl($query);
$form = new formCreatorFromJsonClass($json);
// Передаем путь к файлу обработчику
echo ($form->formCreation('roomObrabotchik.php'));

 //Старая форма рабочая
//$table = new formCreatorClass('ref_users');
//echo ($table->formCreation('roomObrabotchik.php'));