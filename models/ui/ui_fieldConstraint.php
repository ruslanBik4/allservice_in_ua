<?php

class ui_fieldConstraint
{
    public $name;
    public $value;
    public $relative_html_input_name;

    /**
     * ui_fieldConstraint constructor.
     * @param $name
     * @param null $value
     * @param null $relative_html_input_name
     */
    public function __construct($name, $value = NULL, $relative_html_input_name = NULL)
    {
        $this->name = $name;
        $this->value = $value;
        $this->relative_html_input_name = $relative_html_input_name;
        # если $name нет в таблице ui_input_fields_constraints - бросить исключене "не известное ограничение поля"
    }
}
