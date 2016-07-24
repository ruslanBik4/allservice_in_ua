<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 22:43
 */
require_once '../autoload.php';
echo "Для входа в личный кабинет заполните форму:<br><br>";
require_once '../autoload.php';
$table = new roomClass('get_fields_info_windows.exe', 'ref_clients');
echo ($table->formCreation());

