<?php

$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/pantry/';

include_once $rootPath . 'common/showErrors.php';
require_once $rootPath . 'common/idiorm.php';
require_once $rootPath . 'common/paris.php';
require_once $rootPath . 'db_configs/db_configs.php';
require_once $rootPath . 'models/Note.php';

class NotesService {

    function addNote() {
        $note = ORM::for_table('Notes')->create();
        $note->customer_id = $_POST['customer_id'];
        $note->order_id = $_POST['order_id'];
        $note->message = $_POST['message'];
        $note->save();
    }

    function getCustomerNotes() {
        $notes = \models\Note::where('customer_id', $_POST['customer_id'])->findMany();
        $returnObj = array();

        foreach ($notes as $note) {
            array_push($returnObj, $note);
        }

        echo json_encode($returnObj);
        exit();
    }

    function getOrderNotes() {
        $notes = \models\Note::where('order_id', $_POST['order_id'])->findMany();
        $returnObj = array();

        foreach ($notes as $note) {
            array_push($returnObj, $note);
        }

        echo json_encode($returnObj);
        exit();
    }
}