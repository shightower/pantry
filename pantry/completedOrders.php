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

$completedOrders = \models\Order::where('status', COMPLETE_STATUS)->order_by_desc('orderDate')->findMany();
$completedOrderArray = array();

foreach($completedOrders as $order) {
    $customer = \models\Customer::findOne($order->customer_id);
    $orderAsArray = $order->asArray();
    $orderAsArray['customerFirstName'] = $customer->firstName;
    $orderAsArray['customerLastName'] = $customer->lastName;
    array_push($completedOrderArray, $orderAsArray);
}

echo json_encode($completedOrderArray);