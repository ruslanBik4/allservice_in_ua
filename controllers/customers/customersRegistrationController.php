<?php
class customersRegistrationController {

    private $handler;
    private $queryString = [];
    
    public function __construct($handler, $queryString = null) {
        $this->handler = $handler;
        
        if ($queryString)
            $this->queryString = array_merge($this->queryString, $queryString);
            
            
            
    }

    /**
     * Метод для отрисовки формы регистрации
     * @return string
     */
    public function getFormRegistration() {
        
         // извлекает свойства полей таблиц для регистрации
        $queryObject = new QueryOld();
        $query = "input_form_info('client_registration')";
        $json = $queryObject->callProcedure($query);
        $form = new formCreatorFromJsonClass($json, $this->queryString);
        return "Для регистрации заполните форму:<br>Она отправиться на {$this->handler}<br>"
         . $form->formCreation($this->handler );
   }
    
 }  