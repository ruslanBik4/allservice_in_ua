<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 24.07.2016
 * Time: 22:43
 */
require_once '../autoload.php';
echo "Для регистрации заполните форму:<br><br>";
$table = new FormCreatorClass('ref_users');

echo ($table->formCreation('roomObrabotchik.php'));