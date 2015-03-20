<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';
require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Customer.php';

$customers = \models\Customer::findMany();
$customerArray = array();

foreach($customers as $customer) {
    array_push($customerArray, $customer->asArray());
}

echo json_encode($customerArray);