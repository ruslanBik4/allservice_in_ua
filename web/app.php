<?php
    
      require_once '../models/autoload.php';
    
    $arrPath = explode('/', $_REQUEST['path']);

    //var_dump($arrPath);


    switch ($arrPath[0]) {
        case 'customers': {

            
            switch ($arrPath[1])  {
                case 'registration':
                    $parameters = explode('?', $arrPath[2]);
                    var_dump($_GET);
                    $controller = new customersRegistrationController( $_GET['handler'] ? : 'registration/?signin', $_GET );
                    break;
                case 'authorization':
                    $controller = new customerAuthorizarionController();
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
                 <li><a href="customers/showtable/clients">Клиенты</a></li> 
                 <li><a href="admin">Админка</a></li> 
                 <li><a href="admin/tables">Список всех таблиц</a></li> 
             </ul>
            <?php  
        
    } 
    ?>
    <div id="left_pane" style="float:left: width:230px"> <?=$leftContent?> </div>
    <div id="content" style="float:left: width:230px"> <?=$content?> </div>