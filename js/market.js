$(window).load( function() {
	if ( ($('#admin').length > 0) || (document.location.hash.substring(1) == 'admin') )
	{ 
	    $('#catalog_pane').load("show_category.php?admin", SetMenuAction); 
	}
	// else  $('#catalog_pane').load("show_category.php", SetMenuAction ); // отключаю растяжку картинки.parents('div').css({ height: 'auto' });
 // запускаем смену баннеров через 30 секунд
//  setInterval( function() { $("#go_login").click(); }, 3000 );  
	
}); // $(document).ready

/*
$(window).scroll(function() {
		$('header').css( { left : $('#catalog_pane').width() - $(window).scrollLeft() } );
// 		$('#left_pane').css( { left : - $(window).scrollLeft() } );
});
*/

// отработка авторизации
function LoginSubmit(this_form, href) {
var old_text = $('p', this_form).html();

    $(this_form).ajaxSubmit({
		beforeSubmit: function(a,f,o) {
			o.dataType = "html";
			$('p', this_form).html('Начинаю отправку...');
/* 			$('div', this_form).hide(); */
		},
		success: function(data) {
		   var parent_div =	$(this_form).parent().hide().parent();
		   $('p', this_form).html( data + old_text );
		   if ( data.search('Ошибка') > -1)
		   {
		     alert(data);
		     $('div', this_form).show();
		     $('p', this_form).html( 'Неудачная попытка ' + old_text );
		     return false;
		   }
		   
		   $('#catalog_pane').load("show_category.php", { user : $('input[name=login]').val() }, SetMenuAction );
/*
		   return false;
		   AfterLogin(data);
*/
/*
		   if (document.location.pathname.substring(1))
		   {
*/
		     $('#pane').attr( 'rel', '' );
		     if ( $('#pane').length == 1 )
		     	ShowOkno( href ? href : document.location.pathname.substring(1)  +'&323' );
		     else
		     	window.location = document.location +'&323';
// 		   }
/* 		     parent_div.toggle().load( href, function () {$.fancybox.toggle(); parent_div.toggle() } ); */
		}
	});
		
	return false;
}

// увеличение шрифта
function BigFont() {
	$('#catalog_pane a, #article_pane').css( 'font-size', 24 );
	$('#pane').addClass( 'big-font' );
}
 // Здесь обработка для показа каталога в виде стожка
 
/*  * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true }); */
function cookieSet(index) {

$.cookie('submenuMark-' + index, 'opened', {expires: null, path: '/'}); // Set mark to cookie (submenu is shown):

}

function cookieDel(index) {

$.cookie('submenuMark-' + index, null, {expires: null, path: '/'}); // Delete mark from cookie (submenu is hidden):

}

function HideSubMenu(this_a, this_i) {

	   this_a.prev().removeClass('expanded').addClass('collapsed');
	  
	   cookieDel(this_i);
	
	   this_a.find('ul').each(function() {
	
	     this_a.hide(0, cookieDel( $( 'ul#my-menu ul' ).index( this_a ) ) ).prev().removeClass( 'expanded' ).addClass( 'collapsed' );
	
	   });
}

function SetMenuAction() {
 $('ul#my-menu ul').each(function(i) { // Check each submenu:

    if ($.cookie('submenuMark-' + i)) {  // If index of submenu is marked in cookies:

    	$(this).prev().removeClass('collapsed').addClass('expanded'); // Show it (add apropriate classes)

    }
    else {
  
     	$(this).prev().removeClass('expanded').addClass('collapsed'); // Hide it

    }

    $(this).prev().addClass('collapsible').click(function() { // Attach an event listener

		     var this_i = $('ul#my-menu ul').index($(this).next()), // The index of the submenu of the clicked link
			 	 this_a = $(this);
			 	 
		     if ( this_a.hasClass('collapsed') )
		     {
			     parent_a = $(this).parents('li').children('a').addClass('expanded');
		      	 $('.expanded').not(parent_a).removeClass('expanded').addClass('collapsed');
		     	this_a.removeClass('collapsed').addClass('expanded');
		      	 
		     }
		     else
			 	this_a.removeClass('expanded').addClass('collapsed');

		
		return true; //($('#admin').length > 0); // Prohibit the browser to follow the link address

   }); // Attach an event listener
  });

}; // SetMenuAction
//распахиваем элемент дерева
function ExpandParents( this_element ) {
	$(this_element).parents('li, form').children('a').addClass('expanded');
	return false;
}
//распахиваем элемент дерева и отрабатываем ссылку
function ShowOknoAndExpandParents( this_element ) {
	ShowOkno( this_element.dataset.href, this_element.title, this_element );
	return ExpandParents( this_element );
}


