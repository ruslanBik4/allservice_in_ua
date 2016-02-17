<?php
	require_once 'tcpdf/tcpdf.php'; 
	// подключаем библиотеку 
	// создаем объект TCPDF - документ с размерами формата A4 
	// ориентация - книжная 
	// единицы измерения - миллиметры 
	// кодировка - UTF-8 
	$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); 
	// убираем на всякий случай шапку и футер документа 
// 	$pdf->setPageOrientation('Landscape'); //setPrintHeader(false); 
	$pdf->setPrintFooter(false); 
	$pdf->SetMargins(20, 25, 25); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа) 
// 	$pdf->SetXY(90, 10); 
	// устанавливаем координаты вывода текста в рамке: 
	// 90 мм - отступ от левого края бумаги, 10 мм - от верхнего 

	$pdf->SetDrawColor(0, 0, 200);  // устанавливаем цвет рамки (синий) 
	$pdf->SetTextColor(0, 200, 0);  // устанавливаем цвет текста (зеленый) 
// set cell padding
$pdf->setCellPaddings(1, 1, 1, 1);

// set cell margins
$pdf->setCellMargins(1, 1, 1, 1);

// set color for background
$pdf->SetFillColor(255, 255, 127);

  $_SERVER['HTTP_REFERER'] = ';;;'; // отключаю срабатывание редиректа
  require_once("params.php");
   
	 if ( isset($_REQUEST['table']) )
		  $table = $_REQUEST['table']; 
	 else if ( isset($_REQUEST['key_parent']) )// если нет запроса, возможно,  это вызов категорий для просмотра
		 
			 $table =  GetParamFromCategory( $_REQUEST['key_parent'] );
	 
	 else
	     return;

	$pdf->SetTitle("Данные по $table"); //(30, 6, "Данные по $table", 1, 1, 'C');  // выводим ячейку с надписью шириной 30 мм и высотой 6 мм. Строка отцентрирована относительно границ ячейки 

  if ( $where = GetParamOrDefault( 'where', '' ) )
  	$table .= " where $where";

    $recordSet = runSQL( "select * from $table", 20 );
    
//     $pdf->setDefaultTableColumns( $recordSet->FieldCount() );
	 
  		for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
		{
			$field = $recordSet->FetchField($i); 
			$name  = $field->name;
			$value = $recordSet->fields[$name];
			
			$arr_field[$i]["field_name"] = $field_name = GetFieldProp($name);
			$arr_field[$i]["length"] = $length = ( $fieldSet->fields['field_len'] ? $fieldSet->fields['field_len']/2  : ( ($length = $field->max_length) <51 ? $length : 50) );
			$arr_field[$i]["type"] = $type = ( $fieldSet->fields['type_input'] ? $fieldSet->fields['type_input'] : StyleInput( $field->type ) );
			
			$arr_field[$i]["is_view"] = ( !$fieldSet->EOF ? $fieldSet->fields['is_view'] : '1' );
			
			$align = ( (($type=='checkbox') || ($type=='radio')) ? 'center' : ( ($type=='number') ? 'center' : 'left' ) );
			$col_width = ( $length > 0 ? $length : GetLengthFromType($type) );
			   if ( ($align == 'center') && ($col_width < 10) )
			     $col_width = 10;
			   else if ( ($type=='number') && ($col_width < 50) )
			     $col_width = 50;
/*
			   else if ( $col_width < 80 )
			     $col_width = 80;
*/
			     
			$arr_field[$i]["length"] = $col_width;
		} //for

	while (!$recordSet->EOF) {
		$pdf->AddPage();  // создаем первую страницу, на которой будет содержимое 
		if ( ($photo=$recordSet->fields['photo']) ) 
		{
		     $pdf->Image( "/photos/$photo", 0, 0, 500 );
		     $pdf->setCellMargins( 110 );
			
		}
		     
  		for( $i = 0; $i < $recordSet->FieldCount(); $i++ )
		{
			$field = $recordSet->FetchField($i); 
			$name  = $field->name;
			$value = $recordSet->fields[$i];
			if ( !$arr_field[$i]["is_view"]  || ($name == 'photo') )
			  continue;
			
			if (substr($name, 0, 2 ) == 'id' && (substr($name, 2) != substr($table, 2)) && ($name != 'id') )
				$value = GetValueFromID( $value, $name ); 
				
			$type = $arr_field[$i]["type"];
			$align = ( (($type=='checkbox') || ($type=='radio')) ? 'C' : ( ($type=='number') ? 'C' : 'L' ) );
			
			// Наименование поля
			$pdf->SetFont('freeserif', 'b', 12);
			$pdf->Cell( 50, 0,  $arr_field[$i]["field_name"], 1, 0, 'L' ) ;
			
			$pdf->SetFont( '', '' );

			if (($type=='checkbox') || ($type=='radio'))
			   $pdf-> CheckBox( $name, 12, $recordSet->fields[$i] );
			else   
				$pdf->Cell( -$arr_field[$i]["length"], 0,  ( $value ? $value : '-'), 0, 0, 'L' ) ;
				
			$pdf->Ln(); //ПЕРЕВОД СТРОКИ

		} //for
		$pdf->Ln();
		$recordSet->MoveNext();
	}	 // while
	 $pdf->Output("$table.pdf", 'I'); 
	 // выводим документ в браузер, заставляя его включить плагин для отображения PDF (если имеется) 

?>