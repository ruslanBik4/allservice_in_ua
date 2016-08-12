<?php

class ui_fieldRule
{

    private $rule_name;
    private $description;
    private $constraints; //array ui_fieldConstraint object

    private $id_rule;
    private $changed; // flag

    /**
     * fieldRule constructor.
     * @param $rule_name
     */
    public function __construct($rule_name)
    {
        $this->rule_name = $rule_name;
        # проверить есть ли правило с таким именем. если нет то это новое
        $sql = sprintf(
            "SELECT id FROM ui_input_fields_rules WHERE name = '%s'",
            $this->rule_name
        );
        $query = new Query();
        $result = $query->runSql($sql);
        # если $result пустой значит это новое правило
        if (empty($result)) {
            $this->changed = true;
        } else {
            $this->id_rule = $result[0]['id'];
            $this->constraints = $this->load_constraints();
            $this->changed = false;
        }
    }

    /**
     * @param string $name
     */
    public function addConstraint($name)
    {
        $this->constraints[$name] = new ui_fieldConstraint($name);
    }


    /**
     * @param string $name
     */
    public function delConstraint($name)
    {
        unset($this->constraints[$name]);
    }


    private function load_constraints()
    {
        $constraints = [];
        $sql = sprintf("SELECT c.name, rc.value, rc.relative_html_input_name 
FROM ui_input_fields_rules_constraints rc 
JOIN ui_input_fields_rules r ON r.id = rc.id_ui_input_fields_rules AND r.name = '%s' 
JOIN  ui_input_fields_constraints c ON c.id = rc.id_ui_input_fields_constraints",
            $this->rule_name);

        $query = new Query();
        $result = $query->runSql($sql);
        foreach ($result as $row) {
            $constraints[$row['name']] = $row;
        }
        return $constraints;
    }

    public function save()
    {
        if ($this->changed) {
            if (empty($this->id_rule)){
                #insert
            }else{
                #update
            }
        }
        foreach($this->constraints as $constraint){
            if ($constraint->isChanged()){
                
            }
        }
    }

    /**
     * @return mixed
     */
    public function getRuleName()
    {
        return $this->rule_name;
    }

    /**
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @return mixed
     */
    public function getIdRule()
    {
        return $this->id_rule;
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


}