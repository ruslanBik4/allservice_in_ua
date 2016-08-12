<?php

class ui_fieldConstraint
{
    private $name;
    private $value;
    private $relative_html_input_name;

    private $id_constraint;
    private $changed; // flag

    /**
     * ui_fieldConstraint constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $sql = sprintf(
            "SELECT id FROM ui_input_fields_constraints WHERE name = '%s'",
            $this->name
        );
        $query = new QueryOld();
        $result = $query->runSql($sql);
        if (empty($result)) {
            $this->changed = true;
        } else {
            $this->id_constraint = result[0]['id'];
            $this->changed = false;
        }
    }

    public function toJSON()
    {
        return json_encode($this);
    }

    public function save()
    {
        if ($this->changed) {

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
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->changed = true;
    }

    /**
     * @return null
     */
    public function getRelativeHtmlInputName()
    {
        return $this->relative_html_input_name;
    }

    /**
     * @param null $relative_html_input_name
     */
    public function setRelativeHtmlInputName($relative_html_input_name)
    {
        $this->relative_html_input_name = $relative_html_input_name;
        $this->changed = true;
    }
}
