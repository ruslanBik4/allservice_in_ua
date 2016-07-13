<meta charset="utf-8">
<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    if(!isset($_POST['delete'])){
        echo <<<END
            <br>
            <br><a href='showInvestors.php'><button>Просмотреть всех инвесторов</button></a><br>  
END;
        exit(-1);
    }

    $deleteId = $investor->sanitizeString($_POST['delete']);
    echo $investor->deleteInvestor($deleteId);
echo <<<END
            <br>
            <br><a href='showInvestors.php'><button>Просмотреть всех инвесторов</button></a><br>  
END;
    header('Location: showInvestors.php');
