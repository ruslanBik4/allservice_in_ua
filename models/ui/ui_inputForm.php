<?php

class ui_inputForm
{
    public $html_name;
    public $html_id;
    public $js_func_onsubmit;
    public $action;
    public $description;
    public $fields; // array ui_inputField object

    private $form_id;

    /**
     * ui_inputForm constructor.
     * @param $form_name
     */
    public function __construct($form_name)
    {
        $sql = sprintf("SELECT id, html_name, html_id, js_func_onsubmit, action, description 
FROM ui_input_forms 
WHERE html_name='%s'",
            $form_name);
        $query = new Query();
        $result = $query->runSql($sql);
        $this->html_name = $result[0]['html_name'];
        $this->html_id = $result[0]['html_id'];
        /*
                #удаить пробелы и скобки
                $this->js_func_onsubmit = str_replace(
                    [' ','(',')'],
                    '',
                    $result[0]['js_func_onsubmit']
                );
          */
        #удаить пробелы и скобки
        $this->js_func_onsubmit = trim(explode('(', $result[0]['js_func_onsubmit'])[0]);

        $this->action = $result[0]['action'];
        $this->description = $result[0]['description'];
        $this->form_id = $result[0]['id'];

        $this->fields = [];
        $sql = sprintf("SELECT id FROM ui_input_fields WHERE id_ui_input_forms=%d", $this->form_id);
        $result = $query->runSql($sql);
        foreach ($result as $field) {
            $this->fields[] = new ui_inputField($field['id']);
        }

    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $html = '';
        $arrFormParts = [];

        $arrFormParts[] = '<form';
        if (isset($this->html_name)) {
            $arrFormParts[] = sprintf('name="%s"', $this->html_name);
        }
        if (isset($this->html_id)) {
            $arrFormParts[] = sprintf('id="%s"', $this->html_id);
        }

        $arrFormParts[] = 'method="POST"';
        $arrFormParts[] = '>';
        $html = implode(' ', $arrFormParts);

        foreach ($this->fields as $field) {
            $html .= '<p>' . $field->toHtml() . '</p>';
        }
        if (isset($this->js_func_onsubmit)){
            $html .= sprintf(
                '<p><input type="button" value="Отправить" onclick="%s">',
                $this->js_func_onsubmit . "($('form'), '" . $this->action . "')"
            );
        }

        $html .= '</form>';

        return $html;
    }
}