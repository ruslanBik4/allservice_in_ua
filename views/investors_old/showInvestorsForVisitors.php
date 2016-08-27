<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 17.07.2016
 * Time: 19:04
 */
    require_once 'connectionData.php';
    require_once '../autoload.php';;
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
try {
    echo($investor->showInvestorsForVisitors());
} catch (Exception $e) {
    echo $e->getMessage();
}
?>