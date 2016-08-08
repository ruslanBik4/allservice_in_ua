<?php
// отрисовывает одну ячейку таблицы, с текстом данных ( символ "-" в случае отсутствия данных ) и стилем, переданными как параметры    
function WrapTD( $name, $text='-', $style, $align='right' ) {
	if (!$text)
	  $text = '-';
	  
	return "<div name='$name' ".GetAttrFromValue( 'style', $style ).GetAttrFromValue( 'align', $align )." class='td'> $text </div>";
}
// отрисовывает ячейку шапки таблицы
function WrapTH( $name, $text='-', $style, $align='center' ) {
	return "<div title='$name' ".GetAttrFromValue( 'style', $style ).GetAttrFromValue( 'align', $align )." class='th'> $text </div>";
}
// отрисовывает шапку таблицы
function GetTableHeads($order) {
global $recordSet, $fieldSet, $arr_field, $table, $onsubmit, $action, $th2, $width_all, $admin_true, $current_params;

  $th1 = $th2 = $in_group ='';
  $width_all  = 30;  // padding bootramp 15px
	$output_text = "<output name='State'>⎋</output>";
  
  for( $i = 0; $i < $recordSet->FieldCount(); $i++ ) // проходит по полям
   {
	   $field = $recordSet->FetchField($i); 
// 	   print_r($recordSet->FetchField($i));  //проверить потом multiple_key;
	   
	   $arr_field[$i]["name"] = $name  = $field->name;
	   if ( ($name == 'primary')  || ( $field->primary_key ) )
	   {
	   		$th1 .= WrapTH( $name, "<a href='show_tovar.php$current_params".( $admin_true ? '' : '&admin' )."' > <img src='http://solution.artel.ws/images/ddn.png' /> </a>", WIDTH_FIRST_COL );	 		
	   		
	   		if ( $admin_true ) // создаем форму для добавления элемента
	   		{
		   		$th2 .= WrapTH( $name, "$output_text<input id='btnId' type='image' src='http://solution.artel.ws/images/add.png' form='fFilter' title='Добавить новую запись' style='display:none;' />", WIDTH_FIRST_COL );
		   		
	   		}
	   		else
		   		$th2 .= WrapTH( $name, "$output_text<button id='btnId' value='♋︎' form='fFilter' title='Очистить фильтры' hidden onclick='return PLayFilter( this );' class='btn-info'> <span class='glyphicon glyphicon-arrow-up'></span> </button>", WIDTH_FIRST_COL );
		   		
		   $width_all += 30;
		   continue;
	   }
	  
	   if ( ($name == 'id')  || ($name == 'photo') )
	   	continue;
	   		
	   $arr_field[$i]["field_name"] = $field_name = GetFieldProp($name);
	   $arr_field[$i]["is_view"] = ( !$fieldSet->EOF ? $fieldSet->fields['is_view'] : '1' );
	   
	   if ( !($admin_true || $arr_field[$i]["is_view"]) )
	      continue;
	   
	   // сохраняем настройки слобцов для последующего показа в глобальном массиве  $arr_field
	   $arr_field[$i]["type"] = $type = ( $fieldSet->fields['type_input'] ? $fieldSet->fields['type_input'] : StyleInput( $field->type ) );
	   $arr_field[$i]["length"] = $length = ( $fieldSet->fields['field_len'] ? $fieldSet->fields['field_len']  : ( ($length = $field->max_length) <151 ? $length : 150) );
	   $arr_field[$i]["in_table"] = $in_table = ( $fieldSet->fields['in_table'] ? $fieldSet->fields['in_table'] : '');
	   $arr_field[$i]["formula"] =  $fieldSet->fields['formula']; 
	   $arr_field[$i]["url"] =  $fieldSet->fields['url']; 
	   
	   $style = $arr_field[$i]["style"] =  $fieldSet->fields['style']; 
	if (substr($name, 0, 3 ) == 'id_' && (substr($name, 2) != substr($table, 2)) && (substr($name, 3) != $table) )
	{
		$in_table = substr($name, 3);
		$type     = 'select';
	}


	   $align = ( ($type=='checkbox') || ($type=='radio') ? 'center' : 'left' );
	   
	   $temp = $col_width = ( ($align != 'center') && ($length > 5) ? $length : GetLengthFromType($type) ); 
	   
	   if ( $align == 'center' )
	     $col_width = ( ($col_width < 55) ? 55 : $col_width );
	   else if ( $type=='number' )
	     $col_width = ($col_width < 55 ? 55 : $col_width );
	   else if ( $col_width < 75 )
	     $col_width = 75;
	   else if ( $col_width > 550 )
	     $col_width = 550;
	   
	   $arr_field[$i]["col_width"] = $col_width;
	   $style = "width:$col_width;";
	   $width_all += $col_width;
	   $order_type = "&order=$name";
	   $field_title= 'Нажмите, чтобы сортировать по возрастанию';
	   $sort_arrow = '';
	   
	   // готовим сортировку для нажатия по имени поля
	   if ($order == "$name desc" ) 
	   {
		   $sort_arrow = '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';
		   $field_title= 'Отсортировано по убыванию, нажмите, чтобы сортировать по возрастанию';
	   }
	   else if ($order == $name)
	   {
		   $order_type .= '%20desc';
		   $sort_arrow = '<span class="glyphicon glyphicon-sort-by-attributes"></span>';
		   $field_title= 'Отсортировано по возрастанию, нажмите, чтобы сортировать по убыванию';
	   }
	   
	   if ( strstr( $current_params, 'order') )
	   {
	      $new_params = preg_replace( '/&order=[^&]+/', $order_type, $current_params );		   
	   }
	   else
	      $new_params = $current_params.$order_type;
	   
	   if ( $in_group != $fieldSet->fields['name_group'] )
	   {
		   if ( $in_group ) // закрываем старую группу
		   	  $th1 .= '</div>';
		   	  
		   if ( ($in_group = $fieldSet->fields['name_group']) ) // начинаем новую группу
			    $th1 .= "<div class='td'> <p class='name_group' > $in_group</p> ";
		    
	   }
	   
	   
       // строка заголовков столбцов
       $th1 .= WrapTH( $name, "<a href='show_tovar.php$new_params' title='$field_title' >$field_name$sort_arrow</a>", $style );
       // строка фильтров либо добавления новой записи
       $th2.= WrapTH( $name, GetInputFromType( $type, $name, ( $admin_true && isset( $_REQUEST[$name] ) ? $_REQUEST[$name] : '' ), $style, 'fFilter', $in_table, $field_name, ( $admin_true ? 'return FormIsModified( event, this.form);' : 'return FilterIsModified( event, this );' ) ), '', $align );
    }
	
	$th2 = WrapSimpleForm( 'fFilter', ( $admin_true ? "$action.php" : ''), GetHiddenInput('table', $table).GetHiddenInput('key_name', $name).$th2,  "margin:0", '', "class='thead'"  );
	
  return $th1;
}
// показ данных в одном поле
function GetInputFromType($type, $name, $value, $style, $formName, $in_table, $field_name, $oninput='return FormIsModified(event, this.form);', $required ) {
global $arr_field, $conn, $admin_true;
global $ADODB_FETCH_MODE;	
	    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	    
	if ( ( $in_table && ($type == 'select') ) || ($name == 'type_input')  )
	{
		if ($name == 'type_input')  // Для устроения таблиц
		{
		   $type_fields = array( 'search' => 'По умолчанию',
		   						 'file'   => 'Картинку',
		   						 'checkbox' => 'checkbox',
		   						 'number'   => 'number',
		   						 'select'   => 'Выборка',
		   						 'radio'	=> 'radio',
		   						 'textarea' => 'textarea',
		   						 'date'     => 'Дата',
		   );
		   
		   $options = "<option selected disabled>Выберите тип поля</option>";
		   foreach( $type_fields as $value_type => $text)
		    $options .= "<option ".($value_type== $value ? 'selected' : '')." value='$value_type'>$text</option>";
		}
		else
		{
			$parentSet = $conn->CacheExecute( 300, "select * from $in_table order by 2");
			$options = "<option value='' >".( $admin_true ? 'Нет' : 'Все')."</option>";
			foreach( $parentSet as $key => $row ) {
				$selected = ( $value == ($row[0]) ? 'selected' : '');
			    $options .= "<option $selected value='{$row[0]}'> {$row[1]}</option>";
			}	 // while
		}
		
		return "<select  class='form-control' style='$style' name='$name' form='$formName' onchange='$oninput' > $options </select>";
	}
	else if ( ($type == 'textarea') && ($formName != 'fFilter') )
      return GetTextArea($name, '', $value, $style, $formName, $oninput, $required);
	else if ( ($type == 'checkbox') && ($formName == 'fFilter') )
	{
// 		стили убираем, чтобы не порить ширину
	  return "<select class='form-control' name='$name' form='$formName' onchange='$oninput' ><option value='' >Все</option> <option value='✔︎' >✔︎</option><option value='✖︎' >✖︎</option></select>";
	}
	else
	  return GetTextInput($name, '', $value, $style, $type, $formName, $oninput, $required );
}
// jnhbcjdrf lfyys[ nf,kbws
function GetTableRecords($order) {
global $recordSet, $fieldSet, $table, $onsubmit, $arr_field, $admin_true;

	$order_value = '';	       
	$output_text = "<output name='State'>✎</output>";
	
	foreach($recordSet as $k => $row)
	{
		$photo = ( $row['photo'] ? $row['photo'] : $row['primary'] );
		$photo_img = ( $photo ? "<img src='$photo' class='photo_max' /> <span class='glyphicon glyphicon-eye-open'></span>" : "<span class='glyphicon glyphicon-eye-close'></span>" );
		$style_div = ( $order && ($order_value != $row[$order])  // отчертим предыдущие значения
			 ? 'border-top: 1px inset; padding-top: 1px;' : '' );
	    $text = '';
	    
		for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
		{
		
		    $field = $recordSet->FetchField($i); 
			$name  = $arr_field[$i]["name"];
			$value = $row[$i];
			if ( ($name == 'primary')  || ( $field->primary_key ) ) // потом разобраться, чтобы это было одно поле
			{
				$id = $value;
				$formName = "f$table$id";
				
				if ( $admin_true )
					$text .= WrapTD( $name, "$output_text<input type='image' src='http://solution.artel.ws/images/valid.png' value='Сохранить' form='$formName' style='display:none;' title='Сохранить изменения'/> ", WIDTH_FIRST_COL );
				else
					$text .= WrapTD( $name, "<a target='_blank' href='show_record.php?table=$table&id=$value&key_id=$name' > $photo_img</a> ", WIDTH_FIRST_COL, 'left' );
				
				continue;
			}
			
		   if ( ($name == 'id') ||  !($admin_true || $arr_field[$i]["is_view"]) || ($name == 'photo') )
				continue;
			
					
			$type  = $arr_field[$i]["type"];
			$length = $arr_field[$i]["length"];
			$col_width = $arr_field[$i]["col_width"];
			$field_name = $arr_field[$i]["field_name"];
			$in_table = $arr_field[$i]["in_table"];
			$style = $arr_field[$i]["style"];
			$url=$arr_field[$i]["url"];
			
			$align = ( (($type=='checkbox') || ($type=='radio')) ? 'center' : ( ($type=='number') ? 'center' : 'left' ) );
			$style .= "max-width:550px;min-width:".( $align == 'center' ? '10px;' : ( ($type=='number') ? '50px' : '70px').";width:$col_width;" ); //."margin: auto;";
			
			if ( $admin_true && ($name != 'date_sys') )
				$text .= WrapTD( $name, GetInputFromType( $type, $name, $value, $style, $formName, $in_table, $field_name), $style.";margin: auto;height: auto;width:$col_width;", $align );
			else 
			{
				if ( ($order == $name) )
				{
					$order_value = $value;	
				}
				if (substr($name, 0, 3 ) == 'id_' && (substr($name, 2) != substr($table, 2)) )
				{
					$value = GetValueFromID( $value, $name );
					$text .= WrapTD( $name, ( $url ? GetURLField( $url, $value ) : $value ), $style, $align ); 
				}				 
				elseif ($name == 'photo')
				 $text .= WrapTD( $name, "<a href='/photos/$value' class='fancybox-button' rel='collection'> <img src='/photos/$value' style='$style'/> <img src='/photos/$value' class='photo_max' /> </a>", $style, 'center' ); 
				else
				{
					if ( !($value) && $arr_field[$i]["formula"] )
					    $value = GetValueFormula( $arr_field[$i]["formula"] );
					    
				 $text .= WrapTD( $name, ( (($type=='checkbox') || ($type=='radio')) ? ( $value ? "✔︎" : "✖︎" )/* GetTextInput( $name, '', $value, $style, $type, '', '' ) */ : ( $value ? ( $url ? GetURLField( $url, $value ) : $value ) : '-')), $style.";margin: auto;height: auto;", $align ); 				
				}
			}
		} //for по полям
		 
		if ( $admin_true )
			echo WrapSimpleForm( $formName, "add_record_in_table.php", GetHiddenInput('table', $table).GetHiddenInput('key_name', $name).GetHiddenInput( $name, $value ).$text, $style_div.'margin:0', 'Изменить запись', 'class=tr' );
		else
			echo "<div class='tr' style='$style_div' >$text</div>";
			
	}	 // while по записям 
}
// делаем ссылку для раскрытия поля
function GetURLField( $url, $value ) {
global $recordSet;
	
	$url = preg_replace( '/#\$(\w+)\$#/e', '"\"{$recordSet->fields[$1]}\""', $url);
	return "<a href='show_tovar.php?table=$url' title='Посмотреть наличие' > $value </a>";
}
// получить значение поле Айди из вторичного ключа, елслинадо

