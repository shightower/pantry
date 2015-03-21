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

use \Carbon\Carbon;

class OrderService {
    const REGULAR_ORDER_TYPE = "REGULAR";
    const TEFAP_ORDER_TYPE = "TEFAP";

    public function addOrder() {
        $customerId = $_POST['customerId'];
        $orderType = strtoupper($_POST['type']);

        if(!$this->hasPendingOrder($customerId, $orderType)) {
            $newOrder = ORM::for_table('Order')->create();
            $newOrder->status = PENDING_STATUS;
            $newOrder->customer_id = $customerId;
            $newOrder->orderDate = Carbon::now();
            $newOrder->type = $orderType;
            $newOrder->save();
        } else {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL']." 400 User already has pending order.");
        }
    }

    public function completeOrder() {

        //first check to make sure a valid order number is provided and it is still pending.
        $orderId = $_POST['id'];
        $pendingOrder = \models\Order::where(array(
            'id' => $orderId,
            'status' => PENDING_STATUS
        ))->findOne();

        if($pendingOrder != null && $pendingOrder != false) {
            $pendingOrder->orderWeight = $_POST['orderWeight'];
            $pendingOrder->status = COMPLETE_STATUS;
            $orderType = $pendingOrder->type;

            if($pendingOrder->type === OrderService::REGULAR_ORDER_TYPE) {
                $pendingOrder->numBags = $_POST['numBags'];
                $pendingOrder->save();

                //Upate the Customer's next availble order date
                $customer = \models\Customer::findOne($pendingOrder->customer_id);
                $nextAvailableDate = Carbon::now()->addDays(WAIT_PERIOD_DAYS);
                $customer->lastOrderDate = Carbon::now();
                $customer->nextAvailableDate = $nextAvailableDate;
                $customer->save();
            } else if($pendingOrder->type === OrderService::TEFAP_ORDER_TYPE) {
                $pendingOrder->tefapCount = $_POST['tefapCount'];
                $pendingOrder->save();
            }
        } else {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL']." 400 Could not find order, or order already completed.");
        }
    }

    private function hasPendingOrder($customerId, $orderType) {
        $hasPending = false;
        $count = \models\Order::where(array(
            'customer_id' => $customerId,
            'type' => $orderType,
            'status' => PENDING_STATUS
        ))->count();

        if($count > 0) {
            $hasPending = true;
        }

        return $hasPending;
    }
}