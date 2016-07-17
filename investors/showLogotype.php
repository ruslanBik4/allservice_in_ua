<?php
require_once 'connectionData.php';
require_once 'investorClass.php';
$params = array($host, $user, $password, $database);
$investor = new investorClass($params);
$id = $investor->sanitizeString($_GET['id']);
$logotype = $investor->showLogotype($id);
switch ($logotype){
    case false:
        die();
    default:
        header('Content-type: image/*');
        echo $logotype;
}