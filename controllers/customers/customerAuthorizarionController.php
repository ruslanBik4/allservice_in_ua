<?php

class customerAuthorizarionController
{
    public function __construct()
    {
        echo 'Hi Authorization!';
    }

    /**
     *Метод для отрисовки формы авторизации
     */
    public function getFormAuthorization(){
        $queryObject = new Query();
        $query = "input_form_info('client_authorization')";
        $json = $queryObject->callProcedure($query);
        debug::VD($json); 
        $form = new formCreatorClass($json, $this->queryString);
        return "Заполните форму для авторизации:<br>Она отправиться на {$this->handler}<br>"
        . $form->formCreation($this->handler );
    }
}