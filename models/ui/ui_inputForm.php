<?php

class ui_inputForm
{
    private $id_form;
    private $html_name;
    private $html_id;
    private $action;
    private $description;
    private $fields = []; // array ui_inputField object

    private $changed; // flag
    private $query; // Query object

    /**
     * ui_inputForm constructor.
     * @param $form_name
     */
    public function __construct($form_name)
    {
        $this->query = new Query();
        $this->html_name = $form_name;
        $sql = sprintf("SELECT id, html_name, html_id, action, description 
FROM ui_input_forms 
WHERE html_name='%s'",
            $this->html_name);
        //$query = new QueryOld();
        $result = $this->query->runSql($sql);
        if (empty($result)) {
            # новая форма, ее в базе еще нет.
            $this->changed = true;
        } else {
            $form = &$result[0]; // В $result должна бять одна запись.
            $this->html_id = $form['html_id'];
            $this->action = $form['action'];
            $this->action = 'showPOST.php'; // **************** для теста
            $this->description = $form['description'];
            $this->id_form = $form['id'];
            //$this->fields = $this->load_fields();
            $this->load_fields();
            $this->changed = false;
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

        $arrFormParts[] = '>';
        $html = implode(' ', $arrFormParts);

        foreach ($this->fields as $field) {
            $html .= '<p>' . $field->toHtml() . '</p>';
        }

        $html .= sprintf(
            '<p><input type="button" value="Отправить" onclick="%s">',
            "send_form($('form'), '" . $this->action . "')"
        );
        $html .= '</form>';
        return $html;
    }

    private function load_fields()
    {
        if (!empty($this->id_form)) {
            $sql = sprintf(
                "SELECT id FROM ui_input_fields WHERE id_ui_input_forms=%d",
                $this->id_form
            );
            //$query = new QueryOld();
            $result = $this->query->runSql($sql);
            foreach ($result as $field) {
                $this->fields[$field['id']] = new ui_inputField($field['id']);
            }
        }
    }

    public function addField(ui_inputField $field)
    {
        $this->fields[] = $field;
        $this->changed = true;
    }

    public function save()
    {
        if ($this->changed) {

        }
    }

    /**
     * @return mixed
     */
    public function getHtmlName()
    {
        return $this->html_name;
    }

    /**
     * @param mixed $html_name
     */
    public function setHtmlName($html_name)
    {
        $this->html_name = $html_name;
        $this->changed = true;
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
        $this->changed = true;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
        $this->changed = true;
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
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return mixed
     */
    public function getIdForm()
    {
        return $this->id_form;
    }

}