function GetRealValue( $value, $name ) {
  global $table;
  
  if ( substr($name, 0, 3 ) == 'id_' )
		return GetValueFromID( $value, $name ); 
  else
      return $value;	
}
// отрисовывает форму, если макрос зашит в строку запроса
function GetMacrosForm() {
global $adminTrue, $macros;	
    if ( isset($_REQUEST['key_parent']) )
    {
	   $recordSet = runSQL( "select * from category where key_category=".$_REQUEST['key_parent'] ); 
	   
 	   $name_category = $recordSet->fields['name'];
 	   $key_category  = $recordSet->fields['key_category']; 
 	   $table = $recordSet->fields['sql_text'];
 	   $title = $recordSet->fields['title'];
	   $memo  = htmlspecialchars( $recordSet->fields['memo'] );

 	   $href_tovary = "show_tovar.php?table=$table&title=$name_category";
	   if ( preg_match_all( '/#\$(\w+)\$#/', $table, $macros, PREG_SET_ORDER ) )
	   {
		   $onsubmit= 'return NewFromMacros( this,"'. $name_category.'" );';
		    echo WrapForm( $nameForm= "f$key_category", $href_tovary, $onsubmit, GetFieldFromMacros( ( strstr($name_category, 'день') ?  'date' : (strstr($name_category, 'месяц')  ? 'month' : 'week') ), $macros, 'Добавить диаграмму', '' ), "'  class='form-inline'  role='form'" );
		    return 'Draw();';
	   }
    }
    
    return 'Draw();';
}
//
function GetTitleParam($param) {

	 if ( !$param || !($value=GetParamOrDefault( $param, '' )) )
	    return '';

	 if ( strstr( $param, 'check' ) )
	    return ( $value ? GetFieldProp( substr($param, 6) ) : '' );
	 else if ( strstr( $param, 'date' ) )
	 	return " - '".GetFullStringDate($value)."'. ";	 
	 else
	 	return GetFieldProp( $param ); // временно .'='.$value;

}
// показ диаграммы комбо
function GetComboChart( $chart ) {
global $recordSet, $table, $fields, $sql_text;
	 	 $groups = $_REQUEST['group'];

 	$recordSet = runSQL( $_SESSION['sql_text'] = $sql_text="select * from $chart" );
 	$add_fields = $comma = '';
    $field = $recordSet->FetchField(0);
 	$key_name = $field->name; // запоминаем имя ключевого поля (первого)

	$arr_chart = split(',', $groups);
?>
 <script> function GetData() { 
	 var data = new google.visualization.DataTable();
 	// добавляем столбцы
	data.addColumn('string', 'Наименование');
	data.addColumn('number', 'Всего');
<?php
		
	foreach($recordSet as $k => $row) {
    
	  echo "data.addColumn('number', '{$row[1]}');\n";
	  $add_fields .= ", (select count(*) from $table AND ($key_name={$row[0]}) AND (id_size=`chart_size`) AND Tsvet=`Цвет`) AS `{$row[1]}`";
	}	 // while
/*
for( $i = 1; $i < $recordSet->FieldCount(); $i++ )
	  {
		  $field = $recordSet->FetchField($i);
		  echo "data.addColumn('number', '{$field->name}');\n";
	  }
*/
		
 	$recordSet = runSQL( $_SESSION['sql_text'] = $sql_text="select $fields $add_fields from $table group by $groups" );
		
	$summa = 0;
	
	while (!$recordSet->EOF) {
		
	    $field = $recordSet->FetchField(0);
	    $string = "data.addRow( [ '";
// 	    .GetRealValue($recordSet->fields[0], 'id_size'/* GetFieldProp($field->name) */ ).' '.GetRealValue($recordSet->fields[1])."'"; // заголовок
		foreach( $arr_chart as $id => $value )
		{
		 $string .= $comma.GetRealValue( $recordSet->fields[$id], $value );
		 $comma   = "_";
		}
	     
	    echo $string."'";
	    
		for( $i = 2; $i < $recordSet->FieldCount(); $i++ )
		     echo ", {$recordSet->fields[$i]}"; 
		  
	      $summa += $recordSet->fields[2];
		  echo "] );\n";
	    $recordSet->MoveNext();
	}	 // while
?> return data; 
  }    
  function GetOptions() {
	    var options = {
		    title : "Продажи по группам  <?=$title?>. Всего = <?=$summa?>",
		    vAxis: {title: "Продажи"},
		    hAxis: {title: "Размер и цвет"},
		    selectionMode: 'multiple',
// 		    tooltip: {trigger: 'selection'},
		    aggregationTarget: 'category',
// 		    orientation: 'vertical',
		    lineWidth: 5,
		    isStacked: false,
		    seriesType: "bars",
		    series: {5: {type: "line"}}
		  };
    return options;
  }
  function GetTitle() {
	  return document.title + " <?=$title?>. Итого = <?=$summa?>";
  }
  Draw( drawComboChart );
  </script>
	  <div class="combobar_div"  onclick="if ( confirm( 'Вы ТОЧНО хотите удалить диаграмму ?' ) ) $(this).hide();" ></div>
  
<?php
}
// показ диаграмм
function GetChart( $chart ) {
global $recordSet, $table, $macrosU, $sql_text;
global $ADODB_FETCH_MODE;
   $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;

    $func_load = GetMacrosForm();
 	$recordSet = runSQL( $sql_text="select $chart, count(*) as value from $table group by $chart" );
	$arr_chart = split(',', $chart);
	$summa = 0;
	$title = '';
	
	for( $i=0; $i <= sizeof($macrosU); $i++ )
	   	    $title .= GetTitleParam( $macrosU[$i][1] );
	
	$not_include = array( 'PHPSESSID', 'MAX_FILE_SIZE', 'table', 'chart', 'user', 'title' );
	if ( !$title )
	{
		if ( isset($_REQUEST['key_parent']) )
			$title = $_REQUEST['title'] ;
		else
		   foreach($_REQUEST as $name => $value )
		   	 if ( in_array( $name, $not_include ) )
		   	    continue;
		   	 else
		   	    $title .= GetTitleParam( $name );
	 
	}
?>
  <div class="chart_parent" onclick="return RemoveChart(event);" > 
	  <div class="chart_div"  > 
		  <img src='http://solution.artel.ws/images/ddn.png' onload=""/> 
	  </div> 
	  <div class="table_div"></div>
	  <div class="colm_div"></div>
	  <div class="bar_div" ></div>
  </div>
 <script> function GetData() { 
	 var data = new google.visualization.DataTable();
	//основные столбцы
	data.addColumn('string', 'Наименование');
	data.addColumn('number', 'Количество');
// 	data.addColumn('role', 'style');

<?php
	foreach($recordSet as $k => $row) {
		$comma     = $string = '';
		foreach( $arr_chart as $id => $value )
		{
		 $string .= $comma.GetRealValue( $row[$value], $value );
		 $comma   = "_";
		}
		$summa += ($count = $row['value']);
		echo "data.addRow( [ '$string', $count ] );\n"; 
	}	 // foreach($recordSet
?> return data; 
  }    
  function GetTitle() {
	  return document.title + " <?=$title?>";
  }
  function GetCountData() {
	  return <?=$summa?>;
  }
  <?=$func_load?>;
  </script>
  
<?php
}
 try
 {
  $order = $fieldSet = $recordSet = $admin_true = $summary = '';
  require_once("params.php"); 
  require_once('config_db.php'); 
  $arr_field = array();
  $macrosU   = array();
  
  if (isAdmin())
      $admin_true = '&admin';
      
  $action= ( $admin_true ? 'add_record_in_table' : 'show_tovar' );
  $submit= $admin_true ? 'Добавить ' : 'Применить фильтры';
  $onsubmit = $admin_true ? 'return SaveObject( this );' : 'return PLayFilter( this );';

  $current_params = GetParamsToString();  

  if ( isset($_REQUEST['table']) )
	  $table = $_REQUEST['table'];
  else if ( isset($_REQUEST['key_parent']) )// если нет запроса, возможно,  это вызов категорий для просмотра
  {
	  $key_parent = $_REQUEST['key_parent'];
	  if ( isset($_REQUEST['category']) )   
	     $table = "category where key_parent=$key_parent";
	  else
	  {
	    $table =  GetParamFromCategory($key_parent);
	  }
   }
   else
     return;
     
 $is_order = ( $_REQUEST['order'] ? " order by ".($order=$_REQUEST['order']) : '');
 
 
  if ( $where = GetParamOrDefault( 'where', '' ) )
  	$table .= " where $where";

  $fields = GetParamOrDefault( 'fields', '*' ); 
  
	 if (isset($_REQUEST['chart']))
	 {
	    GetChart( $_REQUEST['chart'] );
		 return;
	 } 
	 
	 if (isset($_REQUEST['combochart']))
	 {
	    GetComboChart( $_REQUEST['combochart'] );
		 return;
	 } 
	
   // вырезаем из запроса паразитические условия типа (1=1) 
   $table = preg_replace( '/ OR\s+(\(1=1\))*\s*(?=\))/', '', $table );
   $_SESSION['sql_text'] = $sql_text = "select $fields from $table $is_order";
   
   global $ADODB_FETCH_MODE;
   $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
  
  $recordSet = runSQL( $sql_text, ($new_nrow=GetParamOrDefault( 'nrows', 50 )), GetParamOrDefault( 'offset', -1 ), false, 2 );
 	    
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
 
/*
  if ($recordSet->EOF )
  {
	  echo 'По запросу ничего не найдено';
	  return;
  }
*/
 $new_offset = min( array( $new_nrow, $recordSet->RecordCount() ) )  + GetParamOrDefault( 'offset', 0 );
	 
	 $recordCount = GetParamOrDefault( 'recordCount', GetRecordCount( $table, $fields, $recordSet->RecordCount() ) );
	 
	 if ( isset($_REQUEST['summary']) )
	 {
		$recordSummary = runSQL( "select {$_REQUEST['summary']} from $table" );   
		for( $i = 0; $i < $recordSummary->FieldCount(); $i++ )
		{
			$field = $recordSummary->FetchField($i); 
			$summary .= $field->name." = ".$recordSummary->fields[$i].". ";
		 }
		$summary =  "<div class='panel-success'>$summary</div>";	
	 }
	 $table = ( ($pos=strpos( $table, ' ' )) ? substr( $table, 0, $pos ) : $table );
	 $header_table = GetTableHeads($order); 
	 if ( $order )
	 {
	 	$order_name = " Порядок по '<b>";
		$arr_order = split(',', $order); 
		foreach( $arr_order as $field_order)
			$order_name .= GetFieldProp( $field_order ).', ';
			
		$order_name .= "</b>'";	
	 }
}
catch(Exception $e)	   
{
    $_SESSION['errors'] = $conn->ErrorMsg();
    $_SESSION['error_class'] = $e;
    $_SESSION['sql_text'] = $sql_text;
    $_SESSION['error_time'] = date('d.m.y H:i:s');
    
  echo "Ошибка - {$_SESSION['errors']}. Подробности смотри <a href='error_log.php' target='_blank'>тут </a>. запрос - $sql_text".print_r($_REQUEST);
}

