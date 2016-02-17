<?php
function runSQL($rsql, $nrows=-1, $offset=-1, $inputarr=false, $secs2cache=0) {
	global $conn;
	
    $_SESSION['last_sql_text'] = $rsql;
	$recordSet = $conn->SelectLimit( $rsql, $nrows, $offset, $inputarr, $secs2cache );
	   
	if (!$recordSet) 
	 throw new Exception( $_RESULT['errors'] = $conn->ErrorMsg().'&lt;BR>', -1);
	 
	return $recordSet;
}
// получаем общее число записей для запроса, если это возможно
function GetRecordCount( $table, $fields, $recordCount ) {
global $ADODB_FETCH_MODE, $conn;	
	    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	
	try
	{ 
	  if ( strstr($table, 'group by' ) )
		 $recordSet = runSQL( "select count(*) from (select $fields from $table) W " );	  
	  else
		 $recordSet = runSQL( "select count(*) from $table " );
		 
	  $count = $recordSet->fields[0];
	 
	  $recordCount = ( $count > $recordCount ? $count : $recordCount );
		
	}
	catch(Exception $e)
	{
    $_SESSION['errors'] = $conn->ErrorMsg();
    $_SESSION['error_class'] = $e;
    $_SESSION['sql_text'] = $sql_text;
    $_SESSION['error_time'] = date('d.m.y H:i:s');
    
  $recordCount =   "Ошибка - {$_SESSION['errors']}. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.";
	}
	
	return $recordCount;
}
// получение значений полей для форматирования данных
function GetFieldProp($field_name) {
global $conn, $fieldSet, $arr_field;
global $ADODB_FETCH_MODE;
   $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
   
	$fieldSet = $conn->Execute( "select * from field_names where field_name = '$field_name'");
	
	if (!$fieldSet->EOF) 
		return $fieldSet->fields['title'];
	else
    	 return $field_name;
}
// удаляем паразитические условия после формирования условий из текста запроса
function RemoveEmptyConditions( $table ) {

   $table = preg_replace( '/ OR\s+(\(1=1\))*\s*(?=\))/', '', $table );
   $table = preg_replace( '/ AND\s+\(\w+=\'-1\'\)/', '', $table );
	
	return $table;
}
// получить значение из справочника для таблицы
// получаем значение ключа, имя поля, в котором оно содержалось в таблице и имя поля, которое нам надо вернуть
function GetValueFromID($id, $name, $cache_id=3600, $title='title', $table_name='' ) {
global $conn;
global $ADODB_FETCH_MODE;
   $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;

 if (!$table_name)
 	$table_name = 'rs'.substr($name, 2); 
 
 try
 {
	 $indexes = $conn->MetaIndexes( $table_name, true ); 
	 $key_name= ( array_key_exists('PRIMARY', $indexes) ? $indexes['PRIMARY']['columns'][0] : 'id' );
	 $parentSet = $conn->CacheExecute( $cache_id, "select * from $table_name where $key_name = $id" );
	
	if ( strpos( $conn->ErrorMsg(), "Unknown column '$key_name' in 'where clause'" ) > -1 )
		 $parentSet = $conn->CacheExecute( $cache_id, "select * from $table_name where $name = $id"); 
		 
	if (!$parentSet->EOF)  // есть результат!
		 return ( $parentSet->fields[$title] ? $parentSet->fields[$title] : $parentSet->fields[1] );
 }
 catch(Exception $e)
 {
        $_SESSION['errors'] = $conn->ErrorMsg();
        $_SESSION['error_class'] = $e;
        $_SESSION['error_time'] = date('d.m.y H:i:s');
	 
 }
 return ( $id ? $id : '-');
}

