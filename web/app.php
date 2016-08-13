<?php
    
      require_once '../models/autoload.php';
    
    $arrPath = explode('/', $_REQUEST['path']);

    //var_dump($arrPath);


    switch ($arrPath[0]) {
        case 'customers': {
            switch ($arrPath[1])  {
                case 'registration':
                    $parameters = explode('?', $arrPath[2]);
                    $controller = new customersRegistrationController( $_GET['handler'] ? : 'roomProcessing.php', $_GET );
                    echo $controller->getFormRegistration();
                    break;
                case 'roomProcessing.php':
                    $room = new roomProcessing();
                    break;
                case 'authorization':
                    $controller = new customerAuthorizarionController();
                    $controller->getFormAuthorization();
                    break;
                case 'showtable':
                    $table = new tableDrawing($arrPath[2]);
                    echo $table->getTable();
                    break;
                default:
                echo 'Hello, customers!';
            }
            break;
       }
            break;
        case 'admin':
              echo 'admin';
              isAuthorization();
              break;
        case 'user':
        default:
            echo 'app.php'.var_dump($_REQUEST);  
        
    } 