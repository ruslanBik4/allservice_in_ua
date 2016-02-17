<?php
function ImportFromTable($table) {
global $connOut, $conn;
global $ADODB_FETCH_MODE;	
	    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

	$filename = ( ($pos=strpos( $table, ' ' )) ? substr( $table, 0, $pos ) : $table );
	$fileName = $_SERVER['DOCUMENT_ROOT']."/temp/$filename.csv"; 
	
	$recordSet = $connOut->Execute( "select * from $table " );
	
	$fd   = fopen($fileName, 'wb');

	$TERMINATED = ','; 
	$ENCLOSED = '"'; 
	$LINES_TERMINATED = "\r\n";
	
	foreach( $recordSet as $key => $row ) {
  		for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
  			fwrite( $fd, $ENCLOSED.$row[$i].$ENCLOSED.$TERMINATED );
		fwrite( $fd, $LINES_TERMINATED );
	}	 // while
	
   fclose($fd);	
	
	echo "<br> Успешно ".$connOut->Affected_Rows()." записей считано $table ";
	    
    $conn->Execute("LOAD DATA LOCAL INFILE '$fileName' REPLACE INTO TABLE $table FIELDS TERMINATED BY  ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n'  ");
	echo "<br> Успешно ".$conn->Affected_Rows()." записей импортировано в $table.".$recordSet->RecordCount(); 
	
	// освобождаем файл
	unlink($fileName);
	
}

  require_once('config_db.php');
  echo 'Начинаю процесс импорта '.date('d.m.y H:i:s');
  echo '<br>Очищаю кеш';
  
try
{
    ClearCashe();
	// подсоединяемся к базе сервера сбора данных	
	$connOut = &ADONewConnection('mysqli'); 
	$connOut->PConnect('nvh264.mirohost.net','u_ritos2x','ySLgLRrO','ritos2'); // основной вход      
	$connOut->Execute("SET NAMES utf8"); 
	if ( !$connOut )
	  echo "<br>not connect to host 'nvh264.mirohost.net'";
	
	ImportFromTable( 'prodaja' );
	
	//склад очищаем перед импортом
	$conn->Execute("TRUNCATE TABLE sklad");
	ImportFromTable( 'sklad' );

  echo '<br> Завершение процесса импорта '.date('d.m.y H:i:s');
	 
}
catch(Exception $e)	   
{
	$_SESSION['errors'] = $connOut->ErrorMsg();
	$_SESSION['error_class'] = $e;
	$_SESSION['error_time'] = date('d.m.y H:i:s');
	
	echo "<br> Ошибка. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.".$conn->ErrorMsg();
}
?>