<?php

include_once 'showErrors.php';

if(!isset($_SESSION['login_user'])){
    header("location: login.php");
}