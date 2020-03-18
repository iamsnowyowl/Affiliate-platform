<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'Pre_controller',
    'function' => 'initialize',
    'filename' => 'Pre_controller.php',
    'filepath' => 'hooks',
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Audit_trail',
    'function' => 'init_audit',
    'filename' => 'Audit_trail.php',
    'filepath' => 'hooks',
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Authorization',
    'function' => 'on_check_authorization',
    'filename' => 'Authorization.php',
    'filepath' => 'hooks',
);

$hook['post_system'][] = array(
    'class'    => 'Audit_trail',
    'function' => 'save_audit',
    'filename' => 'Audit_trail.php',
    'filepath' => 'hooks',
);

$hook['pre_system'][] = array(
    'class'    => 'Environment',
    'function' => 'load',
    'filename' => 'Environment.php',
    'filepath' => 'hooks',
);