function ClearCashe($name) {
global $ADODB_CACHE;

 $ADODB_CACHE->flushall() ; //$conn->CacheFlush();
 return;
 
 try
 {
	 $table_name = 'rs'.substr($name, 2); 
	 $indexes = $conn->MetaIndexes( $table_name, true ); 
	 $key_name= ( array_key_exists('PRIMARY', $indexes) ? $indexes['PRIMARY']['columns'][0] : 'id' );
	$conn->CacheFlush( "select * from $table_name where $key_name = ?" );
 }	 
 catch(Exception $e)
 {
	// ошибку отсутствия таблицы игнорируем, так как ее может и не существовать
	 if ( strpos( $conn->ErrorMsg(), "Table 'clubok.$table_name' doesn't exist" ) == -1 )
		 throw $e;
	    
 }
}
// записываем данные выборки в csv стандартной фигурации ('"' обрамляют поле, ',' между поолями, перевод строки между записями)
// собственно говоря, это нужно, чтобы записать его в Вин-кодировке
function PutRecordSetToCSV( $recordSet, $file, $TERMINATED = ',', $ENCLOSED = '"', $LINES_TERMINATED = "\r\n" ) {
global $filename;

  $fd   = fopen($file, 'wb');
   
   // пишем заголовки
   for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
   {
	   $field = $recordSet->FetchField($i);
	   $name  = $field->name; 
/*
	   if ( substr($name, 0, 3 ) == 'id_' && (substr($name, 2) != substr($filename, 2)) )
	       ClearCashe($name);
*/
	       
      fwrite( $fd, $ENCLOSED.GetFieldProp($field->name).$ENCLOSED.$TERMINATED );
   }
      
   fwrite( $fd, $LINES_TERMINATED );
   
   // пишем данные   
	foreach( $recordSet as $key => $row ) {
  		for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
  		{
  			$field = $recordSet->FetchField($i);
  			$name  = $field->name; 
  			$value = ( substr($name, 0, 3 ) == 'id_' && (substr($name, 2) != substr($filename, 2)) ? GetValueFromID( $row[$i], $name, 300 )  : $row[$i] );
  			fwrite( $fd, $ENCLOSED.$value.$ENCLOSED.$TERMINATED );
	  		
  		}
		 
		fwrite( $fd, $LINES_TERMINATED );
	}	 // while
  fclose($fd);	

   // чистим кеш
   for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
   {
	   $field = $recordSet->FetchField($i);
	   $name  = $field->name; 
	   if ( substr($name, 0, 3 ) == 'id_' && (substr($name, 2) != substr($filename, 2)) )
	       ClearCashe($name);
	       
   }

}
// получаем параметры запроса SQL из таблицы CATEGORY по ключу 
function GetParamFromCategory($key_parent) {
global $ADODB_FETCH_MODE;	
	    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	    
	  $recordSet = runSQL( $_SESSION['sql_text'] = "select * from category where key_category=?", 1, -1, Array( $key_parent ), ( $admin_true ? 0 : 0 ) );
	  $table = $recordSet->fields['sql_text'];
      $_REQUEST['title'] = trim( $recordSet->fields['name'] );
      $_REQUEST['fields'] = ( trim( $recordSet->fields['fields'] ) ? $recordSet->fields['fields'] : '*' );
      if ( !isset($_REQUEST['order']) )
      	$_REQUEST['order'] = trim( $recordSet->fields['byOrder'] );
      
      if ( $recordSet->fields['summary'] )
      	$_REQUEST['summary'] = trim( $recordSet->fields['summary'] );
		
   if ( preg_match_all( '/&(\w+)\=(.+)/', $table, $macros, PREG_SET_ORDER ) ) // вычленяем данные запроса из строки
   {
		for( $i=0; $i <= sizeof($macros); $i++ )
		{
		 if ( !($param = $macros[$i][1]) )
		    continue;
	
		 $_REQUEST[$param] = $macros[$i][2];
	    }
	    $table = preg_replace( '/&(\w+)\=(.+)/', '', $table);
	} 
	else if ( !preg_match_all( '/(\w+)/', $table, $macros, PREG_SET_ORDER ) ) // русское название
	{
		$table = "`".( ($pos=strpos( $table, 'where' )) ? substr( $table, 0, $pos ) : $table )."`";
	}   
	else
	{
	}   

	  // вставляем макросы, которые переданы как параметры
	  $table = preg_replace( '/#\$check_(\w+)(%%)*\$#/e', '"$_REQUEST[$1]"', $table); //галочки
	  $table = preg_replace( '/#\$or_(\w+)(%%)*\$#/e', '"$_REQUEST[$1]"', $table); //необязательные текстовые поля (могут быть 1=1, если ничего не выбрано для них
	  $table = preg_replace( '/#\$(\w+)\$#/e', '"\'$_REQUEST[$1]\'"', $table);

   // вырезаем из запроса паразитические условия типа (1=1) 
   $table = RemoveEmptyConditions( $table );  

	return $table;
}
// для булева поля получаем булево значение из символа для показа
function GetBooleanValue($value) {
 
  $true_boolean = array( 'on', "✔︎" );
	
	return ( in_array( $value, $true_boolean ) ? 1 : 0 );
}
// вставка данных в таблицу
function RunInsertUpdateSQL($not_include, $is_boolean, $table, $key_name='id' ) {
 global $content, $conn, $key_id;
 
  $sql = $comma = $params = '';
  $issetKey_Fields = isset($_POST[$key_name]); // передано ключевое значение
  $values = array(); // для значение

  foreach($_POST as $name => $value )
  { //echo " $name = $value <br>";
    if ( in_array( $name, $not_include ) || !(in_array( $name, $is_boolean ) || $value) // пустые пишем только булевы 
    		|| ($name == 'photo') || ($name == $key_name) ) 
       continue;
     
    if ( $issetKey_Fields )
     $sql .= "$comma$name=?";
    else
    {
     $sql .= "$comma$name";
	 $params .= "$comma?"; 
    }
    
    $values[$name] = ( is_array($value) ? implode(",", $value) : ( in_array( $name, $is_boolean ) ? GetBooleanValue($value) :  $value ) ); //булевых переводим в 0/1
    
    $comma = ', ';
  }
	   
/*
	   print_r($_POST);
	   print_r($values);
*/
	   
  if ($content) // тут пока привязка к полю photo - потом надо будет сделать ее универсальной
  {
	 $values .= $comma.( $issetKey_Fields ? 'photo=' : '')."'$content'";
	 $sql .= "$comma photo";
  }  
 	if ( $issetKey_Fields ) 
	{
	  $values[] = $key_id = $_POST[$key_name];
	  $sql = "update $table set $sql where $key_name = ?";
	  $recordSet = $conn->Execute( $sql, $values );
	  return  "Успешно изменили запись №$key_id в таблице $table! ";
	}
	else
	{
      $sql = "insert into $table ( $sql ) values ( $params )";
	  $recordSet = $conn->Execute($sql, $values);
	  $key_id = $conn->Insert_ID();
	  return   "Успешно добавили запись №$key_id в таблице $table ! ";
    }   
}

  error_reporting(3);
  session_start(); 

  // подключаем АдоДБ
  require_once('adodb/adodb.inc.php');
  require_once("adodb/adodb-exceptions.inc.php"); 
    
