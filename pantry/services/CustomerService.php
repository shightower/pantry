<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';
require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Customer.php';

class CustomerService {

    public function addCustomer() {
        $fNameValid = $_POST['firstName'];
        $lNameValid = $_POST['lastName'];
        $streetValid = $_POST['street'];
        $cityValid = $_POST['city'];
        $stateValid = $_POST['state'];
        $zipValid = $_POST['zip'];
        $numKidsValid = $_POST['numOfKids'];
        $numAdultsValid = $_POST['numOfAdults'];
        $phonenumberValid = $_POST['phonenumber'];
        $ethnicityValid = $_POST['ethnicity'];
        $attendeeValid = $_POST['attendee'];
        $serviceValid = $_POST['service'];

        $customer = ORM::for_table('Customer')->create();
        $customer->first_name = $fNameValid;
        $customer->last_name = $lNameValid;
        $customer->street = $streetValid;
        $customer->city = $cityValid;
        $customer->state = $stateValid;
        $customer->zip = $zipValid;
        $customer->num_adults = $numAdultsValid;
        $customer->num_kids = $numKidsValid;
//        $customer->phone = $phonenumberValid;
        $customer->ethnicity = $ethnicityValid;
        $customer->service = $serviceValid;
        $customer->is_attendee = false;

        $customer->save();
    }

    public function getCustomers() {
        $customers = \models\Customer::findMany();
        $customerArray = array();

        foreach($customers as $customer) {
            //echo json_encode($customer->asArray());
//            $customerJson = json_encode($customer->asArray());
            array_push($customerArray, $customer->asArray());
        }

        echo chop(json_encode($customerArray));
    }

//$customers = \models\Customer::findMany();
//$customerJson = "[";
//
//foreach($customers as $customer) {
//    //echo json_encode($customer->asArray());
//$customerJson .= json_encode($customer->asArray()) . ",";
//}
//
//$customerJson = substr($customerJson, 0, -1);
//$customerJson .= "]";
//
//echo $customerJson;
}