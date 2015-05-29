<?php
if(session_id() == '') {
    session_start();
}

require_once 'common/sessionCheck.php';
require 'services/NotesService.php';

if(isset($_POST['action']) && $_POST['action'] == 'addNote') {
    $ns = new NotesService();
    $ns->addNote();
}

if(isset($_POST['action']) && $_POST['action'] == 'getCustomerNotes') {
    $ns = new NotesService();
    $ns->getCustomerNotes();
}

if(isset($_POST['action']) && $_POST['action'] == 'getOrderNotes') {
    $ns = new NotesService();
    $ns->getOrderNotes();
}