try
{
	 $pwd = rawurlencode('iizJ3KKZ');
     $flags = MYSQL_CLIENT_COMPRESS.','.MYSQL_OPT_LOCAL_INFILE;
     $dsn = "mysql://u_allservi:$pwd@localhost/allservi?persist&clientflags=$flags#meta";
     $conn = ADONewConnection( $dsn );  	
// 	$conn->debug = true; 
	// для исполнения LOCAL INTO
	mysqli_options( $conn->connectionId, MYSQL_OPT_LOCAL_INFILE );
/*
	$conn = &ADONewConnection('mysql', 'meta'); 
	$conn->PConnect('localhost','u_clubok','rIwriaoQ','clubok'); // основной вход      
*/
    if ( isset($_REQUEST['codepage']) )
	    $conn->Execute("SET NAMES '{$_REQUEST['codepage']}'"); 
    else     
		$conn->Execute("SET NAMES utf8");
// 	$ADODB_CACHE_DIR = '/tmp/ADODB_cache';
	
	if ( $conn )
	  echo  $conn->Version();
	else
	  throw new Exception( "Нет соединения с БД!." );
	 
}
catch(Exception $e)	   
{
    $_SESSION['errors'] = $conn->ErrorMsg();
    $_SESSION['error_class'] = $e;
    $_SESSION['error_time'] = date('d.m.y H:i:s');
    
  echo "Ошибка при подключении к БД - {$e->Getmessage()}. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>. Флаги подключения - $flags";
  exit;
}
?>