<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    echo($investor->showInvestor());
//echo <<<END
//            <br>
//            <br><a href='index.html'><button>Перейти на страницу заполнения формы</button></a><br>
//END;
?>