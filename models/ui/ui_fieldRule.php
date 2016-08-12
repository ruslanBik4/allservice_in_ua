<?php

class ui_fieldRule
{

    private $rule_name;
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
        $query = new QueryOld();
        $result = $query->runSql($sql);
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
     * @param string|NULL $value
     * @param string|NULL $relative_html_input_name
     */
    public function addConstraint($name, $value = NULL, $relative_html_input_name = NULL)
    {
        $this->constraints[$name] = new ui_fieldConstraint($name, $value, $relative_html_input_name);
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

        $query = new QueryOld();
        $result = $query->runSql($sql);
        foreach ($result as $row) {
            $constraints[] = $row;
        }
        return $constraints;
    }

    public function save()
    {
        if ($this->changed) {

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


}