// шаблон страницы
?>
	 <header class='panel-heading' style='width:<?=$width_all?>px;'>  
		  <div style='margin:0;' class='thead table row-fluid' > <?=$header_table?> </div>
		  <?=$th2?> 
	  </header>
	 <div id="table_body" style='width: <?=$width_all?>px;' class='panel-body scroll-pane '> 
		 <?=GetTableRecords($order)?> 
	 </div>
	 <div id='uploadOutput' onclick='$(this).hide()'></div>
<form name="fImport" id='fImport' class='form-horizontal' role='form' action="add_categoryes.php"  method='post'  target='content' style='display:none;position:absolute; top:50; left: 230; width:550px; height: auto; border: 3px red; background:white;' oninput='FormIsModified( event, this )' onsubmit='return SaveObject( this, "key_category" );' enctype='multipart/form-data' title='' onreset="if ( confirm('Очистить все введенные данные?') ) {  $('#table_body > div').html(''); $('#fields_rows > div:first').next().nextAll('div').remove(); } else return false;" > 
	<output name='State' ></output> <img id='loadingfImport' src='http://artel.ws/solution/loading.gif' style='display:none;'> 
	<input type='hidden' name='key_parent' value='<?=$_REQUEST['key_parent']?>' /> 		
 	<input type='file' name='file' onchange='handleFileImportLevels(event);' value='Взять из файла' accept='text/csv' />
    <progress id='progressfImport' value='0' max='100' hidden > </progress>
	 	<div id="table_title" class="tr thead panel-heading row-fluid"> </div>
  <select id='code_page' name='code_page' > 
	  <option value='cp1251' selected > Кодировка WIn-1251 </option>
	  <option value='UTF-8'> UTF-8 </option> 
  </select>
  <select id='sep_fields' name='sep_fields'>
	  <option value=';' selected > Разделение полей запяточием</option>
	  <option value=','  > Разделение полей запятыми и кавычками</option>
  </select>
  не импортировать первые <input type='number' name='ignore_line' value="1"/> строк
	<div id="table_header" class="tr thead panel-heading row-fluid"> </div>
	<input hidden type='submit' name='Add' value='Добавить в <?=$_REQUEST['key_parent']?>'/>
