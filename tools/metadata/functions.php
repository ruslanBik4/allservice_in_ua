<?php
function load_input_forms($args)
{
    $query = new Query();
    $sql = "SELECT id, html_name, html_id, action, description FROM ui_input_forms";
    $result = $query->runSql($sql);
    foreach($result as $form){
        var_dump($form);
    }
}