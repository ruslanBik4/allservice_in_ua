<?php

class investorsController
{
    // хранит объект модели
    protected $model;
    // путь к представлениям 
    protected $view_path = '../views/investors/';

    /**
     * Создает объект модели и записывает в переменную $model
     * investorsController constructor.
     */
    public function __construct()
        {
            $this->model = new investorsModel();
        }


    /**
     * Получает данные $data списка инвесторов  из модели и передает их в экземпляр представления $view 
     * для отображения методом render()
     * @throws Exception
     */
    public function visitor()
    {
        $data = $this->model->showInvestorsForVisitors();
        $view = new investorsView($data, $this->view_path.'showInvestorsForVisitors.html');
        echo $view->render();    
    }


    /**
     * Если администратор авторизирован данные $data передает в экземпляр представления $view
     * для отображения методом render(), в противном случае вызывается метод visitor() для вывода данных для посетителей
     */
    public function admin()
    {
        $_REQUEST['admin'] = '1';
        
        if (isset($_REQUEST['admin']))
        {
            $data['random'] = rand();
            $view = new investorsView($data, $this->view_path.'adminInvestors.html');
            echo $view->render();
        } else {
            $this->visitor();
        }
    }


    /**
     * Данные $data получает из модели, передает в экземпляр представления $view
     * для отображения методом render()
     * @throws Exception
     */
    public function edit()
    {
        $data = $this->model->showInvestors();
        $view = new investorsView($data, $this->view_path.'showInvestors.html');
        echo $view->render();
    }

    /**
     * Добавление новой записи в таблицу БД
     */
    public function add(){
        if ($_POST['name'] == '' || $_POST['country'] == '') {
            $view = new investorsView(null, $this->view_path.'addEmpty.html');
            echo $view->render();
        } else {
            $result = $this->model->addInvestor('investors', $_POST);
            if($result){
                header('Location: admin');
            } else {
                $view = new investorsView(null, $this->view_path.'addError.html');
                echo $view->render();
            }
        }
    }

    /**
     * Удаление записи из таблицы БД
     */
    public function delete(){
        $result = $this->model->deleteInvestor($_POST['delete']);
        if($result){
            header('Location: edit');
        } else {
            $view = new investorsView(null, $this->view_path.'deleteError.html');
            echo $view->render();
        }
    }

    /**
     * Вывод записи из таблицы инвесторов для редактирования
     * @throws Exception
     */
    public function correct(){
        $id = $_POST['correct'];
        $data = $this->model->correctInvestor($id);
        $view = new investorsView($data, $this->view_path.'correctForm.html');
        echo $view->render();
    }

    /**
     * Обновление записи в таблице БД после редактирования
     */
    public function update(){
        if ($_POST['id'] == '' || $_POST['name'] == '' || $_POST['country'] == '') {
            $view = new investorsView(null, $this->view_path.'updateEmpty.html');
            echo $view->render();
        } else {
            $id = array_shift($_POST);
            $id = 'id='.$id;
            $result = $this->model->updateInvestor('investors', $_POST, $id);
            if($result){
                header('Location: edit');
            } else {
                $view = new investorsView(null, $this->view_path.'updateError.html');
                echo $view->render();
            }
        }
    }

}