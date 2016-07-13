<?php
require_once 'connectionData.php';
require_once 'investorClass.php';
$params = array($host, $user, $password, $database);
$investor = new investorClass($params);
$id = $investor->sanitizeString($_GET['id']);
$investor->showLogotype($id);
?>