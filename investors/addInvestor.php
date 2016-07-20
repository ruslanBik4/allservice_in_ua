<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    $referAfterError = "<meta charset='utf-8'><br><br><a href='investors.php'><button>Перейти на страницу заполнения формы</button></a><br>";
    if($_POST['name']=='' || $_POST['country']==''){
        echo "Вы ввели недостаточно данных<br>";
        echo $referAfterError;
        exit(-1);
    }

    $name = $investor->sanitizeString($_POST['name']);
    $country = $investor->sanitizeString($_POST['country']);

    $logotype = $investor->processingImage($_FILES['logotype']);
    if(is_array($logotype)){
        switch ($logotype[0]){
            case 1:
                echo $logotype[1];
                echo $referAfterError;
                die();
            case 2:
                echo $logotype[1];
                echo $referAfterError;
                die();
            //Так как картинка не явлется обязательной ошибку отсутствия картинки отключаем
//            case 3:
//                echo "<meta charset='utf-8'>";
//                echo $logotype[1];
        }
    }

    $dobavlenieInvestora = $investor->addInvestor($name, $country, $logotype);
    switch ($dobavlenieInvestora){
        case true:
            //Записать успешна
            header('Location: investors.php');
            break;
        case false:
            echo 'Ошибка записи данных:<br>';
            echo $referAfterError;
            die();
    }
?>

