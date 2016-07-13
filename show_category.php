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
			return /* "<a href='#' class='del_item' name='$name' value='Удалить?' title='Удалить $name?' onclick='return DelCategory(this.name, $key_category);' > */
			"<span class='glyphicon glyphicon-remove pop_menu' onclick='return DelCategory(this.name, $key_category);'></span>";
		else
			return "($count)";
  }
  else
   return '';
}

// добавляем кнопку перехода на форму добавления элемента
function AddFormNewElement( $key_parent, $parent_name ) {
 
  return "<span class='glyphicon glyphicon-plus pop_menu' target='content' onmousedown='return ShowOknoAndExpandParents(this);' data-href='show_form.php?key_parent=$key_parent&parent_name=$parent_name' title='Добавить элемент'></span>";
	
}
function GetCategoryByParent( $key_parent, $parent_name='', $title_parent='', $null_category=FALSE ) {
global $adminTrue, $show_tovar, $show_category;

  $addForm = $text = $clickTovarEdit = $for_user = '';
  $symbol_form = "<span class='glyphicon glyphicon-collapse-down'></span>";
  $symbol_form1 = "<span class='glyphicon glyphicon-collapse-up'></span>";
  $symbol_add_li = "<span class='glyphicon glyphicon-collapse-down collapsible' style='float:rigth;' ></span>";
  
  if ( $adminTrue ) 
  {
	  $parent_name = ($key_parent > 0 ? " в $parent_name": 'подуровень' );
				
	  $clickTovarEdit = ' onclick="';
	  $nameForm = "fCategory$key_parent";
	  
	  // создание нового элемента, содержащего данные ( и новой таблицы в БД при необходимости )
	  $addForm = //"<li onclick='$(this).children(1).toggle(); return ExpandParents(this);'>Добавить $parent_name $symbol_form 
	  "<span class='glyphicon glyphicon-plus pop_menu' target='content' onclick='return ShowOknoAndExpandParents(this);' data-href='show_form.php?key_parent=$key_parent&parent_name=$parent_name' title='Добавить элемент'></span>";

	  // по клику будет вызываться форма для создания новой подкатегории
	  $addForm .= "<span class='glyphicon glyphicon-collapse-down pop_menu' onclick='$(this).next().toggle(); return false;' title='Добавить подуровень'> ".
	             WrapForm( $nameForm, "add_record_in_table.php", 'return SaveObject( this, "key_category" );', GetHiddenInput('key_parent', $key_parent).GetHiddenInput("table" , "category").GetHiddenInput("key_name" , "key_category").GetTextInput("name", 'Название', '', "width:90%", 'search', $nameForm, null, 'required')/* GetTextArea("sql_text", "Код sql для отбора данных (после from)", '', "width:90%", $nameForm, null, 'required') */."<input hidden type=submit name=Add value='Добавить $parent_name'/>", 
	             'width:230px;display:none;float:left' ).'</span>'; //</li>';
  }
  else  if ( isset($_REQUEST['user']) && ($key_parent == 0) )
      $for_user = " AND (locate( lower( '{$_REQUEST['user']}' ), lower(users) ) > 0)";

    
global $ADODB_FETCH_MODE;	
	    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	    
      $strwhere = "select * from category where key_parent = $key_parent and is_view = 1 $for_user order by leaf, name" ;  
//        echo $strwhere;
	  $recordSet = runSQL( $strwhere );
	   
      $count = 1;
	while (!$recordSet->EOF) { $count++;
		
/*
	  if ( ($key_parent == 0) && ($text) )
			$text .= "<img src='images/vensel.png' />";	
*/
/*
      else if ($count++ == 8)
      	  $text .= "<li style='float:left'>Показать больше</li> $symbol_add_li <ul>";
*/
				
 	   $name_category = trim( $recordSet->fields['name'] );
 	   $key_category  = $recordSet->fields['key_category']; 
 	   $table = $recordSet->fields['sql_text'];
 	   $title = ( $title_parent ? $title_parent.' > '.$name_category : $name_category);
	   $memo  = htmlspecialchars( $recordSet->fields['memo'] );

 	   $href_tovary = "show_tovar.php?key_parent=$key_category$adminTrue";
 	   $href_category = "show_tovar.php?key_parent=$key_category&category&catalog$adminTrue&title=$title";
	   $submenu = GetCategoryByParent( $key_category, $name_category, $title, ($table == '') );
	   $onsubmit= "return ShowFromMacros( this );";
	   $onchange= "return ShowFromMacros( this.form );";
	   
	   // Показываем ссылку на таблицу(набор данных)
	   if ( ($submenu == '') && ($table) )
	   {
		   if ( preg_match_all( '/#\$(\w+)(%%)*\$#/', $table, $macros, PREG_SET_ORDER ) ) // форма для параметров запроса
	       	$text .= "<li onclick='$(this).next().toggle(); return ExpandParents(this);' title='$title' >$name_category $symbol_form</li>".WrapForm( $nameForm= "f$key_category", $href_tovary, $onsubmit, GetFieldFromMacros( ( strstr($name_category, 'день') ?  'date' : (strstr($name_category, 'месяц')  ? 'month' : 'week') ), $macros, '⏎', $onchange ), 'width:230px', null, $title ).'';
		   else
	       	$text .= GetLiOnclick( $key_category, $name_category, '', $title );
	   }	   
	   // показываем ссылку на категории - для уже ветвей дерева либо пустых кодов SQL
	   else
	       $text .= "<li class='collapsed collapsible'> <a href='$href_category'  target='content' title='$title'  onclick=' ExpandUL( $(this) ); ".( $adminTrue ? $show_tovar : '' )."'><span>$name_category</span></a>".( $adminTrue ? AddFormNewElement( $key_category, $name_category ) : '' ).($submenu == '' ? GetDelFormtext( $key_category, $name_category ) : "<div class='panel wrapper' ><ul>$submenu</ul></div>" )."</li>";
		
      $recordSet->MoveNext();
	}	 

  return ($text ? $text/* .($count > 7 ? '</ul>': '' ) */ : ''); 
}
function GetLiOnclick( $key_parent, $name_category, $params='', $title='' ) {
global $show_tovar,$adminTrue;

    if ( $title == '')
    	$title = $name_category;
    	
	return "<li> <a  target='content' onclick='ExpandParents(this);$show_tovar' title='$title' href='show_tovar.php?key_parent=$key_parent$adminTrue$params&title=$title' ><span>$name_category</span></a>".GetDelFormtext( $key_parent, $name_category )."</li>";
	
}
function GetLiAref( $table, $name_category ) {
global $show_tovar,$adminTrue;
	return "<li target='content' onclick='ShowOkno( this.dataset.href, this.title, this ); return ExpandParents(this);' data-href='show_tovar.php?table=$table$adminTrue' title='$name_category' >$name_category</li>";
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
	  $admin_text = "<li class='collapsed collapsible'> <a onclick='return ExpandUL( $(this) );'>Служебное</a> <div><ul>".GetLiAref( 'category&order=key_parent', 'Редактировать категории').GetLiAref( 'field_names&order=field_name', 'Устроение таблиц' ).GetLiAref( 'search', 'Ассоциации поиска' ).GetLiAref( 'users', 'Пользователи' )."<li onclick='return ExpandParents(this);'> Импорт</li>"./* WrapForm( 'fImport', 'load_file.php', 'return SaveObject(this, 0)', GetTextInput( 'table', "имя таблицы", '', "width:90%", 'search', 'fImport', 'return InputTableIsModified( event, this);'  )."<br><input type='file' name='file' onchange='handleFileImport(event);'/> <input type='submit' hidden value='Импорт' />", 'width:230px' ). */"<li><a href='login.php?logout' > Выйти ({$_REQUEST['user']})</a></li></ul></div></li>";
  }

      
  try { 
	
	$text = GetCategoryByParent( $key_parent );
	
	if ( isset($_REQUEST['list']) )
	{
	?>	<ul id="list-menu" class="list-menu">
			<?=$admin_text.$text?>
		</ul>
		
	<?php				
	}
	else if ( isset($_REQUEST['cicle']) )
	{
	?>	<ul id="cicle-menu" class="cicle-menu">
			<?=$admin_text.$text?>
		</ul>
		
	<?php				
	}
	else
	{		
	?>	<ul id="tree-menu" class="tree-menu">
			<?=$admin_text.$text?>
		</ul>
		
	<?php		
	}
	 
} 
   catch(Exception $e)	   
   {
        $_SESSION['errors'] = $conn->ErrorMsg();
        $_SESSION['error_class'] = $e;
        $_SESSION['error_time'] = date('d.m.y H:i:s');
      echo "Ошибка. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>.";
	}

?>

