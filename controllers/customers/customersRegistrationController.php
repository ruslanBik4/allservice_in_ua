<?php
class customersRegistrationController {

    private $handler;
    private $queryString = [];
    
    public function __construct($handler, $queryString = null) {
        $this->handler = $handler;
        
        if ($queryString)
            $this->queryString = array_merge($this->queryString, $queryString);
            
            
            
    }
    
    public function getFormRegistration() {
        
         // извлекает свойства полей таблиц для регистрации
        $queryObject = new Query();
        $query = "input_form_info('client_registration')";
        $json = $queryObject->callProcedure($query); 
        $form = new formCreatorFromJsonClass($json, $this->queryString);
        $print = "<head><script src='../../web/js/registration_validate.js'></script>
<script src='https://code.jquery.com/jquery-1.12.0.min.js'></script>
<script src='https://code.jquery.com/jquery-migrate-1.2.1.min.js'></script></head><body>";
        return "$print Для регистрации заполните форму:<br>Она отправиться на {$this->handler}<br>"
         . $form->formCreation($this->handler );
   }
    
 }