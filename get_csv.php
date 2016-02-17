<?php
	
  error_reporting(3);
  session_start(); 
  
try
{
  $_REQUEST['codepage'] = 'cp1251'; 
  include_once('config_db.php');
//  		$conn->debug = true; 

 if ( isset($_REQUEST['table']) )
	  $table = $_REQUEST['table']; 
 else if ( isset($_REQUEST['key_parent']) )// если нет запроса, возможно,  это вызов категорий для просмотра
	 
	 $table =  GetParamFromCategory( $_REQUEST['key_parent'] );
 
 else
     return;
    
  $filename = ( ($pos=strpos( $table, ' ' )) ? substr( $table, 0, $pos ) : $table );
  
  if ( isset($_REQUEST['where']) )
  	$table .= ( strstr( $table, 'where' ) ? ' AND ' : ' WHERE ' )."  {$_REQUEST['where']}";

  ClearCashe(); // чтобы не брать все в юникоде
  
  $file = $_SERVER['DOCUMENT_ROOT']."/temp/$filename.csv";
  
  $fields = (isset($_REQUEST[ 'fields' ]) ? $_REQUEST[ 'fields' ] : '*' );
  $is_order = ( $_REQUEST['order'] ? " order by ".($order=$_REQUEST['order']) : '');
  
    $sql_text = mb_convert_encoding( "select $fields from $table $is_order", 'Windows-1251', 'UTF-8' ); 
  
   global $ADODB_FETCH_MODE;
   $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

  $recordSet=$conn->Execute( $sql_text );
    PutRecordSetToCSV( $recordSet, $file, ';' );
  
    //try if (file_exists($file))
    {
	//    header('X-Accel-Redirect: ' . $file);
	  // заставляем браузер показать окно сохранения файла
	    header('Content-Description: File Transfer');
	    header('Content-Type: text/csv');
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 36000');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	
	    header("Content-Disposition: attachment; filename=".basename($file) );
	    header('Content-Length: ' . filesize($file));
	    
	    // читаем файл и отправляем его пользователю
	    readfile($file);
		// освобождаем файл
		unlink($file);
	    
    }
/*
    else
     echo $sql_text;
*/
	 ClearCashe();   
}
catch(Exception $e)	   
{
    $_SESSION['errors'] = $conn->ErrorMsg();
    $_SESSION['error_class'] = $e;
    $_SESSION['error_time'] = date('d.m.y H:i:s');
    
  echo "Ошибка при создании файла экспорта CSV. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.".$conn->ErrorMsg();
}
exit;
?>