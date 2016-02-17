<?php
  error_reporting(0);
  session_start(); 

	if( !$_SERVER['HTTP_REFERER'] && ( !$_SERVER['HTTP_X_REQUESTED_WITH'] || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' )  && !strstr( $_SERVER['REQUEST_URI'], "show_record.php" ) ) 
	{
		include('index.html');
		exit(0);
	}
	
	if( isset($_SESSION['customer']) )
	{
		if ( !isset($_REQUEST['user']) ) // для залогиненного пользователя и некоторых скриптов
			 $_REQUEST['user'] = $_SESSION['login'];
			
	} 
	else {
		
	  if( strstr( $_SERVER['REQUEST_URI'], "show_tovar.php" ) 
		  || strstr( $_SERVER['REQUEST_URI'], "loadpage.php" )  ) // случай, когда показываем окно авторизации
	    include('login.htm');
	    
	  if( !strstr( $_SERVER['REQUEST_URI'], "login.php" ) )
	   exit(0);		
	}
// раскладка массива для печати	
function print_array( $array ) {
  $result = '';
  foreach( $array as $b => $param)
   if ( is_array($param) )
      $result .= print_array( $param );
   else
      $result .= "$b => $param<br/>";
  
  return $result;
}

function isAdmin() { 
	
  if ( isset($_SESSION['customer']) && ($_SESSION['admin'] == '12345') )
    return isset($_REQUEST['admin']);

  if ( isset($_REQUEST['pass']) )
	 $isMode = ( $_SESSION['admin'] = $_REQUEST['pass'] );
  else
    $isMode = (isset($_REQUEST['admin']));

  return ( $isMode && ($_SESSION['admin'] == '12345') );

}
function SendMailTo($msg, $email_address, $subject) {

	  $email_from_mail = $email_from = 'W3easy@gmail.com';
	          
	  $subject = "=?windows-1251?B?"
	          .base64_encode($subject)
	          ."?=";
	
	  $headers =
	       "Return-Path: <".$email_from_mail.">\n"
	      ."From: ".$email_from."\n"
	      ."Errors-To: ".$email_from."\n"
	      ."Message-ID: <".md5($email_from)."@my.ru>\n"
	      ."X-Mailer: PHP v.".phpversion()."\n"
	      ."Content-Type: text/html;\n\tcharset=\"windows-1251\"";
	
	  return ( mail($email_address, $subject, $msg, $headers) );

}
// стиль показа для разных типов полей
function StyleInput($type) {
	   switch ($type) 
	   {
		 case 'string': 
			return 'search';  
		 case 'int': 
			return 'number';  
		 case 'timestamp': 
			return 'datetime';
		 case 'blob': 
			return 'textarea';  
		 default:
		    return 'text';  
	   }
	
}
// минимальный размер поля для разных типов полей
function GetLengthFromType($type) {
	   switch ($type) 
	   {
		 case 'select': 
			return '120';  
		 case 'checkbox': 
			return '50';  
		 case 'number': 
			return '70';  
		 case 'date': 
			return '110';
		 case 'datetime': 
			return '140';
		 case 'timestamp': 
			return '140';
		 case 'textarea': 
			return '100';
		 default:
		    return '100';  
	   }
	
}

// оборачивает элементы в форму с событием onsubmit
function WrapForm( $nameForm, $action, $onsubmit, $elements, $style, $oninput='return FormIsModified( event, this);', $title, $add_attributes='' ) {
	return "<form name='$nameForm' id='$nameForm' class='form-horizontal' role='form' action=\"$action\"  method='post'  target='content' style='width:100%; outline: 3px red;$style' onsubmit='$onsubmit' enctype='multipart/form-data' title='$title' $add_attributes ".GetAttrFromValue( 'oninput', $oninput )."  >$elements<output name='State' ></output> <img id='loading$nameForm' src='http://artel.ws/solution/loading.gif' style='display:none;'> 
          <progress id='progress$nameForm' value='0' max='100' hidden > </progress> 
          </form>";
}
// оборачивает элементы в простую форму с стандартным событием onsubmit SaveObject
function WrapSimpleForm( $nameForm, $action, $elements, $style, $title, $add_attributes='' ) {
	return "<form name='$nameForm' id='$nameForm' class='form-simple tr' role='form' action='$action'  method='post'  target='content' style='width:auto;$style' onsubmit='return SaveObject( this );' enctype='multipart/form-data' title='$title' oninput='return FormIsModified( event, this);' $add_attributes onabort='alert($nameForm);'>$elements</form>";
}
// вставка атрибута, если есть его значение
function GetAttrFromValue( $text, $value) {
	return ( $value ? " $text='$value' " : '' );
}
// создаю скрытое поле для формы
function GetHiddenInput($name, $value) {
	return "<input name='$name' type='hidden' value='$value' />";	
}
// создаю текстовое поле ввода
function GetTextInput($name, $placeholder, $value, $style, $type='search', $formName, $oninput="return FormIsModified(event, this.form);", $required) {
	
	if ( ($type=='checkbox') || ($type=='radio') )
	{
		$value = ( $value ? ' checked ' : 'unchecked' );
		$input_class = "input-center";
// 		$value .= "onclick='$oninput' ";
// 		$style = 'max-width:50px;';
	  return "<input type='$type'  class='$input_class' name='$name' $value ".GetAttrFromValue( 'placeholder', $placeholder ).GetAttrFromValue( 'style', $style ).GetAttrFromValue( 'form', $formName ).GetAttrFromValue( 'onkeyup', $oninput ).GetAttrFromValue( 'onchange', $oninput )." $required />";
	}
	else
	{
		$input_class = 'form-control';
		$value = GetAttrFromValue( 'value', $value );
	}
		
	return "<input type='$type'  class='$input_class' name='$name' $value ".GetAttrFromValue( 'placeholder', $placeholder ).GetAttrFromValue( 'style', $style ).GetAttrFromValue( 'form', $formName ).GetAttrFromValue( 'onkeyup', $oninput ).GetAttrFromValue( 'onchange', $oninput )." $required />";
}
// для большого текста
function GetTextArea($name, $placeholder, $value, $style, $formName, $oninput="return FormIsModified(event, this.form);", $required){
		  return "<textarea name='$name' ".GetAttrFromValue( 'style', $style ).GetAttrFromValue( 'form', $formName)." onchange='$oninput' oninput='$oninput' cols=1 $required>$value</textarea>";
}
// для дат
function GetDateInput($name, $placeholder, $value, $style, $type='date', $formName, $onchange="return FormIsModified(event, this.form);", $required) {
	
	return "<input type='$type' name='$name' ".GetAttrFromValue( 'value', $value ).GetAttrFromValue( 'placeholder', $placeholder ).GetAttrFromValue( 'style', $style ).GetAttrFromValue( 'form', $formName ).GetAttrFromValue( 'onchange', $onchange )." $required />";
}
function GetImageInput( $src, $title, $hidden ) {
	return "<input type='image' $hidden src='$src' title='$title' />";
}

// формирование групп колонок
function GetGrouptitle() {
global $fieldSet, $in_group;
	   if ( $in_group != $fieldSet->fields['name_group'] )
	   {
		   if ( $in_group ) // закрываем старую группу
		   	  return '</fieldset>';
		   	  
		   if ( ($in_group = $fieldSet->fields['name_group']) ) // начинаем новую группу
			    return "<fieldset class='input-group panel'> <p class='name_group' > $in_group</p> ";
		    
	   }
	return '';
}
// формирую поля ввода для формирования запроса из макросов строки категории
function GetFieldFromMacros( $type_date, $macros, $button, $onchange ) {
global $fieldSet, $in_group, $macrosU;
	
	$text = $in_group = ''; 
	// макросы могут повторяться, потому удаляем дубликаты
	$macrosU    = array_map("unserialize",array_unique(array_map("serialize",$macros)));
	$type_date = ( (sizeof($macros) > 1) && ($type_date == 'week') ?  'date' : $type_date );
	
	// создаем элементы ввода для макросов
	for( $i=0; $i <= sizeof($macrosU); $i++ )
	{
	 if ( !($param = $macrosU[$i][1]) )
	    continue;

	 $required = ( $macrosU[$i][2] =='%%' ? 'required' : '' );
	 if ( strstr( $param, 'date' ) ) // выставляем поля для дат двух типов (день либо месяц)
		 $text .= GetDateInput( $param, "ГГГГ-ММ-ДД", GetParamOrDefault( $param, ($type_date == 'date' ? strftime('%Y-%m-%d') : strftime('%Y-%m') ) ), "max-width:90%", $type_date, '', $onchange, "required title='$param'" );
	 else if( substr($param, 0, 3 ) == 'id_' ) 
	 {
			global $ADODB_FETCH_MODE;	
		    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	    
			$recordSet = runSQL("select * from rs_".substr($param, 3) );
			$options = "<option value='-1' noselect >".($title=GetFieldProp( $param ))."</option>";
			foreach( $recordSet as $key => $row ) 
			    $options .= "<option value='{$row[0]}'> {$row[1]}</option>";
			    
			$text .= GetGrouptitle()."<select  class='form-control' name='$param' onchange='$onchange' style='max-width:90%'  title='$title' $required value='-1' > $options </select>";
	 }
	 else if ( strstr( $param, 'check' ) ) // выставляем галочки
	 {
		 $fieldName = substr($param, 6); 
		 $title		= GetFieldProp( $fieldName );
	 
		 $text .= GetGrouptitle()."<label class='checkbox-inline'> <input type='checkbox' name='$fieldName' value='".GetParamOrDefault( $param, '' )."' onchange='$onchange' title='$title' $required />$title</label>";
	 }
	 else if ( substr($param, 0, 3 ) == 'or_' ) // необязательные текстовые поля
	 {
		 $fieldName = substr($param, 3); 
		 $title	= GetFieldProp( $fieldName );
		 $text .= GetTextInput( $fieldName, $title, GetParamOrDefault( $param, '' ), "max-width:90%", 'search', '', '', "$required title='$title' data-param='or'" );
	 }
	 else
	 {
		 $title	= GetFieldProp( $param );
		 $text .= GetTextInput( $param, $title, GetParamOrDefault( $param, '' ), "max-width:90%", 'search', '', '', "$required title='$title'" );
	 }
	 
	 }// for
	 
   if ( $in_group ) // закрываем последнюю группу
   	  $text .= '</fieldset>';
		   	  
	
	if ( (sizeof($macrosU) > 1) || ($button != '⏎') ) 
	  return $text."<button type='submit' name='Add' value='$button' > <span class='glyphicon glyphicon-import'></span> </button>";
	else
	  return "<div class='input-group'>$text<span class='input-group-btn'> <button class='btn btn-success' type='button'><span class='glyphicon glyphicon-download'></span></button> </span> </div>";
}

// date
 // получение дня и месяца в цифрах
 function GetShortDate( $date ) {
	 return preg_replace( "/(19|20)(\d{2})-(\d{1,2})-(\d{1,2})/", "\\4.\\3.", $date);
 }

// получение полной даты прописью
 function GetFullStringDate( $date ) {
	 return strftime('%d %B %Y', strtotime( $date ) );
 }
// получение имени месяца
function GetRusMonth( $date ) {
	 return strftime('%B', strtotime( $date ) ) ;
}
// получение дня недели
function GetRusDayWeek( $date ) {
	 return strftime('%A', strtotime( $date ) ) ;
}

// Обрезка текста по пробелу
function cropStr($str, $size){ 
  $str = substr( strip_tags($str), 0, $size); // первым этапом надо отрезать строку четко по заданному количеству символов
  return substr($str, 0, strrpos($str, ' ' )).'...';    //получаем позицию последнего пробела и обрезаем до нее строку
}
// эти две функции нужны для показа кликабельных ссылок в тексте и показа текста БЕЗ этих ссылок
function GetURL($memo) {
  return preg_replace( '/((https?|ftp):\/\/)*(www\.[\S]+\.[\S]+)/si', '<a href="http://$3" target="_blank" title="$3"> Ссылка </a>', $memo);
}
function DelURL($memo) {
  return preg_replace( '/((https?|ftp):\/\/)*(www\.[\S]+\.[\S]+)/si', '', $memo);

}
// расчет значения поля по формуле
function GetValueFormula( $formula ) {
global $recordSet;
 
  preg_match_all('/(?P<field>\w+)(?P<znak>[\-\+*\/])(?P<mnog>[\d.]+)?/', $formula, $matches);
  $value = $znak = '';

//   print_r($matches);
  
  foreach( $matches['field'] as $id => $result )
  {
  	$resul_value = $recordSet->fields[$result];
  	switch ( $matches['znak'][$id] )
  	{
	  	case '*':
		  	$resul_value = $matches['mnog'][$id] * $resul_value;
		  	break;
	  	case '/':
		  	$resul_value = $matches['mnog'][$id] / $resul_value;
		  	break;
	}
  	switch ( $znak )
  	{
	  	case '-':
		  	$value = $value - $resul_value;
		  	break;
	  	case '+':
		  	$value = $value + $resul_value;
		  	break;
		default:  	
		  	$value = $resul_value;
	  	
  	}
	  	
  	$znak = $matches['znak'][$id];
  }
  
  return "=$value";
}

 // считываем значения полей в переменные - устарела
function ReadFields( $recordSet ) {
global $key_parent, $name, $memo, $key_tovary, $spec, $new_tovar, $in_store, $is_view, $tags, $date_sys, $annotion;

	   $name  = htmlspecialchars( $recordSet->fields['name'] );
	   $memo  = $recordSet->fields['memo'];
	   $tags  = $recordSet->fields['tags'];
	   $is_view = $recordSet->fields['is_view'];
 	   $in_store   = $recordSet->fields['in_store'];
 	   $new_tovar  = $recordSet->fields['new_tovar'];
	   $key_tovary = $recordSet->fields[0];
 	   $spec	   = $recordSet->fields['spec']; 
 	   $date_sys   = $recordSet->fields['date_sys']; 
 	   $annotion   = $recordSet->fields['annotion']; 
	
}
//считываем шаблоны страниц, сделанныке в СК
function ReadTemplateFromHtml($HTMLFileName) {
	// Создаем поток
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>"Accept-language: ru\r\n text/html;charset=utf-8" .
	              "Cookie: foo=bar\r\n"
	  )
	);
	
	$context = stream_context_create($opts);
	
	 $show_lot = strstr( file_get_contents( $HTMLFileName, false, $context), '<div' );
     $show_lot = substr( $show_lot, 0, strpos( $show_lot, '</body>') );
     $show_lot = str_replace( 'margin: auto;', 'float:left;width:100%;', $show_lot );
	
	return $show_lot;
}
function GetParamOrDefault ( $name_param, $default ) {
	return (isset($_REQUEST[ $name_param ]) ? $_REQUEST[ $name_param ] : $default );
}
// получене параметров скрипта для дополнительных ссылок
function GetParamsToString() {

  $menu = '';
  $comma = '?';
  foreach( $_REQUEST as $name => $value )
  {
	 if ( ($name == 'admin') || ($name == 'PHPSESSID')/*  && ($name != 'PHPSESSID') */ )
	   continue;
	 
	  $menu .= "$comma$name=$value";
	  $comma = '&';
	 
  }
	
  return $menu;
}
// Создаем поток для чтения html-файлов
function GetContextHTML() {
		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept-language: ru\r\n text/html;charset=utf-8" .
		              "Cookie: foo=bar\r\n"
		  )
		);
		
		$context = stream_context_create($opts);
  return $context;
}
  header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
  header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
  header('Content-Type: text/html; charset=utf-8');
  header('Access-Control-Allow-Origin: *');
  
  define( "WIDTH_FIRST_COL", "width:30px;" );
  
  date_default_timezone_set('Europe/Moscow');
  setlocale(LC_ALL, 'ru_RU.UTF-8');
  mb_internal_encoding("UTF-8");

?>