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
    const IS_ATTENDEE = "1";
    const DATE_FORMAT = "D, d M Y H:i:s O";

    public function addOrder() {
        $customerId = $_POST['customerId'];
        $orderType = strtoupper($_POST['type']);

        if(!$this->hasPendingOrder($customerId, $orderType)) {
            $newOrder = ORM::for_table('Order')->create();
            $newOrder->status = PENDING_STATUS;
            $newOrder->customer_id = $customerId;
            $newOrder->orderDate = Carbon::now();
            $newOrder->type = $orderType;
            $customer = \models\Customer::findOne($customerId);
            $totalPeople = $customer->numKids + $customer->numAdults;

            if($orderType == OrderService::REGULAR_ORDER_TYPE) {
                if($newOrder->orderDate->dayOfWeek == Carbon::TUESDAY || $newOrder->orderDate->dayOfWeek == Carbon::SATURDAY) {
                    if($totalPeople <= 3) {
                        $newOrder->numBags = 2;
                    } else if($totalPeople > 3 && $totalPeople <= 5) {
                        $newOrder->numBags = 3;
                    } else {
                        $newOrder->numBags = 4;
                    }
                } else {
                    if($totalPeople <= 3) {
                        $newOrder->numBags = 1;
                    } else if($totalPeople > 3 && $totalPeople <= 5) {
                        $newOrder->numBags = 2;
                    } else {
                        $newOrder->numBags = 3;
                    }
                }
            }

            $newOrder->save();
        } else {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL']." 400 User already has pending order.");
        }
    }

    public function getPendingOrders() {
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
        exit();
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

            if($pendingOrder->type === OrderService::REGULAR_ORDER_TYPE) {
                $pendingOrder->numBags = $_POST['numBags'];
                $pendingOrder->save();

                // Update the Customer's next available order date
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

    public function deletePendingOrder() {
        $orderId = $_POST['id'];
        $pendingOrder = \models\Order::where(array(
            'id' => $orderId,
            'status' => PENDING_STATUS
        ))->findOne();

        if($pendingOrder != null && $pendingOrder != false) {
            $pendingOrder->delete();
        } else {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL']." 400 Could not find order, or order already completed.");
        }

    }

    public function generateRegularOrderReport() {
        $startDate = Carbon::createFromFormat(OrderService::DATE_FORMAT, $_POST['startDate']);
        $startDate->hour = 0;
        $startDate->minute = 0;
        $startDate->second = 0;

        $endDate = Carbon::createFromFormat(OrderService::DATE_FORMAT, $_POST['endDate']);
        $endDate->hour = 23;
        $endDate->minute = 59;
        $endDate->second = 59;

        $reportInfo = array();

        $completedOrders = \models\Order::where(array(
            'status' => COMPLETE_STATUS,
            'type'=> OrderService::REGULAR_ORDER_TYPE
        ))->where_raw('(orderDate between ? and ?)', array($startDate, $endDate))->findMany();

        $reportInfo['totalWeight'] = 0;
        $reportInfo['totalKids'] = 0;
        $reportInfo['totalAdults'] = 0;
        $reportInfo['totalFamilies'] = 0;
        $reportInfo['totalBccAttendees'] = 0;
        $reportInfo['totalNonBccAttendees'] = 0;

        foreach($completedOrders as $order) {
            $reportInfo['totalFamilies'] += 1;
            $reportInfo['totalWeight'] += $order->orderWeight;
            $customer = \models\Customer::findOne($order->customer_id);
            $reportInfo['totalAdults'] += $customer->numAdults;
            $reportInfo['totalKids'] += $customer->numKids;

            if($customer->isAttendee === OrderService::IS_ATTENDEE) {
                $reportInfo['totalBccAttendees'] += 1;
            } else {
                $reportInfo['totalNonBccAttendees'] += 1;
            }
        }

        echo json_encode($reportInfo);
        exit();
    }

    public function generateTefapOrderReport() {
        $startDate = Carbon::createFromFormat(OrderService::DATE_FORMAT, $_POST['startDate']);
        $startDate->hour = 0;
        $startDate->minute = 0;
        $startDate->second = 0;

        $endDate = Carbon::createFromFormat(OrderService::DATE_FORMAT, $_POST['endDate']);
        $endDate->hour = 23;
        $endDate->minute = 59;
        $endDate->second = 59;

        $reportInfo = array();

        $completedOrders = \models\Order::where(array(
            'status' =>COMPLETE_STATUS,
            'type' => OrderService::TEFAP_ORDER_TYPE
        ))->where_raw('(orderDate between ? and ?)', array($startDate, $endDate))->findMany();

        $reportInfo['totalWeight'] = 0;
        $reportInfo['totalKids'] = 0;
        $reportInfo['totalAdults'] = 0;
        $reportInfo['totalFamilies'] = 0;
        $reportInfo['tefapCount'] = 0;
        $reportInfo['totalBccAttendees'] = 0;
        $reportInfo['totalNonBccAttendees'] = 0;

        foreach($completedOrders as $order) {
            $reportInfo['totalFamilies'] += 1;
            $reportInfo['totalWeight'] += $order->orderWeight;
            $customer = \models\Customer::findOne($order->customer_id);
            $reportInfo['totalAdults'] += $customer->numAdults;
            $reportInfo['totalKids'] += $customer->numKids;
            $reportInfo['tefapCount'] += $order->tefapCount;

            if($customer->isAttendee === OrderService::IS_ATTENDEE) {
                $reportInfo['totalBccAttendees'] += 1;
            } else {
                $reportInfo['totalNonBccAttendees'] += 1;
            }
        }

        echo json_encode($reportInfo);
        exit();
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