<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 10.09.2016
 * Time: 16:30
 */
class customersOfficeController
{
    private $actionAttribute;

    private $getParams = [];

    public function __construct($getParams = null)
    {
        if ($getParams)
            $this->getParams = array_merge($this->getParams, $getParams);

        $queryObject = new Query();
        $query = "input_form_info('service_add')";
        $data = $queryObject->callProcedure($query);
        //var_dump($data);
        $this->actionAttribute = $data[0]['action'];

        $form = new formCreatorClass($data, $this->getParams);

        echo  "Форма:<br>Она отправиться на {$this->actionAttribute}<br>". $form->formCreation($this->actionAttribute );

        $sql = "SELECT * FROM ref_services";

        var_dump($_POST);
        
        if(!empty($_POST))
        {
            $newOffice = new prepareAndRunRequest('service_add');
        }

        echo '<pre>';
        print_r($queryObject->runSql($sql));
        echo '</pre>';
    }

}