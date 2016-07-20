<?php
    require_once 'connectionData.php';
    require_once 'investorClass.php';
    $params = array($host, $user, $password, $database);
    $investor = new investorClass($params);
    $referAfterError = "<meta charset='utf-8'><br><br><a href='investors.php'><button>Перейти на страницу заполнения формы</button></a><br>";

    if(!isset($_POST['delete'])){
            echo $referAfterError;
            exit(-1);
    }

    $deleteId = $investor->sanitizeString($_POST['delete']);
    $delete = $investor->deleteInvestor($deleteId);
    switch ($delete) {
        case true:
            header('Location: showInvestors.php');
            break;
        case false:
            echo('Ошибка удаления данных:<br>');
            echo $referAfterError;
            die();
    }