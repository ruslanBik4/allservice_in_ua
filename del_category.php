<?php

  require_once("params.php"); 

   try
   {
     $sql = "delete from category where key_category = ".$_POST['key_category'];
	 $recordSet = runSQL($sql);
	 echo "Успешно удалили запись №".$_POST['key_category'];  
   }
   catch(Exception $e)	   
   {
        $_SESSION['errors'] = $conn->ErrorMsg();
        $_SESSION['error_class'] = $e;
        $_SESSION['error_time'] = date('d.m.y H:i:s');
        
      echo "Ошибка. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.";
	}
  
?>
