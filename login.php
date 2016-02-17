<?php	
  error_reporting(0);
  session_start(); 
try
{ 
  if (isset($_REQUEST['logout'])) // разавторизация
  {
    unset($_SESSION['customer']);
    unset( $_SESSION['admin'] );
    unset($_REQUEST['logout']);
  	  unset($_SESSION['is_admin']);
    include('login.htm');
    exit(0);
  }

  if ( !( isset($_REQUEST['login']) && isset($_REQUEST['password']) )  )
  {
    echo "Ошибка при вводе имени или пароля! login={$_REQUEST['login']} password={$_REQUEST['password']}";
    exit(0);
  }
  
  require_once('config_db.php');
// 	$conn->debug = true; 

  $login    = $conn->Qmagic( $_REQUEST['login'] );
  $password	= $conn->Qmagic( $_REQUEST['password'] );
  $recordSet = runSQL( $_SESSION['sql_text'] = "select * from users where login=$login and password = $password"); //.$conn->Qmagic( hash("sha256", $password) ) );
 
  if ($recordSet->EOF)
	  throw new Exception( "Ошибка при вводе имени и пароля! $login $password Либо запись не активизирована." );
	  
  // общие параметры
  try
  {
  	$_SESSION['customer'] = $recordSet->fields['key_users'];
  	$_SESSION['login']    = $recordSet->fields['login'];
  	$_SESSION['name_user'] = $recordSet->fields['descriptor'];
  	
  	if ( $recordSet->fields['is_admin'] )
  	{
  	   $_SESSION['admin'] = '12345';
  	   $_SESSION['is_admin'] = 'true';
	  	
  	}
  	else
  	  unset($_SESSION['is_admin']);
  	   
  	if (isset($_REQUEST['href']))
  	{
/*
	  	 header( "Location: {$_REQUEST['href']}", true, 301 );
	  	 
?> <script> document.location = '<?=$_REQUEST['href']?>';
   </script>
<?php
*/ }
  	else
  		echo $_SESSION['name_user'];
  }
  catch(Exception $e)
  {
	  $msg = 'Ошибка при входе на сайт - сообщите администратору сайта '.$e->getMessage();
/*
	  if ( SendMailTo($msg, 'W3easy@gmail.com', 'ошибка при входе на сайт') )
	  	echo $msg;
	  else
	    echo $msg.$e->getMessage();  
 
*/ }  
  
}
catch(Exception $e)
{
	$_SESSION['errors'] = $conn->ErrorMsg();
	$_SESSION['error_class'] = $e;
	$_SESSION['message'] = $e->Getmessage();
	$_SESSION['error_time'] = date('d.m.y H:i:s');
	
	echo "Ошибка при авторизации - {$_SESSION['message']}. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>. Флаги подключения - {$_SESSION['sql_text']}";

}   
?>