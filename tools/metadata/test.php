#!/usr/bin/php
<?php
require_once '../../models/autoload.php';



    $query = new Query();
#    $sql = "SELECT id, html_name, html_id, action, description FROM ui_input_forms";
    $sql = "SELECT id FROM ui_input_forms";
    $result = $query->runSql($sql);
    var_dump($result);
#    foreach($result as $form){
#        var_dump($form);
#    }