// подключение редактора
function InitEditorMemo(this_id) {
	if ( editor ) 
	{ 
		if (editor.name == this.id)
		 return; 
		else 
		 editor.destroy(); 
	}  
	CKEDITOR.on( 'instanceReady', function() {
// var topEditor = cookieGet('cke_top') || topEditorOffset || DivContent.offset().top - $('.cke_top').height();				    
	$('.cke_top')/* .css( { top : topEditor } ) */.addClass('notHidden');
	$('.cke_top .cke_toolbar_break').detach();
//     $('.cke_contents').css( { height : DivContent.height() } );
//     $('.cke_bottom').css( { top : $(this).offset().top + $(this).height() + 5 } );
    });	
    editor = CKEDITOR.replace( this_id );
}
// показ и скрытие КОРЗИНЫ
var isProcess = 0;
function DeleteTovar(name) {

 var rezult = confirm( 'Вы ТОЧНО хотите удалить товар "' + name + '" ?'  );
 
 if (rezult)
 {
  $('#resSave').show();
  $('#body').scrollTo( 0 );
 }
 return rezult;
}

function ResetPane(key_parent) {
/*  if ( $('#resSave').attr('display') == 'none' ) */
/*     return false; */

  $('#pane').hide().load('show_tovar.php?key_parent=' + key_parent + '&admin=', function () {$('#resSave').hide(); } ).show();
}

function handleFileSelect(evt) {
var files = evt.files || evt.target.files; // FileList object

   if (files.length < 1)
     return false;

	f = files[0];
	  // Only process image files.
	  if (!f.type.match('image.*')) {
	    alert('Файл не графического типа!');
	    return true;
	  }
	  
	  if (f.size > 2000000) {
	    alert('Файл ' + f.name + ' слишком велик! Загрузка может оказаться очень долгой и прерваться в неподходящий момент. Советуем сжать его с помощью любого графического редатора.' + f.size + ' б');
	    return true;	  
	  }
	  
	  var reader = new FileReader();
	
	  // Closure to capture the file information.
	  reader.onload = (function(theFile) {
	    return function(e) {
	      // Render thumbnail.
	      $('#img_' + evt.target.id).attr( 'src', e.target.result ).attr( 'title', escape(theFile.name) );
	      $('#uploadOutput' +  + evt.target.id.substring(5) ).html('Не забудьте нажать кнопку <b>Сохранить изменения</b>!');
	    };
	  })(f);
	
	  // Read in the image file as a data URL.
	  reader.readAsDataURL(f);
	//}
  return true;		
}
// считывание файла для импорта
function handleFileImport(event) {
var files = event.files || event.target.files, // FileList object
	$progress = $('#progressfImport').show();

   if (files.length < 1)
     return false;
     
	// Считываем массив добавленных файлов и создаем для них оконочки.
	f = files[0];
	
	
	  var reader = new FileReader();
	
/* 	  Ход загрузки */
	  reader.onprogress = (function(evt) {
	  
	      percentComplete = Math.round( (evt.loaded / evt.total) / 100); 
		  $progress.val(percentComplete).text( 'Progress - ' + percentComplete + '%' );
	  });
	  
	  // Завершение загрузки файла
	  reader.onload = (function(theFile) {
	    return function(e) {
	      // Render thumbnail.
	      $('#pane').html(  e.target.result );	      
	      $('#pane')[0].file = theFile;
// 	      $('#btReadSclad').show();     
	           
	    };
	  })(f);
	
	  // Read in the image file as a data URL.
	  reader.readAsText(f);
	  
	  // записываем имя файла в поле ввода имени таблицы (как правило, они совпадают )
	  filename = f.name/* .split('.')[0] */.replace(/\s*(\(\d+\))*\.csv/, '');
	  
	  $(event.currentTarget).prevAll('input').val( filename );
	  // записываю атрибут pane, чтобы потом перегрузить Содержимое по этой таблице
	  DivContent.attr('rel', 'show_tovar.php?table=' + filename);
	  
	  FormIsModified( event, event.currentTarget.form );
	
	
  return true;		
}

