<?php
class investorClass
{
    private $param = array();
    private $connectionToBase;

    public function deleteInvestor($id){
        $queryDeleteInvestor = "DELETE FROM investors WHERE id='{$id}' ";
        $result = mysqli_query($this->connectionToBase, $queryDeleteInvestor);
        $error = mysqli_error($this->connectionToBase);
        if($error){
            die('Ошибка удаления данных:<br>'.$error);
        }
        return "Запись с id={$id} успешно удалена";
    }

    public function showInvestor(){
        $queryShowInvestors = "SELECT * FROM investors ORDER BY id DESC";
        $result = mysqli_query($this->connectionToBase, $queryShowInvestors);
        $error = mysqli_error($this->connectionToBase);
        if($error){
            die('Ошибка чтения данных:<br>'.$error);
        }
        $message = "<table>";
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $logo = '';
            if($row['logotype']!=""){
                $logo = "<img class='logotypes' src='showLogotype.php?id={$row['id']}'>";
            }
            $deleteButton = "<form method='post' action='deleteInvestor.php' id='{$row['id']}'>
                                <input form='{$row['id']}' type='hidden' name='delete' value='{$row['id']}'>
                                <button form='{$row['id']}'>Удалить запись</button>
                            </form>";
            $message.="<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['country']}</td>
                        <td>{$logo}</td><td>{$deleteButton}</td></tr>";
        }
        $message.= "</table>";
        return $message;
    }

    public function  showLogotype($id){
        $queryForLogotype = "SELECT logotype FROM investors WHERE id='{$id}' ";
        $result=mysqli_query($this->connectionToBase, $queryForLogotype);
        $row = mysqli_fetch_array($result);
        $logotype = $row[0];
        if(!$logotype) die();
        header('Content-type: image/*');
        echo $logotype;
    }

    public function processingImage(array $files)
    {
        $ErrorDescription = '';
        $image_size = $files['size'];
        if($image_size != 0) {
            if ($image_size > 1024 * 1024 * 3) {
                $ErrorDescription = "Ошибка! Размер логотипа дожен быть менее 3Мб";
                die($ErrorDescription."<br><br><a href='investors.html'><button>Перейти на страницу заполнения формы</button></a><br>");
            } else {
                switch($files['type'])
                {
                    case 'image/jpeg': $ext = 'jpg'; break;
                    case 'image/gif':  $ext = 'gif'; break;
                    case 'image/png':  $ext = 'png'; break;
                    case 'image/tiff': $ext = 'tif'; break;
                    default:           $ext = '';    break;
                }
                if (!$ext){
                    $ErrorDescription = "Ошибка! Загруженный файл не является картинкой";
                    die($ErrorDescription);
                } else {
                    $image = file_get_contents($files['tmp_name']);
                    $image = mysqli_real_escape_string($this->connectionToBase, $image);
                    return $image;
                }
            }
        } else {
            $ErrorDescription = "Вы не загрузили логотип либо размер файла равен 0";
            //echo($ErrorDescription);
        }
    }

    public function addInvestor($name, $country, $logotype)
    {
        $queryCreateTable = "CREATE TABLE IF NOT EXISTS investors(
          id INT AUTO_INCREMENT,
          name VARCHAR(100),
          country VARCHAR(50),
          logotype MEDIUMBLOB,
          PRIMARY KEY (id)) CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB
          ";
        $result = mysqli_query($this->connectionToBase, $queryCreateTable);
        $queryAddInvestor = "INSERT INTO investors(`name`, `country`, `logotype`) VALUES( '{$name}', '{$country}', '{$logotype}' )";
        $result = mysqli_query($this->connectionToBase, $queryAddInvestor);
        $error = mysqli_error($this->connectionToBase);
        if($error){
            die('Ошибка записи данных:<br>'.$error);
        } else {
            header('Location: investors.html');
            //echo "<br>Данные инвестора {$name} успешно внесены в БД";
        }
    }

    public function sanitizeString($string)
    {
        $string = strip_tags($string);
        $string = htmlentities($string);
        $string = stripslashes($string);
        return mysqli_real_escape_string($this->connectionToBase, $string);
    }

    public function connection($host, $user, $pass, $db){
        $this->connectionToBase = mysqli_connect($host, $user, $pass, $db);
        return $this->connectionToBase;
    }

    public function __construct(array $param){
        foreach ($param as $key => $value){
            $this->param[$key] = $value;
        }
        $this->connection($param[0],$param[1],$param[2],$param[3]);
    }
}
