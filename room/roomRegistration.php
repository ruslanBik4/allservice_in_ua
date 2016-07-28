<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 22:43
 */
require_once '../autoload.php';
echo "Для регистрации заполните форму:<br><br>";
require_once '../autoload.php';

echo '<form method="post" action="roomObrabotchik.php">';
//$table_1 = new roomClass('ref_clients');
//echo ($table_1->formCreation());
$table_2 = new roomClass('ref_users');
echo ($table_2->formCreation());
echo '<input type="submit">';
echo '</form>';
