<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';

require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Order.php';
require_once $rootPath . 'models/Customer.php';
require_once $rootPath . 'common/Carbon.php';

use \Carbon\Carbon;

class OrderService {

    const PENDING_STATUS = 'PENDING';
    const COMPLETE_STATUS = 'COMPLETE';

    public function addOrder() {
        $customerId = $_POST['customerId'];
        $orderType = strtoupper($_POST['type']);

        if(!$this->hasPendingOrder($customerId, $orderType)) {
            $newOrder = ORM::for_table('Order')->create();
            $newOrder->status = OrderService::PENDING_STATUS;
            $newOrder->customer_id = $customerId;
            $newOrder->orderDate = Carbon::now();
            $newOrder->type = $orderType;
            $newOrder->save();
        } else {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL']." 400 User already has pending order.");
        }
    }

    private function hasPendingOrder($customerId, $orderType) {
        $hasPending = false;
        $count = \models\Order::where(array(
            'customer_id' => $customerId,
            'type' => $orderType,
            'status' => OrderService::PENDING_STATUS
        ))->count();

        if($count > 0) {
            $hasPending = true;
        }

        return $hasPending;
    }
}