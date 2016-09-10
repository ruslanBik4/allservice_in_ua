<?php

class customersAuthorizarionController
{
    private $actionAttribute;

    private $getParams = [];

    public function __construct($getParams = null) {
        if ($getParams)
            $this->getParams = array_merge($this->getParams, $getParams);

        // Если $getParams['signin'] есть, значит форма была заполнена, небходимо войти в личный кабинет
        // В противном случае необходимо отобразить форму для регистрации
        if(isset($getParams['signin']))
        {
            $User = new prepareAndRunRequest('signin');
        }
        else
        {
//            echo $this->getFormAuthorization();
        }
    }

    /**
     *Метод для отрисовки формы авторизации
     */
    public function getFormAuthorization(){
        $queryObject = new Query();
        $query = "input_form_info('client_authorization')";
        $data = $queryObject->callProcedure($query);
        $this->actionAttribute = $data[0]['action'];
        $form = new formCreatorClass($data, $this->getParams);
        return "Для авторизации заполните форму:<br>Она отправиться на {$this->actionAttribute}<br>". $form->formCreation($this->actionAttribute );
    }
}