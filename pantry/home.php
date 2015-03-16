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
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
</head>
<body>
<div id='content' class='content centeredBlock'>
    <! -- Common Content, do not change -->
    <div class='logo' id='logo'>
    </div>
    <div id='menuBar'>
        <ul>
            <li><a href="#">Welcome</a></li>
            <li>Customers
                <ul>
                    <li><a id="addCustFrame" data-fancybox-type="iframe" href="addCustomerIframe.php">New</a></li>
                    <li><a href="currentCustomers.php">Current</a></li>
                </ul>
            </li>
            <li>Orders
                <ul>
                    <li><a href="addOrder.php">Add</a></li>
                    <li><a href="pendingOrders.php">Pending</a></li>
                </ul>
            </li>
            <li>Reports
                <ul>
                    <li><a href="regularReport.php">Regular Report</a></li>
                    <li><a href="tefapReport.php">TEFAP Report</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- End of Common Content -->

    <div class='titleDiv'>
        <p id='pageTitle'>Bridgeway Community Foodpantry</p>
    </div>
    <div>
        <p style="width: 50%; font-size: 1.5em;" class="centeredBlock">Welcome to the Bridgeway Community Church Food Pantry! Our mission is to provide families in need with the general necessities including
            both perishable and non-perishable items.  Listed below are our hours of operation.
        </p>
    </div>

    <h2><center><u>Hours of Operation<u><center></h2>
    <table class="indexTable centeredBlock" border="1">
        <tr>
            <th>Sunday</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        <tr>
            <td>
                <ul>
                    <li>10am-11am</li>
                    <li>12pm-1pm</li>
                </ul>
            </td>
            <td>Closed</td>
            <td>&nbsp</td>
            <td>Closed</td>
            <td>Closed</td>
            <td>Closed</td>
            <td>nbsp</td>
        </tr>
    </table>
    <div class="bottomPadding">&nbsp</div>

</div>
</body>
</html>

