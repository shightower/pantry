<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pantry/common/idiorm.php';

$configs = parse_ini_file('configs.ini');

ORM::configure('mysql:host=' . $configs['host'] . ';dbname=' . $configs['dbname']);
ORM::configure('username', $configs['username']);
ORM::configure('password', $configs['password']);
ORM::configure('error_mode', PDO::ERRMODE_WARNING);
ORM::configure('return_result_sets', true);
ORM::configure('logging', true);

unset($configs);
