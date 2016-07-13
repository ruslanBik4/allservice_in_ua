<meta charset="UTF-8">
<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    if($_POST['name']=='' || $_POST['country']==''){
        echo <<<END
        Вы ввели недостаточно данных<br>
        <a href='investors.html'><button>Перейти на страницу заполнения формы</button></a>
END;
        exit(-1);
    }

    $name = $investor->sanitizeString($_POST['name']);
    $country = $investor->sanitizeString($_POST['country']);
    $logotype = $investor->processingImage($_FILES['logotype']);
    $investor->addInvestor($name, $country, $logotype);
    echo <<<END
    <br>
    <br><a href='investors.html'><button>Перейти на страницу заполнения формы</button></a><br>
END;

echo <<<END
    <br>
    <iframe src='showInvestors.php' width='1000' height='1000' ></iframe>  
END;


?>