function InputTableIsModified( event, this_element ) {


	  DivContent.attr('rel', 'show_tovar.php?table=' + this_element.value );
	return false;
}
function LoadPhoto(key_tovary) {

  $('#photo' + key_tovary).click();
        
  return false;		
}
// докачка записей
var
   inRun = false;
function GetMoreRecords( this_element ) {
	
	if (inRun || !this_element)
		return false;
	inRun = true;
	$.get( this_element.href + ' #table_body', GetNewrecords );
	return false;
}
// вычленение составнх частей докачки
function GetNewrecords(data, status) {
/*
	if (footer = data.match(/<div[^>]*divpager.*?>([\s|\S]+)<\/div>/im) )
	{
		$('footer').html( footer[1] );
		data = data.replace(footer[0], '');
	}
*/
	data = FindAndCutPart( data, $('#divpager'), /<div[^>]*divpager[^>]*>\s*(<([a-z]*)[\s\S]*(?=<\/\2>)*)?<\/div>/im, '' );
// 	console.log( data );
/*
	if (header = data.match(/<header.*?>([\s|\S]+)<\/header>/i) )
	{
		$('header').html( header[1] );
		data = data.replace(header[0], '');
	}
*/
	
	if (add_records = data.match(/table_body.*?>([\s|\S]+)<\/div>/i))
		$('#table_body').append(add_records[1] ); 

	inRun = false;
}
// работа с формами
function FormIsModified( event, this_form ) {
  event = event || window.event;
  
	$( 'input[type=image], input[type=submit], input[type=button]', this_form ).show();
	$( 'input[required]', this_form ).show();
	this_form.State.value = '✎';
/*
	if ( this != this_form)
	  console.log('modified', this);
*/
}
// работа с формами
function GetCorrectValue( this_element ) {

	if ( this_element.value.search('checked') > -1 ) // для чекбоксов
	  return this_element.value;

	switch ( this_element.type )
	{
		case 'select-one':
		  return $( 'option[value=' + this_element.value + ' ]', this_element).text();
		case 'checkbox':
		  return ( this_element.checked ? 'checked' : 'off' );
		default:
		 return this_element.value.replace(/^\s+|\s+$/g, '');
	}
}
function SelectedTD() {
	 var parent_tr = $(this).parent(); 
		  flag = true; 
		  $('.selected').each( function() { 
			  if ( $('.td[name=' + this.name + ']', parent_tr).text().search( GetCorrectValue(this) ) == -1 ) 
			  {
				   flag=false; 
				   return flag;
			  } 
			}); 
		if (flag)
		 parent_tr.show(); 
}
var sel_tr = '#table_body .tr';
function FilterIsModified( event, this_element ) {
  event = event || window.event;
var thisvalue = this_element.value,
	regExist =  new RegExp( "(where|AND)\\s+'" + this_element.name + "'=[^&]+(?=(AND|&))?", 'i' );
  
  if ( (event.type == 'keyup') && (event.keyCode != 13) ) // ввод текста до Enter никак не обрабатываем
  {
	  return true;
  }
  if ( (thisvalue.replace(/^\s+|\s+$/g, '') == '')  
  		|| ( ( this_element.type == 'checkbox' ) && ( !this_element.checked ) ) ) // получили пустое значение - обнуляем фильтры
  {
	    $(this_element).removeClass('selected');
	    $( sel_tr + ':hidden .td[name=' + this_element.name + ']' ).each( SelectedTD );
	    next_page = $('#aNextPage').attr('href');
		if (next_page) // докачаем с учетом фильтра
		{
	  	    $('#aNextPage').attr( 'href', next_page.replace( regExist, '' ) ).click();
			$('#aSaveCSV').attr('href', $('#aSaveCSV').attr('href').replace( regExist, '' ) );
			$('#aSavePDF').attr('href', $('#aSavePDF').attr('href').replace( regExist, '' ) );
			
		}
	  	else
	  	  $.get( document.href + ' #table_body', GetNewrecords );  

  }
  else
  {
	  thisvalue = GetCorrectValue( this_element  );
	  $( 'input[type=image]', this_element.form ).show();
  
		if ( thisvalue.search('checked') > -1 ) // для чекбоксов
			otbor = $( sel_tr + ' .td[name=' + this_element.name + ']:has(input[' + thisvalue + '])' ); //подходящие элементы
		else
			otbor = $( sel_tr + ' .td[name=' + this_element.name + ']:contains(' + thisvalue + ')' ); //подходящие элементы
			
		if ( $(this_element).hasClass('selected') ) //
			otbor.each( SelectedTD ); // надо проверить походящие на соответствие другим фильтрам
// 		else
			$( sel_tr + ' .td[name=' + this_element.name + ']' ).not(otbor).parent().hide(); // скрываем неподходящие
// 		$('.selected').removeClass('selected');
		$(this_element).addClass('selected');
		next_page = $('#aNextPage').attr('href');
		if (next_page) // докачаем с учетом фильтра
		{
// 			if ( $( sel_tr + ':visible').length < 50 )
				next_page.replace( /offset=\d+/, 'offset=' + $( sel_tr + ':visible').length );
				
			$('#aNextPage').attr( 'href', AddNewFilter( next_page, this_element ) ).click();
		}
		// создаем ссылку для создания CSV
		if ( href = $('#aSaveCSV').attr('href') )
			$('#aSaveCSV').attr('href', AddNewFilter( href, this_element ) );
		if ( href = $('#aSavePDF').attr('href') )
			$('#aSavePDF').attr('href', AddNewFilter( href, this_element ) );
		
		FormIsModified( event, this_element.form ); // включаем кнопку сброса
// 	 }
  }
  
  return false;
}
// gолучаем условия для sql-запроса, по строкам дадим LIKE
function GetConditionFromElement(this_element) {
	switch ( this_element.type )
	{
		case 'select-one':
		  return this_element.name  +  "=" + this_element.value; // из списка получаем ЖЕСТКОЕ равенство
		case 'checkbox':
		  return this_element.name;
		default:
		 return this_element.name  +  " REGEXP '" + this_element.value.replace(/^\s+|\s+$/g, '') + "'";
	}
}
// вставляем новое условие в фильтра
function AddNewFilter( href, this_element ) {
var add_where = GetConditionFromElement(this_element),
	regExist  =  new RegExp( this_element.name + "=[^&]+(?=(AND|&))?", 'i' );
	
  if ( href.search( /table=/) > -1 )
	return href.replace( /table=([^&]+?(where[^&]+)*)(?=&)/i, 
			function (str, p1, p2, offset, s) 
			{ 
				if ( p1.search( this_element.name ) > -1 )
					return "table=" + p1.replace( regExist, add_where );				
				else
					return "table=" + p1 + (p2 ? ' AND ' : ' where ' ) + add_where;
			 } );
  if ( href.search( /where=/) > -1 )
	return href.replace( /where=([^&]+)/i, 
			function (str, p1, offset, s) 
			{ 
				if ( p1.search( this_element.name ) > -1 )
				  return str.replace( regExist, add_where ); 
				
				else
				  return str + ' AND ' +  add_where; 
				
			} );
  else
	return href + "&where=" + add_where ;
	
}
// фильтрация
 function PLayFilter( this_form ) {
	 $('.selected').removeClass('selected').each( function () { this.value = ''; } );
	 $( sel_tr ).show();
// 	 $(':input[form="' + this_form.name + '"][type!=image][modified]').each( function(i) { if (this.value) { cond = this.value; $('table tbody tr').find('td:eq(' + i + ')' ).each( function() { $.globalEval( 'cond1 = ($(this).text() ==' + cond + ');'); if (!cond1) $(this).parent().hide(); }) } }); 
	 return false;
 }


