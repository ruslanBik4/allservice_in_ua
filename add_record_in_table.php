<?php 

  require_once("config_db.php"); 

 try
 {  // обязательно должно быть имя таблицы и имя ключевого поля
	 if ( !($table = $_POST['table']) || !($key_name = $_POST['key_name']) ) 
		 throw Exception.Create( 'not table name' );
      
	 if (!isset($_POST['key_parent']) &&  isset($_POST['name_parent']) )
	 {
		 $sql = "select key_category from category where name = ".$conn->Qmagic( $_POST['name_parent'] ) ;
		 $recordSet = runSQL($sql);
		 
		  
	     if ($recordSet->fields[0] > 0)     
	    	 $_POST['key_parent'] =  $recordSet->fields[0];
	     else {
		     throw Exception.Create( 'Error '.$sql );
		     return;
	     }  
	 }
 	
	$name = $_POST['name']; 
	
/*  пока убираю - неизвестно, понадобиться ли вообще
	if ( !isset($_POST[$key_name]) &&  isset($_POST['name']) )
	{
	   $recordSet = runSQL( "select $key_name from $table where name = '$name' and key_parent = {$_POST['key_parent']}" );
       if ( $recordSet->fields[0] )     
		$_POST['id'] = $recordSet->fields[0];
	}
*/
	 
	  $is_boolean = array( 'is_view' );
	  $not_include = array( 'MAX_FILE_SIZE', 'name_parent', 'key_name', 'table', 'x', 'y', 'State', $key_name ); 
  
	  echo RunInsertUpdateSQL( $not_include, $is_boolean, $table, $key_name );
	  
   }
   catch(Exception $e)	   
   {
        $_SESSION['errors'] = $conn->ErrorMsg();
        $_SESSION['error_class'] = $e;
        $_SESSION['error_time'] = date('d.m.y H:i:s');
        
      echo "Ошибка. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.";
	}
?>