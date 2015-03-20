<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';
require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Customer.php';

class CustomerService {

    public function addCustomer() {
        $customer = ORM::for_table('Customer')->create();
        $this->setValuesAndSave($customer);
    }

    public function updateCustomer() {
        $customer = \models\Customer::whereIdIs($_POST['id'])->findOne();

        if(false === $customer) {
            return http_response_code(400);
        } else {
            $this->setValuesAndSave($customer);
        }
    }

    private function setValuesAndSave($customer) {
        $fNameValid = $_POST['firstName'];
        $lNameValid = $_POST['lastName'];
        $streetValid = $_POST['street'];
        $cityValid = $_POST['city'];
        $stateValid = $_POST['state'];
        $zipValid = $_POST['zip'];
        $numKidsValid = $_POST['numOfKids'];
        $numAdultsValid = $_POST['numOfAdults'];
        $phonenumberValid = $_POST['phoneNumber'];
        $ethnicityValid = $_POST['ethnicity'];
        $attendeeValid = $_POST['attendee'];
        $serviceValid = $_POST['service'];

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
        $customer->is_attendee = $attendeeValid;

        $customer->save();
        $test = '';
    }
}