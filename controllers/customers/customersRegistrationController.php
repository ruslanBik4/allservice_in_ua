<?php
class customersRegistrationController {

    // значение атрибута action тега form
    private $actionAttribute;

    // массив GET
    private $getParams = [];

    /**
     * Конструктор принимает $actionAttribute - значение атрибута action тега form,
     * записывает в actionAttribute
     * customersRegistrationController constructor.
     * @param $actionAttribute
     * @param null $getParams
     */
    public function __construct($actionAttribute, $getParams = null) {
        $this->actionAttribute = $actionAttribute;
        if ($getParams)
            $this->getParams = array_merge($this->getParams, $getParams);

        // Если $getParams['signin'] есть, значит форма была заполнена, небходимо создать нового пользователя
        // В противном случае необходимо отобразить форму для регистрации
        if(isset($getParams['signin'])){
            $newUser = new prepareAndRunRequest();
        } else {
            echo $this->getFormRegistration();
        }
    }


    /**
     * Метод извлекает данные требуемой таблицы и отправляет на создание в класс formCreatorClass
     * Возвращает описание формы и HTML разметку самой формы для вставки на страницу
     * @return string
     */
    public function getFormRegistration() {
        $queryObject = new Query();
        $query = "input_form_info('client_registration')";

        // получаем данные для рисования формы, вида
        //array(16) {["action"],["constraint_name"],["constraint_value"],["db_field_name"],["db_table_name"],["form_html_id"],
        //["form_html_name"],["html_class"],["html_id"],"html_name"],["html_placeholder"],["html_type"],["html_value"],
        //["js_func_onsubmit"],["label"],["relative_html_input_name"]}
        $data = $queryObject->callProcedure($query);

        // в formCreatorClass передаем данные полей таблицы и массив GET
        $form = new formCreatorClass($data, $this->getParams);
        return "Для регистрации заполните форму:<br>Она отправиться на {$this->actionAttribute}<br>". $form->formCreation($this->actionAttribute );
   }
    
 }  