// отправка данных на сервер
function SaveObject( this_form, id ) {
var //id = ( $('input[name=' + key_name + ']', this_form).val() || ''),
    $out = $('#uploadOutput'),
    $loading = $('#loading' + $(this_form).attr('id') ),
    $progress = $('#progress' + $(this_form).attr('id') );

	  
    $(this_form).ajaxSubmit({
		beforeSubmit: function(a,f,o) {
			o.dataType = "html";
			$out.html('Начинаю отправку...');
			this_form.State.value = '⏳';
			$progress.show();
			$loading.show();
			// записываю измененные checkbox
			$('input[form="' + this_form.name + '"][checked]:not(:checked)').each( function () { a.push(  new Object( { name : this.name, value : 0 } ) ); } ); 
			
		},
		uploadProgress: function(event, position, total, percentComplete) {
		    $out.html( 'Progress - ' + percentComplete + '%' );
		    $progress.val( percentComplete );
		    if (this_form.State.value == '⏳')
		      this_form.State.value = '⌛️';
		    else
		      this_form.State.value = '⏳';
		},
		success: function(data) {
			if (typeof data == 'object' && data.nodeType)
				data = elementToString(data.documentElement, true);
			else if (typeof data == 'object')
				data = objToString(data);
				
			$out.html( data );
			this_form.State.value = '✔︎';
			$progress.hide();
			$loading.hide();
			
			if ( data.search(/Ошибка/) > -1)
			   alert(data);
			else if ( id )  // успешно изменили
			{
			   $( 'input[type=image], input[type=submit]', this_form ).hide();
			 if ( id == 'key_category' )
			   $('#catalog_pane').load("show_category.php?admin", SetMenuAction  );
				
			}
			else  // успешно добавили новый
				ShowOkno( DivContent.attr('rel') + '&' + Math.random() ); // меняю строчку, чтобы перезагрузка сработала  потом надо поменять
				
		}
		
    });

return false;
}

