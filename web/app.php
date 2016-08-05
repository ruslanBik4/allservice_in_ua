<?php
    
      require_once '../models/autoload.php';
    
    $arrPath = explode('/', $_REQUEST['path']);

    var_dump($arrPath);

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
                default:
                echo 'Hello, customers!';
            }
            break;
       }
        case 'admin':
              echo 'admin';
              
              isAuthorization();
        case 'user':
        default:
            echo 'app.php'.var_dump($_REQUEST);  
        
    } 