</form>
	  <div id="divpager"> 
 		<div  class='col-md-2 panel-success'>
	 		<a id='aSaveCSV' target='_blank' href="get_csv.php<?=$current_params?>" alt='referal' title='Сохранить выбранные набор данных как CSV (откроется в Excel)' > <span class="glyphicon glyphicon-download">CSV</span></a>
	 		<a id='aSavePDF' target='_blank' href="get_pdf.php<?=$current_params?>" alt='referal' title='Сохранить выбранные набор данных как PDF' > <span class="glyphicon glyphicon-download">PDF</span></a> 
	 	</div>
 		<div  class='col-md-8 panel-info' > 
		  <?=$summary?>
	 		<?=($admin_true ? "Правка $table. " : '' )?> Показано <span class='badge'><?=$new_offset?></span>			
 		  <progress value="<?=$new_offset?>" max="<?=$recordCount?>"> </progress> из <span class='badge'><?=$recordCount?></span> записей.
	 		<a id='aNextPage' href="show_tovar.php<?=$current_params?><?=$admin_true?>&offset=<?=$new_offset?>&recordCount=<?=$recordCount?>" onclick='return GetMoreRecords( this );' <?=($recordCount > $new_offset ? '' : 'hidden')?> >  Показать еще 50.</a> 

		</div>
 		<div  class='col-md-2 panel-success'>
	 		<a id='aImportCSV' target='_blank' href="" alt='referal' title='Добавить строки из CSV' onclick='$("#fImport").toggle(); return false;'> <span class="glyphicon glyphicon-upload">CSV</span></a>
