<?php
function GetFileError( $fileError ) {
	
	//проверяем загрузку файла на наличие ошибок
	if($fileError > 0)
	{
	 //в зависимости от номера ошибки выводим соответствующее сообщение
	 //UPLOAD_MAX_FILE_SIZE - значение установленное в php.ini
	 //MAX_FILE_SIZE значение указанное в html-форме загрузки файла
	 switch ($fileError)
	 {
		 case 1: echo 'Размер файла превышает допустимое значение UPLOAD_MAX_FILE_SIZE'; break;
		 case 2: echo 'Размер файла превышает допустимое значение MAX_FILE_SIZE'; break;
		 case 3: echo 'Не удалось загрузить часть файла'; break;
		 case 4: echo 'Файл не был загружен'; break;
		 case 6: echo 'Отсутствует временная папка.'; break;
		 case 7: echo 'Не удалось записать файл на диск.'; break;
		 case 8: echo 'PHP-расширение остановило загрузку файла.'; break;
		 default:  echo 'Неизвестная ошибка - '.$fileError;
	 }
	 exit;
	}
 
}   

  if ( !isset($_REQUEST['table']))
    return;
  
  
  	  require_once('params.php');
	  
 try
 {  // обязательно должно быть имя таблицы и имя ключевого поля
	if($_FILES['file']['size'] > 0)
	{
		$fileName = $_FILES['file']['name'];
		$tmpName  = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];
		chmod($tmpName, 0777 ); 
		GetFileError( $_FILES['file']['error'] );
		
	  runSQL("LOAD DATA LOCAL INFILE '$tmpName' REPLACE INTO TABLE {$_REQUEST['table']} FIELDS TERMINATED BY  ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n'  ");
      echo "Успешно ".$conn->Affected_Rows()." записей импортировано"; //  ESCAPED BY  '\'
		unlink($fileName); //ENCLOSED BY  '"'
	}
   }
   catch(Exception $e)	   
   {
        $_SESSION['errors'] = $conn->ErrorMsg();
        $_SESSION['error_class'] = $e;
        $_SESSION['error_time'] = date('d.m.y H:i:s');
        
      echo "Ошибка. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>. <br>".$conn->ErrorMsg();
	}
?>