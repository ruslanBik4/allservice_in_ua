<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    if(!isset($_POST['delete'])){
        echo <<<END
            <meta charset="utf-8">
            <br><a href='showInvestors.php'><button>Просмотреть всех инвесторов</button></a><br>  
END;
        exit(-1);
    }

    $deleteId = $investor->sanitizeString($_POST['delete']);
    header('Location: showInvestors.php');
    echo $investor->deleteInvestor($deleteId);
echo <<<END
            <meta charset="utf-8">
            <br><a href='showInvestors.php'><button>Просмотреть всех инвесторов</button></a><br>  
END;
