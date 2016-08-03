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
       

       foreach($pathInclude as $path) {
           
          $nameFile = "$ROOT_DIR/$path/$className.php";
          if (file_exists($nameFile)) {
            include_once($nameFile);
            break;
          }
     
      }
      
    }