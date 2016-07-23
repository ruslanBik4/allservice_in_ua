<?php
// Эти две строки моделируют работу сервера с кешированием
header("Cache-control: public");
header("Cache-control: max-age=1800");
?>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
<?php
require_once '../autoload.php';
require_once 'connectionData.php';

$params = array($host, $user, $password, $database);
$investor = new investorClass($params);

try {
    echo($investor->showInvestor());
} catch (Exception $e) {
    echo $e->getMessage();
}

?>