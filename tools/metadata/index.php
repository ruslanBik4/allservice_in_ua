<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Метаданные форм ввода</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<html><body>

<div class="left">
<div>
<h2>Выберите форму ввода</h2>
<select name="form_name">
<option>Загрузить</option>
<option>список</option>
<option>форм ввода</option>
<option>из базы</option>
</select>
</label><br>
или
<button>добавьте новую</button>
</div>
</div>

<div class="right">
<h2>Свойства формы</h2>
<div>
<form name="edit_form">
html_name
<input type="text"><br>
description
<textarea></textarea><br>
обработчик
<input type="text"><br>
<button>Сохранить</button>
</form>
</div>
</div>

<div class="left">
<div>
<h2>Список Полей</h2>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<button>Добавить поле</button>
</div>
</div>

<div class="right">
<div>
<h2>Свойства полей</h2>
db_table_name
<input type="text"><br>
db_field_name
<input type="text"><br>
label
<input type="text"><br>
html_type
<input type="text"><br>
html_class
<input type="text"><br>
html_name
<input type="text"><br>
html_id
<input type="text"><br>
html_value
<input type="text"><br>
html_placeholder
<input type="text"><br>
npp
<input type="text"><br>
<button>Сохранить</button>
</div>
</div>

<div class="left">
<div>
<h2>Список правил</h2>
<input type="text"><br>
<input type="text"><br>
<input type="text"><br>
<button>Добавить правило</button>
</div>
</div>

<div class="right">
<div>
<h2>Настройки правила</h2>
name
<input type="text"><br>
value
<input type="text"><br>
relative_html_input_name
<input type="text"><br>
<button>Сохранить</button>
</div>
</div>


<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/metadata.js"></script>

</body></html>
