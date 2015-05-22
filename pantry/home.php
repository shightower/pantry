<?php
if(session_id() == '') {
    session_start();
}

include_once 'common/showErrors.php';
include_once 'db_configs/db_configs.php';

require_once 'common/sessionCheck.php';

?>
<html>
<head>
    <title>Bridgeway Food Pantry</title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/home.css" type="text/css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
</head>
<body>
<div id='content' class='content centeredBlock'>
    <!-- Common Content, do not change -->
    <?php require 'menu.php';?>
    <!-- End of Common Content -->

    <div class='titleDiv'>
        <p id='pageTitle'>Bridgeway Community Foodpantry</p>
    </div>
    <div>
        <p style="width: 50%; font-size: 1.5em;" class="centeredBlock">Welcome to the Bridgeway Community Church Food Pantry! Our mission is to provide families in need with the general necessities including
            both perishable and non-perishable items.  Listed below are our hours of operation.
        </p>
    </div>

    <div style="text-align: center; text-decoration: underline; font-size: 1.65em;">Hours of Operation</div>
    <br/>
    <table class="indexTable centeredBlock" border="1">
        <thead>
        <tr>
            <th>Sunday</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <span>30 Minutes after each service.</span>
            </td>
            <td>Closed</td>
            <td>6pm to 7pm</td>
            <td>Closed</td>
            <td>Closed</td>
            <td>Closed</td>
            <td>10am to 11am</td>
        </tr>
        </tbody>
    </table>
    <div class="bottomPadding">&nbsp</div>

</div>
</body>
</html>

