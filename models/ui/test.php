<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Тест отправки формы</title>
    <meta charset="utf-8">
</head>
<html><body>


<?php
require_once '../autoload.php';

$f = new ui_inputForm('client_registration');
debug::VD($f);
echo $f->toHtml();
?>

<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/ui.js"></script>

</body></html>
