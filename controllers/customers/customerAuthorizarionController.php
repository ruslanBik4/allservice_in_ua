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
        $queryObject = new QueryOld();
        $query = "input_form_info('client_authorization')";
        $json = $queryObject->callProcedure($query);
        debug::VD($json);
    }

}