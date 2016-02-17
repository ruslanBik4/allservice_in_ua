<?php
try
{	
	if ( !isset($_REQUEST['search_text']) )
		return; 
	
	require_once('params.php');
    require_once('config_db.php'); 

	$search_text = $_REQUEST['search_text'];
	
	    $words = split( ' ', $search_text );
	    $new_search = '%';
	    
	    // проверяем для каждого слова фразы поиска на ее аналог в таблице ассоциаций
	    foreach( $words as $ind => $word ) {
		    
		    $recordSearch = runSQL("select * from search where name LIKE ? ", 1, 1, Array( $word ) );
		    if ( !$recordSearch->EOF ) {
// 			 нашли соответствие-замену   
		    	$new_word = $recordSearch->fields['replace_name'];
			    $new_search .= "$new_word%";	
			    $search_text = $new_search;
			    // добавляем оставшиеся слова в конец без изменений
			    for( $i=$ind+1; $i < count($words); $i++ ) 
			    	$search_text .= $words[$i].'%';
			    	
			    $recordSet = runSQL( "select * from category where name LIKE ? order by name", GetParamOrDefault( 'nrows', 5 ), GetParamOrDefault( 'offset', -1 ), Array( $search_text )  );
			    if ($recordSet)
			    	break;
		    }
		    $new_search .= "$word%";
	    }
	    	
	    
	    	
	$text = '';

	foreach($recordSet as $k => $row)
    {	    
	    $text .= "<option value='{$row[0]}'>{$row[1]}</option>";
	}	 // while
	
	echo $text;
} 
catch (exception $e) { 
      echo  GetError( $e, $conn->ErrorMsg() );
} 


?>