<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';

require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/common.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Order.php';
require_once $rootPath . 'models/Customer.php';
require_once $rootPath . 'common/Carbon.php';

$pendingOrders = \models\Order::where('status', PENDING_STATUS)->findMany();
$pendingOrderArray = array();

foreach($pendingOrders as $order) {
    $customer = \models\Customer::findOne($order->customer_id);
    $orderAsArray = $order->asArray();
    $orderAsArray['customerFirstName'] = $customer->firstName;
    $orderAsArray['customerLastName'] = $customer->lastName;
    array_push($pendingOrderArray, $orderAsArray);
}

echo json_encode($pendingOrderArray);