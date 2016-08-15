<?php
    $ROOT_DIR = __DIR__ . '/..';

    function __autoload($className)
    {
       global $ROOT_DIR;
       
       $pathInclude = [ 
           'models',
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
           'isenka',
           'isenka/bridge',
           'investors',
           'models/go_bridge',
           'config'
       ];
       
       if (true) {
           
           // пробуем проанализировать имя класса, чтобы сразу определить путь до него
           $path = '';       
           $partWord = preg_split( "/(?=[A-Z])/", $className, -1, PREG_SPLIT_NO_EMPTY);

           echo "Печатаю части имени класса '$className', по которым буду определять путь до его файла: <br>";
           
           foreach($partWord as $part) {
               switch ($part) {
                   case 'Controller':
                        $path = "controllers$path";
                        break;
                   case 'Model':
                        $path = "models$path";
                        break;
                   case 'Customers':
                   case 'customers':
                   case 'customer':
                        $path = "$path/customers";
                        break;
                  
               }
               
               echo $part . '  ';
           }
           
           // пробуем найти по одноименному пути
           if ($path) {
                          
              echo "<br> Определил путь так - '$path', пробую присоединить файл $className.php . "; 
              $nameFile = "$ROOT_DIR/$path/$className.php";
              if (file_exists($nameFile)) {
                include_once($nameFile);
                echo "Успешно нашли и присоединили файлик $nameFile <br>";
                return true;
              }
               
           }
           else {
               echo 'Не удалось определить путь. <br>';
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