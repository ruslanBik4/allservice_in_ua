<?php
    
    setlocale(LC_CTYPE, "ru_RU.UTF-8");
    
    $params = "./get_fields_info";
    
   foreach($_GET as $key => $value) {
     
     if ($key == 'program') {
        $params .= $value; 
     }  
     else {
         $params .= ' ' . escapeshellarg($value);       
         
     }
   }
    
   $output = [];
   echo exec( $params, $output ) . '<br>';
   
       foreach($output as $row)
        echo $row . ' <br>';

