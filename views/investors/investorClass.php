<?php

class investorClass
{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $connectionToBase;

    /**
     * Записивает параметры поключения и вызывает фукнцию создания подключения к базе
     * investorClass constructor.
     * @param array $param
     */
    public function __construct(array $param)
    {
        $this->host = $param[0];
        $this->user = $param[1];
        $this->pass = $param[2];
        $this->db = $param[3];
        $this->connection();
    }

    /**
     * Создает подключение к базе и записывает в переменную $connectionToBase
     */
    public function connection()
    {
        $this->connectionToBase = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
    }

    /**
     * Функция удаляет запись из базы и возвращает bool (true успешно), (false не успешно)
     * @param $id -  номер записи для удаления из базы
     * @return bool
     */
    public function deleteInvestor($id)
    {
        $queryDeleteInvestor = "DELETE FROM investors WHERE id='{$id}' ";
        $result = mysqli_query($this->connectionToBase, $queryDeleteInvestor);
        $error = mysqli_error($this->connectionToBase);
        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Показывает таблицу инвестров для посетителей (без возможности удалять строки)
     * возвращает таблицу или бросает исключение с ошибкой
     * @return string
     */
    public function showInvestorsForVisitors()
    {
        $queryShowInvestors = "SELECT * FROM investors ORDER BY id";
        $result = mysqli_query($this->connectionToBase, $queryShowInvestors);
        $error = mysqli_error($this->connectionToBase);
        if ($error) {
            throw new Exception ('Ошибка чтения данных:<br>' . $error);
        }
        $message = "<table>";
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $message .= "<tr><td>{$row['name']}</td><td>{$row['country']}</td></tr>";
        }
        $message .= "</table>";
        return $message;
    }

    /**
     * Выводит таблицу инвесторов с возможностью удалять кортежи или бросает исключение с ошибкой
     * @return string
     * @throws Exception
     */
    public function showInvestor()
    {
        $queryShowInvestors = "SELECT * FROM investors ORDER BY id DESC";
        $result = mysqli_query($this->connectionToBase, $queryShowInvestors);
        $error = mysqli_error($this->connectionToBase);
        if ($error) {
            throw new Exception ('Ошибка чтения данных:<br>' . $error);
        }
        $message = "<table>";
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $logo = '';
            if ($row['logotype'] != "") {
                $logo = "<img class='logotypes' src='showLogotype.php?id={$row['id']}'>";
            }
            $deleteButton = "<form method='post' action='deleteInvestor.php' id='{$row['id']}'>
                                <input form='{$row['id']}' type='hidden' name='delete' value='{$row['id']}'>
                                <button form='{$row['id']}'>Удалить запись</button>
                            </form>";
            $message .= "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['country']}</td>
                        <td>{$logo}</td><td>{$deleteButton}</td></tr>";
        }
        $message .= "</table>";
        return $message;
    }

    /**
     * Возвращает логотип из БД в таблицу инвесторов или false при отсутствии изображения
     * @param $id
     * @return bool
     */
    public function showLogotype($id)
    {
        $queryForLogotype = "SELECT logotype FROM investors WHERE id='{$id}' ";
        $result = mysqli_query($this->connectionToBase, $queryForLogotype);
        $row = mysqli_fetch_array($result);
        $logotype = $row[0];
        if (!$logotype) {
            return false;
        } else {
            return $logotype;
        }
    }

    /**
     * Обработка изображения, принимает массив параметров изображения,
     * возвращает изображение в двоичном коде или номер ошибки + описание ошибки
     * @param array $files
     * @return array|bool|string
     */
    public function processingImage(array $files)
    {
        $ErrorDescription = '';
        $ErrorNumber = '';
        $result = array();
        $image_size = $files['size'];
        if ($image_size != 0) {
            if ($image_size > 1024 * 1024 * 3) {
                $ErrorNumber = 1;
                $ErrorDescription = "Ошибка! Размер логотипа дожен быть менее 3Мб";
                $result[0] = $ErrorNumber;
                $result[1] = $ErrorDescription;
                return $result;
            } else {
                switch ($files['type']) {
                    case 'image/jpeg':
                        $ext = 'jpg';
                        break;
                    case 'image/gif':
                        $ext = 'gif';
                        break;
                    case 'image/png':
                        $ext = 'png';
                        break;
                    case 'image/tiff':
                        $ext = 'tif';
                        break;
                    default:
                        $ext = '';
                        break;
                }
                if (!$ext) {
                    $ErrorNumber = 2;
                    $ErrorDescription = "Ошибка! Загруженный файл не является картинкой";
                    $result[0] = $ErrorNumber;
                    $result[1] = $ErrorDescription;
                    return $result;
                } else {
                    $image = file_get_contents($files['tmp_name']);
                    $image = mysqli_real_escape_string($this->connectionToBase, $image);
                    return $image;
                }
            }
        } else {
            //Логотип не был загружен
            $ErrorNumber = 3;
            $ErrorDescription = "Вы не загрузили логотип либо размер файла равен 0";
            $result[0] = $ErrorNumber;
            $result[1] = $ErrorDescription;
            //return $result;
            return '';
        }
    }

    /**
     * Добавление записи в таблицу Инвесторов, возвращает true (запись успешна), false (не успешна)
     * @param $name
     * @param $country
     * @param $logotype
     * @return bool
     */
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
        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Обезвреживает строку, возвращает обработанную строку
     * @param $string
     * @return string
     */
    public function sanitizeString($string)
    {
        $string = strip_tags($string);
        $string = htmlentities($string);
        $string = stripslashes($string);
        return mysqli_real_escape_string($this->connectionToBase, $string);
    }
}
