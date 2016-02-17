<?php
function CountChildren( $key_category ) {

/*
  	$strwhere = "select count(*) from tovary where key_parent  = $key_category";     
  	$recordSet = runSQL( $strwhere );
  	$count = $recordSet->fields[0];
*/

  	$strwhere = "select count(*) from category where key_parent  = $key_category";     
  	$recordSet = runSQL( $strwhere );
  	$count += $recordSet->fields[0];

	return $count;
}

function GetDelFormtext( $key_category, $name ) {
global $adminTrue;

  if ( $adminTrue ) 
  {
	  	$count = CountChildren( $key_category );
	  	if ($count == 0)
			return "<button type='submit' name='$name' value='Удалить?' style='float:right;' title='Удалить $name?' onclick='return DelCategory(this.name, $key_category);' class='btn-delete'<span class='glyphicon glyphicon-delete'>></button>";
		else
			return "($count)";
  }
  else
   return '';
}

function GetCategoryByParent( $key_parent, $parent_name='', $title_parent='', $null_category=FALSE ) {
global $adminTrue, $show_tovar, $show_category;

  $text = $clickTovarEdit = $for_user = '';
  $symbol_form = "<span class='glyphicon glyphicon-collapse-down'>";
  
  if ( $adminTrue ) 
  {
	  $parent_name = ($key_parent > 0 ? " в $parent_name": 'категорию' );
				
	  $clickTovarEdit = ' onclick="';
	  $nameForm = "fCategory$key_parent";
	  
	  $addForm = "<li onclick='$(this).next().toggle(); ExpandParents(this);'>Добавить новую $symbol_form </span> </li>".
	             WrapForm( $nameForm, "add_record_in_table.php", 'return SaveObject( this, "key_category" );', GetHiddenInput('key_parent', $key_parent).GetHiddenInput("table" , "category").GetHiddenInput("key_name" , "key_category").GetTextInput("name", 'Название', '', "width:90%", 'search', $nameForm, null, 'required').GetTextArea("sql_text", "Код sql для отбора данных (после from)", '', "width:90%", $nameForm, null, 'required')."<input hidden type=submit name=Add value='Добавить $parent_name'/>", 
	             'width:230px;' );

  }
  else  if ( isset($_REQUEST['user']) && ($key_parent == 0) )
      $for_user = " AND (locate( lower( '{$_REQUEST['user']}' ), lower(users) ) > 0)";

    
global $ADODB_FETCH_MODE;	
	    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	    
      $strwhere = "select * from category where key_parent = $key_parent and is_view = 1 $for_user order by name" ;  
//        echo $strwhere;
	  $recordSet = runSQL( $strwhere );
	   
    
	while (!$recordSet->EOF) {
		if ( ($key_parent == 0) && ($text) )
			$text .= "<img src='images/vensel.png' />";	
			
 	   $name_category = trim( $recordSet->fields['name'] );
 	   $key_category  = $recordSet->fields['key_category']; 
 	   $table = $recordSet->fields['sql_text'];
 	   $title = str_replace('...', $title_parent.' ', $name_category);
	   $memo  = htmlspecialchars( $recordSet->fields['memo'] );

 	   $href_tovary = "show_tovar.php?key_parent=$key_category$adminTrue";
 	   $href_category = "show_tovar.php?key_parent=$key_category&category&catalog$adminTrue";
	   $submenu = GetCategoryByParent( $key_category, $name_category, $title, ($table == '') );
	   $onsubmit= "return ShowFromMacros( this );";
	   $onchange= "return ShowFromMacros( this.form );";
	   
	   // Показываем ссылку на таблицу(набор данных)
	   if ( ($submenu == '') && ($table) )
	   {
		   if ( preg_match_all( '/#\$(\w+)(%%)*\$#/', $table, $macros, PREG_SET_ORDER ) ) // форма для параметров запроса
	       	$text .= "<li onclick='$(this).next().toggle(); return ExpandParents(this);' >$name_category $symbol_form</li>".WrapForm( $nameForm= "f$key_category", $href_tovary, $onsubmit, GetFieldFromMacros( ( strstr($name_category, 'день') ?  'date' : (strstr($name_category, 'месяц')  ? 'month' : 'week') ), $macros, '⏎', $onchange ), 'width:230px', null, $title ).'';
		   else
	       	$text .= GetLiOnclick( $key_category, $name_category ).GetDelFormtext( $key_category, $name_category );
	   }	   
	   // показываем ссылку на категории - для уже ветвей дерева либо пустых кодов SQL
	   else
	       $text .= "<li> <a href='$href_category' target='content' title='$name_category' class='collapsed collapsible'  onclick='ExpandParents(this); ".( $adminTrue ? $show_tovar : "return false;" )."'>$name_category</a><ul>$submenu</ul></li>";
		
      $recordSet->MoveNext();
	
	}	 

  return ($text ? $text : '').( $adminTrue && $null_category ? GetDelFormtext( $key_parent, $parent_name ).$addForm : '' ); 
}
function GetLiOnclick( $key_parent, $name_category, $params='' ) {
global $show_tovar,$adminTrue;
	return "<li target='content' onclick='ShowOkno( this.dataset.href, this.title, this ); ExpandParents(this);' title='$name_category' data-href='show_tovar.php?key_parent=$key_parent$adminTrue$params' > $name_category </li>";
	
}
function GetLiAref( $table, $name_category ) {
global $show_tovar,$adminTrue;
	return "<li target='content' onclick='ShowOkno( this.dataset.href, this.title, this ); ExpandParents(this);' data-href='show_tovar.php?table=$table$adminTrue' title='$name_category' >$name_category </li>";
} 

  include_once("params.php");
  
  if ( !isset($_SESSION['customer']) )
     return;
     
  include_once('config_db.php'); 

   $key_parent = ( isset($_REQUEST['key_parent']) ? $_REQUEST['key_parent'] : 0 );
   $show_tovar = "ShowOkno( this.href, this.title, this ); return false;";
   $show_category = "ShowOkno( this.href, this.title ); return false;";

// 	$conn->debug = true; 
  $adminTrue = $admin_text = '';
  if ( $_SESSION['is_admin'] )
  {
	  $adminTrue =  '&admin';
	  $admin_text = GetLiAref( 'category&order=key_parent', 'Редактировать категории').GetLiAref( 'field_names&order=field_name', 'Устроение таблиц' ).GetLiAref( 'users', 'Пользователи' )."<li onclick='ExpandParents(this);'> Импорт".WrapForm( 'fImport', 'load_file.php', 'return SaveObject(this, 0)', GetTextInput( 'table', "имя таблицы", '', "width:90%", 'search', 'fImport', 'return InputTableIsModified( event, this);'  )."<br><input type='file' name='file' onchange='handleFileImport(event);'/> <input type='submit' hidden value='Импорт' />" )." </li>";
  }

      
  try { 
	
	$text = GetCategoryByParent( $key_parent );
	
?>	<ul id="my-menu" class="sample-menu">
		<?=$admin_text.$text?>
	</ul>
	<a href='login.php?logout' > Выйти (<?=$_REQUEST['user']?>)</a>
<?php		
	 
} 
   catch(Exception $e)	   
   {
        $_SESSION['errors'] = $conn->ErrorMsg();
        $_SESSION['error_class'] = $e;
        $_SESSION['error_time'] = date('d.m.y H:i:s');
      echo "Ошибка. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.";
	}

?>

