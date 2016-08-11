<?php

class ui_inputField
{

    private $html_name;
    private $html_type;

    private $html_class;
    private $html_id;
    private $html_value;
    private $html_placeholder;
    private $label;
    private $db_table_name;
    private $db_field_name;
    private $npp;
    private $rule;//ui_fieldRule object
    
    private $id_field;
    private $changed; // flag

    /**
     * ui_inputField constructor.
     * @param null $id_field
     */
    public function __construct($id_field = NULL)
    {
        if (isset($id_field)) {
            $this->id_field = $id_field;
            # если передан $id_field, то ищем в базе и заполняем свойства
            $sql = sprintf("SELECT 
                            f.html_name, 
                            f.html_type, 
                            f.html_class, 
                            f.html_id, 
                            f.html_value, 
                            f.html_placeholder, 
                            f.label, 
                            f.db_table_name, 
                            f.db_field_name, 
                            f.npp, 
                            r.name as rule_name, 
                            r.id as rule_id  
                            FROM ui_input_fields f
                            LEFT JOIN ui_input_fields_rules r ON r.id = f.id_ui_input_fields_rules 
                            WHERE f.id=%d ORDER BY f.npp",
                $this->id_field);
            $query = new Query();
            $result = $query->runSql($sql);
            if(empty($result)){
                # исключение id не найден
            }else{
                $this->html_name = $result[0]['html_name'];
                $this->html_type = $result[0]['html_type'];
                $this->html_class = $result[0]['html_class'];
                $this->html_id = $result[0]['html_id'];
                $this->html_value = $result[0]['html_value'];
                $this->label = $result[0]['label'];
                $this->db_table_name = $result[0]['db_table_name'];
                $this->db_field_name = $result[0]['db_field_name'];
                $this->npp = $result[0]['npp'];
                $this->html_placeholder = $result[0]['html_placeholder'] ?: 'Введите значение ' . $this->html_name;
                $this->rule = new ui_fieldRule($result[0]['rule_name']);
                $this->changed=false;
            }
        }
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $html = '';
        $arrFieldParts = [];

        $arrFieldParts[] = '<input';
        if (isset($this->html_name)) {
            $arrFieldParts[] = sprintf('name="%s"', $this->html_name);
        }
        if (isset($this->html_type)) {
            $arrFieldParts[] = sprintf('type="%s"', $this->html_type);
        }
        if (isset($this->html_value)) {
            $arrFieldParts[] = sprintf('value="%s"', $this->html_value);
        }

        if ($this->html_type !== 'hidden') {
            if (isset($this->html_placeholder)) {
                if (in_array($this->html_type, ['text', 'password', 'textarea'])) {
                    $arrFieldParts[] = sprintf('placeholder="%s"', $this->html_placeholder);
                }
            }
            if (isset($this->html_class)) {
                $arrFieldParts[] = sprintf('class="%s"', $this->html_class);
            }
            if (isset($this->html_id)) {
                $arrFieldParts[] = sprintf('id="%s"', $this->html_id);
            }
        }
        $arrFieldParts[] = sprintf('data-db_table_name="%s"', $this->db_table_name);
        $arrFieldParts[] = sprintf('data-db_field_name="%s"', $this->db_field_name);
//        $str_field_rules = str_replace('"','&quot;',json_encode($this->constraints));
        $str_field_rules = str_replace('"','&quot;',json_encode($this->rule->getConstraints()));
        $arrFieldParts[] = sprintf('data-field_constraints="%s"',$str_field_rules );
        $arrFieldParts[] = '>';

        $html = implode(" ", $arrFieldParts);
        if (isset($this->label) AND $this->html_type !== 'hidden') {
            $html = '<label>' . $this->label . '<br>' . $html . '</label>';
        }

        $arrFieldParts[] = '>';
        return $html;
    }

    public function save()
    {
        if ($this->changed) {

        }
    }

    /**
     * @return null
     */
    public function getHtmlName()
    {
        return $this->html_name;
    }

    /**
     * @param null $html_name
     */
    public function setHtmlName($html_name)
    {
        $this->html_name = $html_name;
        $this->changed=true;
    }

    /**
     * @return null
     */
    public function getHtmlType()
    {
        return $this->html_type;
    }

    /**
     * @param null $html_type
     */
    public function setHtmlType($html_type)
    {
        $this->html_type = $html_type;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getHtmlClass()
    {
        return $this->html_class;
    }

    /**
     * @param mixed $html_class
     */
    public function setHtmlClass($html_class)
    {
        $this->html_class = $html_class;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getHtmlId()
    {
        return $this->html_id;
    }

    /**
     * @param mixed $html_id
     */
    public function setHtmlId($html_id)
    {
        $this->html_id = $html_id;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getHtmlValue()
    {
        return $this->html_value;
    }

    /**
     * @param mixed $html_value
     */
    public function setHtmlValue($html_value)
    {
        $this->html_value = $html_value;
        $this->changed=true;
    }

    /**
     * @return string
     */
    public function getHtmlPlaceholder()
    {
        return $this->html_placeholder;
    }

    /**
     * @param string $html_placeholder
     */
    public function setHtmlPlaceholder($html_placeholder)
    {
        $this->html_placeholder = $html_placeholder;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getDbTableName()
    {
        return $this->db_table_name;
    }

    /**
     * @param mixed $db_table_name
     */
    public function setDbTableName($db_table_name)
    {
        $this->db_table_name = $db_table_name;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getDbFieldName()
    {
        return $this->db_field_name;
    }

    /**
     * @param mixed $db_field_name
     */
    public function setDbFieldName($db_field_name)
    {
        $this->db_field_name = $db_field_name;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getNpp()
    {
        return $this->npp;
    }

    /**
     * @param mixed $npp
     */
    public function setNpp($npp)
    {
        $this->npp = $npp;
        $this->changed=true;
    }

    /**
     * @return mixed
     */
    public function getIdField()
    {
        return $this->id_field;
    }


}
