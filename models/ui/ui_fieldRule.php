<?php

class ui_fieldRule
{

    public $rule_name;
    private $constraints; //array ui_fieldConstraint object

    /**
     * fieldRule constructor.
     * @param $rule_name
     */
    public function __construct($rule_name)
    {
        $this->rule_name = $rule_name;
        $this->constraints = $this->load_constraints($this->rule_name);
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


    private function load_constraints($rule_name)
    {
        $sql = sprintf("SELECT c.name, rc.value, rc.relative_html_input_name 
FROM ui_input_fields_rules_constraints rc 
JOIN ui_input_fields_rules r ON r.id = rc.id_ui_input_fields_rules AND r.name = '%s' 
JOIN  ui_input_fields_constraints c ON c.id = rc.id_ui_input_fields_constraints",
            $rule_name);

        $query = new Query();
        return $query->runSql($sql);
    }


}