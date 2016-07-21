<?php
$_REQUEST['admin'] = 1;
if (isset($_REQUEST['admin'])) {
    $random = rand();
    echo <<<END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investors</title>
</head>
<body>
    <form method="post" action="addInvestor.php" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Название компании"><br><br>
        <input type="text" name="country" placeholder="Страна"><br><br>
        Добавьте логотип (jpg, gif, png, tif)<br>
        не более 3Мб<br>
        <input type='file' name="logotype" accept="image/*"><br><br>
        <button type="submit">Добавить инвестора</button>
    </form>
    <br>
    <iframe src='showInvestors.php?param={$random}' width='1000' height='1000' ></iframe> 
    </body>
</html>
END;
} else {
    include_once 'showInvestorsForVisitors.php';
}
