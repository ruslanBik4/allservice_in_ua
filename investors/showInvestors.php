<?php
// Эти две строки моделируют работу сервера с кешированием
header("Cache-control: public");
header("Cache-control: max-age=1800");
?>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';


    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    echo($investor->showInvestor());    
?>