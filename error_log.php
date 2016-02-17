<?php
function print_array( $array ) {
	
  $result = '';
  foreach( $array as $b => $param)
  {
	
	   $result .= "<br/> $b => ";  
	 try
	 { 
	   if ( is_array($param) )
	      $result .= print_array( $param );
	   else if ( is_object($param) )
	      $result .= print_r( $param );
	   else if ( is_string($param) )
	      $result .= $param;
	   else
	      $result .= print_r( $param );
	   
	  }
	   catch(Exception $e)	   
	   {
	     $result .= print_r($e);
	    }
 }
     
  return $result;
}
		session_start();
		error_reporting(2);
//   include_once('config_db.php');

?>
<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache" />

<title>Показ последней ошибки в сессии</title>
<link rel="stylesheet" type="text/css" href="css/market.css" media="screen">
</head>
<body>
 <div> <?="{$_SESSION['error_time']} <br> Пользователь - {$_SESSION['login']}( {$_SESSION['name_user']} ), {$_SESSION['errors']}, "?></div>
 <div> <?=print_array($_SESSION['error_class'])?></div>
 <div> <?=print_array($_SESSION)?></div>
</body>
