<?php
    
    include 'loggerClass.php';
    $ROOT_DIR = __DIR__ . '/..';
    $logger = new loggerClass( isset($_REQUEST['debug']) );


    function __autoload($className)
    {
       global $ROOT_DIR, $logger;
       
       $pathInclude = [ 
           'models',
           'models/go_bridge',
           'models/config',
           'models/ui',
           'views',
           'views/tables',
           'views/forms',
           'views/menus',
           'views/room',
           'views/investors',
           'controllers',
           'controllers/customers',
           'controllers/investors',
           'isenka',
           'investors',
           'config'
       ];
       
       if (true) {
           
           // пробуем проанализировать имя класса, чтобы сразу определить путь до него
           $path = '';       
           $partWord = preg_split( "/(?=[A-Z])/", $className, -1, PREG_SPLIT_NO_EMPTY);
           
           $logger->addTextToLog( "Печатаю части имени класса '$className', по которым буду определять путь до его файла: <br>" );
           
           foreach($partWord as $part) {
               switch ($part) {
                   case 'Controller':
                        $path = "controllers$path";
                        break;
                   case 'View':
                        $path = "views$path";
                        break;
                   case 'admin':
                        $path = "$path/admin";
                        break;
                   case 'users':
                        $path = "$path/users";
                        break;
                   case 'Model':
                        $path = "models$path";
                        break;
                   case 'Customers':
                   case 'customers':
                   case 'customer':
                        $path = "$path/customers";
                        break;
                   case 'investors':
                       $path = "$path/investors";
                       break;
               }
               
               $logger->addTextToLog(  $part . '  ' );
           }
           
           // пробуем найти по одноименному пути
           if ($path) {
                          
              $logger->addTextToLog( "<br> Определил путь так - '$path', пробую присоединить файл $className.php ." ); 
              $nameFile = "$ROOT_DIR/$path/$className.php";
              if (file_exists($nameFile)) {
                include_once($nameFile);
                $logger->addTextToLog( "Успешно нашли и присоединили файлик $nameFile <br>" );
                return true;
              }
               
           }
           else {
               $logger->addTextToLog( 'Не удалось определить путь. <br>' );
           }
       }

       foreach($pathInclude as $path) {
           
          $nameFile = "$ROOT_DIR/$path/$className.php";
          if (file_exists($nameFile)) {
            include_once($nameFile);
            break;
          }
     
      }
      
    }