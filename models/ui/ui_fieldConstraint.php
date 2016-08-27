<?php

class ui_fieldConstraint
{
    private $id_constraint;
    private $id_ui_input_fields_rules;
    private $name;
    private $description;
    private $value;
    private $relative_html_input_name;

    private $id_ui_input_fields_constraints;
    private $changed; // bool flag
    private $query; // Query object


    /**
     * ui_fieldConstraint constructor.
     * @param null $id
     */
    public function __construct($id = NULL)
    {
        $this->query = new Query();
        # если $id не NULL то должен быть в базе. Если нет - исключение.
        if (!empty($id)) {
            $this->id_constraint = $id;
            $sql = sprintf(
                "SELECT 
rc.id, 
fc.id_ui_input_fields_constraints, 
fc.name, 
fc.description, 
rc.value, 
rc.relative_html_input_name 
 FROM ui_input_fields_rules_constraints rc 
 JOIN ui_input_fields_constraints fc ON fc.id = rc.id_ui_input_fields_constraints 
 WHERE fc.id=%d",
                $this->id_constraint
            );
            //$query = new QueryOld();
            $result = $this->query->runSql($sql);
            if (empty($result)) {
                throw new Exception('ui_fieldConstraint id=' . $this->id_constraint . ' не найден');
            } else {
                $constraint =& $result[0]; // В $result должна бять одна запись.
                $this->id_ui_input_fields_rules = $constraint['id_ui_input_fields_rules'];
                $this->id_ui_input_fields_constraints = $constraint['id_ui_input_fields_constraints'];
                $this->name = $constraint['name'];
                $this->description = $constraint['description'];
                $this->value = $constraint['value'];
                $this->relative_html_input_name = $constraint['relative_html_input_name'];
            }
        }
    }

    public function save(){
    # сохраняем если были изменения
        if ($this->changed){
            # name должно иметь значение
            if (empty($this->name)){
                throw new Exception('Невозможно сохранить ui_fieldConstraint. name=NULL');
            }else{
                # id_ui_input_fields_rules должно иметь значение
                if (empty($this->id_ui_input_fields_rules)){
                    throw new Exception('Невозможно сохранить ui_fieldConstraint. id_ui_input_fields_rules=NULL');
                }else{
                    # Если известен $id - update
                    if (!empty($this->id_constraint)){
                        $this->update();
                    }else{
                        # возможно инициализировали с id=NULL
                        # но свойствам присвоили такие значения, что в базе есть такая запись
                        $sql = sprintf(
                            "SELECT rc.id 
 FROM ui_input_fields_rules_constraints rc 
 JOIN ui_input_fields_constraints fc ON fc.id = rc.id_ui_input_fields_constraints 
 WHERE fc.name='%s' AND rc.id_ui_input_fields_rules = %d",
                            $this->name, $this->id_ui_input_fields_rules
                        );
                        $result = $this->query->runSql($sql);
                        if (empty($result)){
                            $this->insert();
                        }else{
                            $this->id_constraint = $result[0]['id'];
                            $this->update();
                        }
                    }
                    
                    # если нет, искать по name,id_ui_input_fields_rules
                    #   нашли - update, не нашли - Insert
                }
            }
        }
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this);
    }

      private function update(){
        $values = [];
        $values['id_ui_input_fields_rules'] = $this->id_ui_input_fields_rules;
        $values['id_ui_input_fields_constraints'] = $this->id_ui_input_fields_constraints;
        $values['value'] = $this->value;
        $values['relative_html_input_name'] = $this->relative_html_input_name;
        $where="id = ".$this->id_constraint;
        $this->query->runUpdate('ui_input_fields_rules_constraints',$values, $where);
    }

    /**
     * @throws Exception
     */
    private function insert(){
        $values = [];
        $values['id_ui_input_fields_rules'] = $this->id_ui_input_fields_rules;
        $values['id_ui_input_fields_constraints'] = $this->id_ui_input_fields_constraints;
        $values['value'] = $this->value;
        $values['relative_html_input_name'] = $this->relative_html_input_name;
        $lastId = $this->query->runInsert('ui_input_fields_rules_constraints',$values);
        if ($lastId>0){
            $this->id_constraint=$lastId;
        }else{
            throw new Exception('Ошибка вставки ');
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        # name должно существовать в ui_input_fields_rules
        $sql = sprintf("SELECT id FROM ui_input_fields_rules WHERE name='%s'",$name);
        $result = $this->query->runSql($sql);
        if (!empty($result)){
            $this->name = $name;
            $this->id_ui_input_fields_constraints = $result[0]['id_ui_input_fields_constraints'];
            $this->changed = true;
        }else{
            throw new Exception('Невозможно присвоить name='.$name. ' отсутствует в ui_input_fields_rules');
        }
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->changed = true;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->changed = true;
    }

    /**
     * @return mixed
     */
    public function getRelativeHtmlInputName()
    {
        return $this->relative_html_input_name;
    }

    /**
     * @param mixed $relative_html_input_name
     */
    public function setRelativeHtmlInputName($relative_html_input_name)
    {
        $this->relative_html_input_name = $relative_html_input_name;
        $this->changed = true;
    }

    /**
     * @return mixed
     */
    public function getIdUiInputFieldsRules()
    {
        return $this->id_ui_input_fields_rules;
    }

    /**
     * @param mixed $id_ui_input_fields_rules
     */
    public function setIdUiInputFieldsRules($id_ui_input_fields_rules)
    {
        $this->id_ui_input_fields_rules = $id_ui_input_fields_rules;
        $this->changed = true;
    }

    /**
     * @return mixed
     */
    public function getChanged()
    {
        return $this->changed;
    }

}
