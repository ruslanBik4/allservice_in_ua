<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.08.2016
 * Time: 23:50
 */
require_once '../../autoload.php';

$f = new ui_inputForm('client_registration');
echo $f->toHtml();