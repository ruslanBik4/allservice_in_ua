<?php
  include_once('params.php');

   $name_page = ( $_REQUEST['name_page'] ? $_REQUEST['name_page'] : 'text_main.htm' );
   if ( file_exists( "$name_page.shtml" ) && (fileatime("$name_page.shtml") > fileatime("$name_page.htm")) )
   {  
     $text =  file_get_contents( "$name_page.shtml" );
   }
   else
     $text =  file_get_contents( "$name_page.htm" );
     
/*
    $begin  = strpos( $text, '<div' );
    $end    = strpos( $text, '</body>' );
    $text  = str_replace( 'margin: auto;', 'float:left;width:100%;', substr( $text, $begin, $end - $begin - 1 ) );
*/
   echo $text;  
?>
