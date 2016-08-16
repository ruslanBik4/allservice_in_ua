<?php
#session_start();
#include_once 'conf.php';
require_once '../../models/autoload.php';
include_once 'functions.php';

if ( array_key_exists("cmd", $_REQUEST)) {
    $cmd = $_REQUEST['cmd'];
    $args = ( array_key_exists("args", $_REQUEST))?$_REQUEST['args']:[];
    switch ($cmd){
        case 'load_input_forms':
#        case 'add_comment':
#        case 'get_page':
#        case 'login':
#        case 'logout':
#            include './cmd/'.$cmd.'.php';
            //echo call_user_func($cmd, $args);
            break;
    }
}
