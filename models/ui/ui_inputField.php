<?php

class ui_inputField
{

    public $html_name;
    public $html_type;

    public $html_class;
    public $html_id;
    public $html_value;
    public $input_placeholder;
    public $label;
    public $db_table_name;
    public $db_field_name;
    public $npp;
    public $rule;//ui_fieldRule object

    /**
     * ui_inputField constructor.
     * @param null $id_field
     * @param null $html_name
     * @param null $html_type
     */
    public function __construct($id_field = NULL, $html_name = NULL, $html_type = NULL)
    {

        if (isset($id_field)) {
            # если передан $id_field, то ищем в базе и заполняем
            $sql = sprintf("SELECT 
                            f.html_name, 
                            f.html_type, 
                            f.html_class, 
                            f.html_id, 
                            f.html_value, 
                            f.label, 
                            f.db_table_name, 
                            f.db_field_name, 
                            f.npp, 
                            r.name as rule_name 
                            FROM ui_input_fields f
                            LEFT JOIN ui_input_fields_rules r ON r.id = f.id_ui_input_fields_rules 
                            WHERE f.id=%d",
                $id_field);
            $query = new Query();
            $result = $query->runSql($sql);
            $this->html_name = $result[0]['html_name'];
            $this->html_type = $result[0]['html_type'];
            $this->html_class = $result[0]['html_class'];
            $this->html_id = $result[0]['html_id'];
            $this->html_value = $result[0]['html_value'];
            $this->label = $result[0]['label'];
            $this->db_table_name = $result[0]['db_table_name'];
            $this->db_field_name = $result[0]['db_field_name'];
            $this->npp = $result[0]['npp'];
            $this->rule = new ui_fieldRule($result[0]['rule_name']);
            
            $this->input_placeholder = $result[0]['placeholder'] ? : 'Введите значение ' . $this->html_name ;

        } elseif (isset($html_name) AND isset($html_type)) {
            $this->html_name = $html_name;
            $this->html_type = $html_type;
        } else {
            # исключение "недостаточно параметров"
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
        if (isset($this->html_class)) {
            $arrFieldParts[] = sprintf('class="%s"', $this->html_class);
        }
        if (isset($this->html_id)) {
            $arrFieldParts[] = sprintf('id="%s"', $this->html_id);
        }
        if (isset($this->html_value)) {
            $arrFieldParts[] = sprintf('value="%s"', $this->html_value);
        }
        $arrFieldParts[] = '>';

        $html = implode(" ", $arrFieldParts);
        if (isset($this->label)) {
            $html = '<label>'.$this->label.'<br>'.$html.'</label>';
        }

        $arrFieldParts[] = '>';
        return $html;
    }
}