function AddCategory( this_form ) {
var key_tovary = ( $('input[name=key_category]', this_form).val() || ''),
    $out = $('#uploadOutput' + key_tovary),
	$loading = $('#loading' + key_tovary),
	$progress= $('#progress' + key_tovary);
		  
    $(this_form).ajaxSubmit({
		beforeSubmit: function(a,f,o) {
			o.dataType = "html";
			$out.html('Начинаю отправку...');
			$loading.show();
			$progress.show();
		},
		uploadProgress: function(event, position, total, percentComplete) {
		    $out.html( 'Progress - ' + percentComplete + '%' );
		    $progress.val(percentComplete);
		},
		failed:  function(data) {
			$out.html( data );
		},
		success: function(data) {
			$out.html('Успешно изменили запись.');
			$loading.hide();
			$progress.hide();
			if (typeof data == 'object' && data.nodeType)
				data = elementToString(data.documentElement, true);
			else if (typeof data == 'object')
				data = objToString(data);
				
			$out.html( data );
			if (key_tovary > '')
			   $('#img_photo' + key_tovary).attr( 'src', 'getphoto.php?key_category=' + key_tovary );
			else {
			   $('#catalog_pane').load("show_category.php?admin", SetMenuAction  );
			   $('#pane').load( "show_tovar.php?key_parent=" + $('input[name=key_parent]', this_form).val() + '&category$admin' );
			}
		}
		
    });
 
 
 return false;
}

function DelCategory( name, key_category ) {
 if ( confirm( 'Вы ТОЧНО хотите удалить "' + name + '" ?' ) )
 {
   $.post("del_category.php", { 'key_category' : key_category }, function (data) { if (data.search('Ошибка') == 0) $('#catalog_pane').load("show_category.php?admin", SetMenuAction ) } );
 }
 return false;
}

