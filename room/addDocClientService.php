<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 23.07.2016
 * Time: 15:04
*/
require_once '../autoload.php';
$table = new roomClass('get_fields_info_windows.exe', 'doc_clients_services_parameters');
echo ($table->formCreation());

