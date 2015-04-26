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
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $numKids = $_POST['numOfKids'];
        $numAdults = $_POST['numOfAdults'];
        $phone = $_POST['phone'];
        $ethnicity = $_POST['ethnicity'];
        $isAttendee = $_POST['isAttendee'];
        $service = "n/a";

        if($isAttendee === "1") {
            $service = $_POST['service'];
        }

        $customer->firstName = $firstName;
        $customer->lastName = $lastName;
        $customer->street = $street;
        $customer->city = $city;
        $customer->state = $state;
        $customer->zip = $zip;
        $customer->numAdults = $numAdults;
        $customer->numKids = $numKids;
        $customer->phone = $phone;
        $customer->ethnicity = $ethnicity;
        $customer->service = $service;
        $customer->isAttendee = $isAttendee;

        $customer->save();
        var_dump($customer);
    }
}