<!-- 	 		<a id='aSavePDF' target='_blank' href="get_pdf.php<?=$current_params?>" alt='referal' title='Сохранить выбранные набор данных как PDF' > <span class="glyphicon glyphicon-upload">PDF</span></a>  -->
	 	</div>
	       
	  </div> 
<!-- <div id="theBreadCrumbs" > <?=$_REQUEST['title']?></div> -->
<script>
// 	window.onload =  function() { 
			$('#table_body').css( { 'height' : document.body.clientHeight-$('header').height() - $('footer').height(), 'padding-botton' : '10px' } );
			console.log( $(window).height(), $('header').height(), $('#table_body').height() );
/*
			$('#content > header').remove();
			$('#pane header').prependTo( $('#content') );
*/
// 	};
	$('#table_body').scroll( function() {
			$('#table_body').css( { 'height' : document.body.clientHeight - $('header').height() - $('footer').height() - 20 , 'padding-botton' : '10px' } );
// 			console.log( $(window).height(), $('header').height(), $('#table_body').height() );
	       if ($(this).scrollTop() + this.clientHeight > this.scrollHeight - 50) {
	            GetMoreRecords( document.getElementById('aNextPage') );
	            $('#goTop').show();
	        }
// 	        else
// 	          console.log( $(this).scrollTop(), this.scrollHeight );
		
	});

</script>		 
