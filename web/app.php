<?php
    
    error_reporting(E_ALL);
    
    require_once '../models/autoload.php';
    
    $arrPath = explode('/', $_REQUEST['path']);
    

try {
    
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
                    $controller = new customersAuthorizarionController();
                    echo $controller->getFormAuthorization();
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
        case 'investors':{
            $controller = new investorsController();
            switch ($arrPath[1]){
                case 'admin':
                case 'edit':
                case 'add':
                case 'delete':
                case 'correct':
                case 'update':
                    $controller->$arrPath[1]();
                    break;
                default:
                    $controller->visitor();
                    break;
            }
        }

            break;
        case 'admin':
            switch ($arrPath[1])  {
                case 'tables':
                    $controller = new adminTablesController();
                    $content    = $controller->getResponse();
                    break;
                default:
                  echo 'admin';
            }
            break;
        case 'user':
            
            $controller = new usersDefaultController();
            $content    = $controller->getResponse();
            $leftContent = $controller->getLeftPanel();
        default:
            ?>
             <ul>
                 <li><a href="customers/registration">Регистрация</a></li> 
                 <li><a href="customers/authorization">Авторизация</a></li> 
                 <li><a href="customers/showtable/ref_clients">Таблица клиентов</a></li> 
                 <li><a href="customers/showtable/ref_users">Пользователи</a></li> 
                 <li><a href="customers/showtable/ref_roles">Роли</a></li> 
                 <li><a href="customers/showtable/ref_permissions">Права</a></li> 
                 <li><a href="customers/showtable/investors">Инвесторы</a></li> 
                 <li><a href="admin">Админка</a></li> 
                 <li><a href="admin/tables">Список всех таблиц</a></li> 
             </ul>
            <?php  
        
    } 
} catch( Exception $e) {
var_dump($e);
}
    ?>
    <div id="left_pane" style="float:left: width:230px"> <?=$leftContent?> </div>
    <div id="content" style="float:left: width:230px"> <?=$content?> </div>