<?php
function WrapDIV( $name, $text, $style, $align='div100' ) {
	return "<div name='$name' style='$style' class='td'> <strong>$name</strong> : $text </div>";
}
function GetRecords() {
global $recordSet, $fieldSet, $table, $onsubmit, $arr_field;

		for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
		{
		
			$field = $recordSet->FetchField($i); 
			$name  = $field->name;
			$value = $recordSet->fields[$name];
			if ( $field->primary_key )
			{
				$id = $value;
				$formName = "f$table$id";
				if ( isAdmin() )
					echo WrapDIV( $name, WrapForm( $formName, "add_record_in_table.php", $onsubmit, GetHiddenInput('table', $table).GetHiddenInput('key_name', 'id').GetHiddenInput( $name, $value )."<input type='image' src='http://solution.artel.ws/images/valid.png' value='Сохранить' form='$formName' hidden title='Сохранить изменения'/> ",  "max-width:100%;" ) );
				else
					echo WrapDIV( $name, "<a target='_blank' href='show_record.php?table=$table&id=$value&key_id=$name' onmousemove='this.click();' > <img src='http://solution.artel.ws/images/eye.png' /> </a>", 'max-width:100%;', 'left' );
				
				continue;
			}
			
					
			$field_name = GetFieldProp($name);
		   if ( ($name == 'id') ||  !(isAdmin() || ( !$fieldSet->EOF ? $fieldSet->fields['is_view'] : '1' ) ) )
				continue;
			
			$type = ( $fieldSet->fields['type_input'] ? $fieldSet->fields['type_input'] : StyleInput( $field->type ) );
			$formula = $fieldSet->fields['formula'];
			$length = ( $fieldSet->fields['field_len'] ? $fieldSet->fields['field_len']  : $field->max_length );;
			$col_width = ( $length > 5 ? $length *10 : GetLengthFromType($type) )."px";
			$style = "min-width:$col_width;float:left;";
			
			if ( isAdmin() )
				echo WrapDIV( $name, GetInputFromType( $type, $name, $value, "width:100%;max-width:150px;", $formName, $in_table, $field_name), $style );
			else 
			{
				if (substr($name, 0, 2 ) == 'id' && (substr($name, 2) != substr($table, 2)) )
				 echo WrapDIV( $field_name, GetValueFromID( $value, $name ), $style ); 
				 
				elseif ($name == 'photo')
				 echo WrapDIV( $field_name, "<a href='/photos/$value' class='fancybox-button' rel='collection'> <img src='/photos/$value' style='$style'/> </a>", $style, 'center' ); 
				else
				{
					if ( !($value) && $formula )
					    $value = GetValueFormula( $formula );
					    
				  echo WrapDIV( $field_name, ( (($type=='checkbox') || ($type=='radio')) ? GetTextInput($name, '', $value, $style, $type, '', '' ) : ( $value ? $value : '-') ), $style ); 				
				}			
			}
		} //for
		 
}

  if ( !isset($_REQUEST['table']) )
     return;
 
 try
 {    
  include_once("params.php"); 
  include_once('config_db.php'); 

  $order = $fieldSet = $recordSet = '';
  $arr_field = array();
  $table = $_REQUEST['table'];
  $key_id = $_REQUEST['key_id'];
  $id = $_REQUEST['id'];


	 $recordSet = runSQL( "select * from $table where $key_id=$id".(isset($_REQUEST['order']) ? " order by ".($order=$_REQUEST['order']) : '') );
?>
 <div class='row'>
	 <?=GetRecords()?>
 </div>
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
