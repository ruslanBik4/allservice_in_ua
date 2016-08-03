<?php
    
      require_once '../models/autoload.php';
    
    $arrPath = explode('/', $_REQUEST['path']);
        
    switch ($arrPath[0]) {
        case 'customers': {
            
            switch ($arrPath[1])  {
                case 'registration':
                    $parameters = explode('?', $arrPath[2]);
                    
//                     if ()
                    
                    $controller = new customersRegistrationController( $_GET['handler'] ? : 'roomObrabotchik.php', $_GET );
                    echo $controller->getFormRegistration();
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