// Реализация поиска
function fsubmit() { 
var DivPane = $('#pane'),
    text_s  = $('#search_text').val() ;

 DivPane.load( "show_tovar.php", 
			 $('#form_s').serializeArray(), 
			 function (data) { 
			 
			 if (data == 0)
			 {
			  alert('Нет данных с такими параметрами!(' + text_s + ')');
			  return false;
			 }
			 else PostLoadOkno( DivPane, "show_tovar", 'Поиск фразы "' + text_s + '" ', '', ".php?search_text=" + text_s );
			 //AddClickShowOkno($('#pane'));
 });
 return false;
}
var arrData;
// для работы с диаграммами

//применение макроса для отбора
function ShowFromMacrosField( this_element, title ) { // для элемента
	
	return ShowFromMacros( this_element.form, title );
}
function GetAddTitle( this_element ) {
	
	switch ( this_element.type )
	{
		case 'select-one':
		{
			return  this_element.value ;
		}
		case 'date':
		{
			return this_element.valueAsDate;
		}
		case 'month':
		{
			return  this_element.valueAsDate +  "-01";
		}
		case 'checkbox':
			return ( this_element.checked ? $(this_element).prev().text() : '' );
		default:
			return ( this_element.value ? this_element.placeholder + '=' + this_element.value : '');
	}
}
function ShowFromMacros( this_form ) {  // для формы
	var action = this_form.action,
	    add_title = '',
		process = true;
		
	$( 'input[type!=submit], select', this_form ).each( function () {	
		if( this.required && !(this.value) ) 
		{ 
			$(this).focus(); 
			return (process = false); // прерываем все
		} 
		action = GetModifyAction( this, action ); 
		if (this.value)
			add_title += GetAddTitle( this ) + ','; 
	}); // each
		
	if (process)
	    ShowOkno( action, this_form.title );
	
	return false;
}
function GetStrongDateFromElement(this_element) {
	return this_element.value + ( this_element.type == "month" ? '-01' : '');
}
// получаем значение из поля для sql-запроса
function GetModifyAction( this_element, action ) {
	var macros = "'" + this_element.value + "'", 
	    name_param = this_element.name,
	    add_action = name_param + '=' + this_element.value,
	    // на случай браузера, не знающего date, month ect.
	    elem_type  = ( this_element.type == 'text' ? $(this_element).attr('type') : this_element.type ); 
	
	switch ( elem_type )
	{
		case 'select-one':
		{
			macros = "'" + this_element.value + "'";
			break;			
		}
		case 'date':
		{
			macros = "'" + GetStrongDateFromElement(this_element) + "'";
			break;
		}
		case 'month':
		{
			macros = "'" + this_element.value +  "-01'";
			add_action += "-01";
			break;
		}
		case 'checkbox':
		{
			name_param = 'check_' + this_element.name;
			 
			if ( this_element.checked )
			{
				macros = this_element.name;
			}
			else
			{
				macros  = ( this_element.required ? '0' : '(1=1)' );
			}
			add_action = this_element.name + '=' + macros;				
				 
			break;
		}
		default: // для текстовых полей и прочих, пока мы их не переопределим
		{
		  	value=this_element.value.replace(/^\s+|\s+$/g, '');
		  	
			if ( value > '' )
			  macros = "'" + value + "'"; // вставляем имя поля, если нужно
			else
			  macros = '';//( this_element.required ? '' : '(1=1)' );

		  	if ( $(this_element).attr('data-param') == 'or' )
			{
				if ( value > '' )
				  macros = name_param + "=" + macros; // вставляем имя поля, если нужно
// 			  add_action = 'or_' + name_param + '=' + macros;
				
			}
/*
			else
			{
				  
*/
				add_action = name_param + '=' + macros;
				
// 			}
		}
	}
	
	Re = new RegExp( '#\\$' + name_param + '(%%)*\\$#', 'g' );
	
	return action.replace( Re, macros ) + '&' + add_action;
}
function NewFromMacros( this_form, title ) {
	var action = this_form.action, process = true;
		
	$('#pane script').text( '');
	$('#postDiv').detach(); // убираю нижнюю полоску, если она есть

	$( 'input[type!=submit], select', this_form ).each( function () {	if( this.required && !(this.value) ) { $(this).focus(); return (process = false);} action = GetModifyAction( this, action ); } );
		
	if (process)
		$.get( action, PutChartBefore ).fail( function(status) { alert(status) } );
// 	document.title = title; 
	
	return false;
}
// ставляем диаграмму ВПЕРЕДИ всех прочих
function PutChartBefore(data) {
	 
	// возможно, здесь есть лишние действия
	if (add_records = data.match(/script.*?>([\s|\S]+)<\/script>/im))
	{
		$('#pane').append( data );
/*
		$('.chart_div:last').after('<div class="chart_div" onclick="if ( confirm( \'Вы ТОЧНО хотите удалить диаграмму ?\' ) ) $(this).hide();" >'); 
		$('.table_div:last').after('<div class="table_div"></div>');
		$('.bar_div:last').after('<div class="bar_div"></div>');
		drawChart();
*/
	}
	else 
	   return;
	   
}
// удаление диаграммы
function RemoveChart(event) {
var parent_width = $(event.currentTarget).width();
	
	if ( (event.offsetY < 10) && (event.offsetX > parent_width * 9 / 10 ) && confirm( 'Вы ТОЧНО хотите удалить диаграмму ?' ) )
	{
		 $(event.currentTarget).remove();
		 return false;		
	}
}
function Draw( fCallBack ) {
	fCallBack = typeof fCallBack !== 'undefined' ?  fCallBack : drawChart;
    
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart', 'table'], 'callback' : fCallBack });

  // Set a callback to run when the Google Visualization API is loaded.
//       google.setOnLoadCallback(drawChart);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
}
function drawChart() {

// Create the data table.
var bar_div = $('.bar_div:last'),
	colm_div = $('.colm_div:last'), 
	chart_div = $('.chart_div:last'), 
	table_div = $('.table_div:last'),
	chart_w   = parseInt( $('#content').width()  / 3, 10 ) - 10;
	
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart( chart_div[0] );
		data  = GetData(),
		data_rows = data.getNumberOfRows(); 
	
	// Set chart options
	var options = {'title': GetTitle(),
	               'width': chart_w+10,
	               'is3D':  true,
	               'legend': 'top',
	               'height': ( data_rows < 10 ? 300 : data_rows * 5 ) 
	               };
	
	chart.draw(data, options);
	
	var bar = new google.visualization.BarChart( bar_div[0] );
	
	options.height = data_rows * 25;
	bar.draw(data, options);
	
	var table = new google.visualization.Table( table_div[0] );

        data.addRow( [ 'Итого', GetCountData() ] );
        table.draw(data, {showRowNumber: true, width: chart_w-10, height: options.height * 2 });
    
    
    var view = new google.visualization.DataView(data);
      view.setColumns([0, 1/*
,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2
*/]);

      var options = {
//         title: "Density of Precious Metals, in g/cm^3",
        width: chart_w,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart( colm_div[0] );
      chart.draw(view, options);
}

function drawAreaChart() {
        var data = google.visualization.arrayToDataTable([
	        [ 'Размер', 'Красный', 'Подол', 'Количество'],
/* data.addRow(  */[ 'L', 1, 2, 45 ],//  ); 
/*  data.addRow(  */[ 'S', 1, 2, 4 ],// ); 
/*  data.addRow(  */[ 'M', 1, 2, 5 ],// ); 
/*  data.addRow( */ [ 'XL', 1, 2, 10 ],// ); 
/*  data.addRow(  */[ 'L', 1, 2, 15 ],// ); 
/*  data.addRow(  */[ 'S', 1, 2, 55 ]// ); 
        ]);


	//основные столбцы
/*
	data.addColumn('string', 'Размер');
	data.addColumn('string', 'Цвет');
	data.addColumn('string', 'Магазин');
 	data.addColumn('number', 'Количество');
*/

 
}
function drawComboChart() {
var data = GetData(),
    chart = new google.visualization.ComboChart( $('.combobar_div')[0] ), 
    options = GetOptions();
    
    chart.draw(data, options);
}