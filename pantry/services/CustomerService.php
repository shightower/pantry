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
        $customer->fist_name = $fNameValid;
        $customer->last_name = $lNameValid;
        $customer->street = $streetValid;
        $customer->city = $cityValid;
        $customer->state = $stateValid;
        $customer->zip = $zipValid;
        $customer->num_adults = $numAdultsValid;
        $customer->num_kids = $numKidsValid;
        $customer->phone = $phonenumberValid;
        $customer->ethnicity = $ethnicityValid;
        $customer->service = $serviceValid;
        $customer->is_attendee = false;

        $customer->save();

        var_dump($customer);




    //    $cs = new CustomerService();
    //    $cs->addCustomer();

    //    $requiredAlphaField = \Respect\Validation\Validator::alpha()->notEmpty();
    //    $requiredAlphaNum = \Respect\Validation\Validator::alnum()->notEmpty();
    //    $requiredNumeric = \Respect\Validation\Validator::numeric()->notEmpty()->not(\Respect\Validation\Validator::negative());
    //
    //    $errMsg = '';
    //    $fNameValid = $requiredAlphaField->validate($_POST['firstName']);
    //    $lNameValid = $requiredAlphaField->validate($_POST['lastName']);
    //    $streetValid = $requiredAlphaNum->validate($_POST['street']);
    //    $cityValid = $requiredAlphaField->validate($_POST['city']);
    //    $stateValid = $requiredAlphaField->length(2,2)->validate($_POST['state']);
    //    $zipValid = $requiredNumeric->length(5,5)->validate($_POST['zip']);
    //    $numKidsValid = $requiredNumeric->max(9)->validate($_POST['numOfKids']);
    //    $numAdultsValid = $requiredNumeric->max(9)->validate($_POST['numOfAdults']);
    //
    //    if(!$fNameValid) {
    //        $errMsg .= "Invalid first name <br/>";
    //    }
    //
    //    if(!$lNameValid) {
    //        $errMsg .= "Invalid last name. <br/>";
    //    }
    //
    //    if(!$streetValid) {
    //        $errMsg .= "Invalid street address. <br/>";
    //    }
    //
    //    if(!$cityValid) {
    //        $errMsg .= "Invalid city. <br/>";
    //    }
    //
    //    if(!$stateValid) {
    //        $errMsg .= "Invalid state code. <br/>";
    //    }
    //
    //    if(!$zipValid) {
    //        $errMsg .= "Invalid zip code <br/>";
    //    }
    //
    //    if(!$numKidsValid) {
    //        $errMsg .= "Invalid # of Kids. <br/>";
    //    }
    //
    //    if(!$numAdultsValid) {
    //        $errMsg .= "Invalid # of Adults. <br/>";
    //    }


    }
}