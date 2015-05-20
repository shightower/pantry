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
            $returnObj = array();
            $returnObj['id'] = $newOrder->id;
            echo json_encode($returnObj);
            exit();
        } else {
            http_response_code(400);
            header($_SERVER['SERVER_PROTOCOL']." 400 User already has pending order.");
        }
    }

    public function getPendingOrders() {
        $orderType = $_GET['type'];
        $orderType = strtoupper($orderType);
        $pendingOrders = \models\Order::where('status', PENDING_STATUS)->where('type', $orderType)->findMany();
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

    public function getCompletedOrders() {
        $orderType = $_GET['type'];
        $orderType = strtoupper($orderType);
        $completedOrders = \models\Order::where('status', COMPLETE_STATUS)->where('type', $orderType)->order_by_desc('orderDate')->findMany();
        $completedOrderArray = array();

        foreach($completedOrders as $order) {
            $customer = \models\Customer::findOne($order->customer_id);
            $orderAsArray = $order->asArray();
            $orderAsArray['customerFirstName'] = $customer->firstName;
            $orderAsArray['customerLastName'] = $customer->lastName;
            array_push($completedOrderArray, $orderAsArray);
        }

        echo json_encode($completedOrderArray);
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
                $today = Carbon::now();
                $customer = \models\Customer::findOne($pendingOrder->customer_id);
                if($today->dayOfWeek != Carbon::SUNDAY) {
                    $nextAvailableDate = Carbon::now()->addDays(WAIT_PERIOD_DAYS);
                    $customer->nextAvailableDate = $nextAvailableDate;
                }

                $customer->lastOrderDate = $today;
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

    public function generateRegularOrderReportSummary() {
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
        $reportInfo['totalBags'] = 0;

        $ethnicities = array();

        foreach($completedOrders as $order) {
            $reportInfo['totalFamilies'] += 1;
            $reportInfo['totalWeight'] += $order->orderWeight;
            $reportInfo['totalBags'] += $order->numBags;
            $customer = \models\Customer::findOne($order->customer_id);
            $reportInfo['totalAdults'] += $customer->numAdults;
            $reportInfo['totalKids'] += $customer->numKids;

            if($customer->isAttendee === OrderService::IS_ATTENDEE) {
                $reportInfo['totalBccAttendees'] += 1;
            } else {
                $reportInfo['totalNonBccAttendees'] += 1;
            }

            array_push($ethnicities, $customer->ethnicity);
        }

        $ethnicityDetails = "";
        $ethnicityCounts = array_count_values($ethnicities);
        $ethnicityKeys = array_keys($ethnicityCounts);

        foreach($ethnicityKeys as $key) {
            $ethnicityDetails .= $key . ' (' . $ethnicityCounts[$key] . ')<br/>';
        }

        $reportInfo['totalEthnicities'] = $ethnicityDetails;

        echo json_encode($reportInfo);
        exit();
    }

    public function generateRegularOrderReportDetails() {
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



        foreach($completedOrders as $order) {
            $customer = \models\Customer::findOne($order->customer_id);

            $recordInfo = array();
            $recordInfo['order_id'] = $order->id;
            $recordInfo['customer_id'] = $customer->id;
            $recordInfo['firstName'] = $customer->firstName;
            $recordInfo['lastName'] = $customer->lastName;
            $recordInfo['numAdults'] = $customer->numAdults;
            $recordInfo['numKids'] = $customer->numKids;
            $recordInfo['weight'] = $order->orderWeight;
            $recordInfo['numBags'] = $order->numBags;
            $recordInfo['ethnicity'] = $customer->ethnicity;
            $recordInfo['isAttendee'] = $customer->isAttendee;

            array_push($reportInfo, $recordInfo);
        }

        echo json_encode($reportInfo);
        exit();
    }

    public function generateTefapOrderReportSummary() {
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
        $reportInfo['totalTefapCount'] = 0;
        $reportInfo['totalBccAttendees'] = 0;
        $reportInfo['totalNonBccAttendees'] = 0;

        $ethnicities = array();

        foreach($completedOrders as $order) {
            $reportInfo['totalFamilies'] += 1;
            $reportInfo['totalWeight'] += $order->orderWeight;
            $customer = \models\Customer::findOne($order->customer_id);
            $reportInfo['totalAdults'] += $customer->numAdults;
            $reportInfo['totalKids'] += $customer->numKids;
            $reportInfo['totalTefapCount'] += $order->tefapCount;

            if($customer->isAttendee === OrderService::IS_ATTENDEE) {
                $reportInfo['totalBccAttendees'] += 1;
            } else {
                $reportInfo['totalNonBccAttendees'] += 1;
            }

            array_push($ethnicities, $customer->ethnicity);
        }

        $ethnicityDetails = "";
        $ethnicityCounts = array_count_values($ethnicities);
        $ethnicityKeys = array_keys($ethnicityCounts);

        foreach($ethnicityKeys as $key) {
            $ethnicityDetails .= $key . ' (' . $ethnicityCounts[$key] . ')<br/>';
        }

        $reportInfo['totalEthnicities'] = $ethnicityDetails;

        echo json_encode($reportInfo);
        exit();
    }

    public function generateTefapOrderReportDetails() {
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
            'type'=> OrderService::TEFAP_ORDER_TYPE
        ))->where_raw('(orderDate between ? and ?)', array($startDate, $endDate))->findMany();

        foreach($completedOrders as $order) {
            $customer = \models\Customer::findOne($order->customer_id);

            $recordInfo = array();
            $recordInfo['order_id'] = $order->id;
            $recordInfo['customer_id'] = $customer->id;
            $recordInfo['firstName'] = $customer->firstName;
            $recordInfo['lastName'] = $customer->lastName;
            $recordInfo['numAdults'] = $customer->numAdults;
            $recordInfo['numKids'] = $customer->numKids;
            $recordInfo['weight'] = $order->orderWeight;
            $recordInfo['tefapCount'] = $order->tefapCount;
            $recordInfo['ethnicity'] = $customer->ethnicity;
            $recordInfo['isAttendee'] = $customer->isAttendee;

            array_push($reportInfo, $recordInfo);
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