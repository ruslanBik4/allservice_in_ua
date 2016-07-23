<?php
    
    setlocale(LC_CTYPE, "ru_RU.UTF-8");
    
    $params = "./";
   foreach($_REQUEST as $key => $value) {
     
     if ($key == 'program') {
        $params .= $value; 
     }  
     else {
         $params .= ' ' . escapeshellarg($value);       
         
     }
   }
    
   $output = [];
   echo "Run command '$params' <br> Result: <br>" . exec( $params, $output ) . '<br>';
   var_dump($output);
