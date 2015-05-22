<?php

$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';
require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'common/Carbon.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Note.php';


use \Carbon\Carbon;

class NotesService {

    function addNote() {
        $note = ORM::for_table('Notes')->create();
        $note->customer_id = $_POST['customer_id'];
        $note->message = $_POST['message'];
        $note->date = Carbon::now();
        $note->save();
        exit();
    }

    function addCustomerNote($customer_id, $message) {
        if($customer_id > 0 && ($message != null || trim($message) != "")) {
            $note = ORM::for_table('Notes')->create();
            $note->customer_id = $customer_id;
            $note->message = $message;
            $note->date = Carbon::now();
            $note->save();
        }
        exit();
    }

    function getCustomerNotes() {
        $notes = \models\Note::where('customer_id', $_GET['customer_id'])->findMany();
        $returnObj = array();

        foreach ($notes as $note) {
            $noteDetails = array();
            $noteDetails['message'] = $note->message;
            $noteDetails['date'] = $note->date;
            array_push($returnObj, $noteDetails);
        }

        echo json_encode($returnObj);
        exit();
    }
}