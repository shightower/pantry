<?php

include_once 'common/showErrors.php';

require_once 'services/OrderService.php';

if(isset($_POST['action']) && $_POST['action'] == 'completeOrder') {
    $os = new OrderService();
    $os->completeOrder();
}

if(isset($_POST['action']) && $_POST['action'] == 'deletePending') {
    $os = new OrderService();
    $os->deletePendingOrder();
}
?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/orders.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jquery.noty.packaged.min.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/orders.js"></script>
</head>
<body>
<div id='content' class='content centeredBlock'>
    <!-- Common Content, do not change -->
    <div class='logo' id='logo'>
    </div>
    <div id='menuBar'>
        <ul>
            <li><a href="home.php">Welcome</a></li>
            <li>Customers
                <ul>
                    <li><a id="addCustFrame" data-fancybox-type="iframe" href="addCustomerIframe.php">New</a></li>
                    <li><a href="currentCustomers.php">Current</a></li>
                </ul>
            </li>
            <li>Orders
                <ul>
                    <li><a href="addOrder.php">Add</a></li>
                    <li><a href="#">Pending</a></li>
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

    <div class='titleDiv'>
        <p id='pageTitle'>Pending Orders</p>
    </div>
    <!-- End of Common Content -->

    <div id="pendingOrdersGrid" class="centeredBlock"></div>

    <div class="bottomPadding">&nbsp</div>

    <div id="popupOrder">
        <div>Complete Order</div>
        <div style="overflow: hidden;">
            <table>
                <tr>
                    <td align="right">Order Number:</td>
                    <td align="left"><input id="id" disabled/></td>
                </tr>
                <tr>
                    <td align="right">First Name:</td>
                    <td align="left"><input id="firstName" disabled/></td>
                </tr>
                <tr>
                    <td align="right">Last Name:</td>
                    <td align="left"><input id="lastName" disabled/></td>
                </tr>
                <tr>
                    <td align="right">Order Date:</td>
                    <td align="left"><input id="orderDate" /></td>
                </tr>
                <tr>
                    <td align="right">Number of Bags:</td>
                    <td align="left"><input id="numBags" type="number" min="1" step="1"/></td>
                </tr>
                <tr>
                    <td align="right">Order Weight:</td>
                    <td align="left"><input id="orderWeight" type="number"/></td>
                </tr>
            </table>
            <div style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 1em;">
                <input style="margin-right: 5px;" type="button" id="completeOrderButton" value="Complete Order" />
                <input id="cancelOrderButton" type="button" value="Cancel" />
            </div>
        </div>
    </div>
    <div id="popupTefap">
        <div>Complete TEFAP Order</div>
        <div style="overflow: hidden;">
            <table>
                <tr>
                    <td align="right">Order Number:</td>
                    <td align="left"><input id="id" disabled/></td>
                </tr>
                <tr>
                    <td align="right">First Name:</td>
                    <td align="left"><input id="tefapFirstName" disabled/></td>
                </tr>
                <tr>
                    <td align="right">Last Name:</td>
                    <td align="left"><input id="tefapLastName" disabled/></td>
                </tr>
                <tr>
                    <td align="right">Order Date:</td>
                    <td align="left"><input id="tefapDate" /></td>
                </tr>
                <tr>
                    <td align="right">TEFAP Count:</td>
                    <td align="left"><input id="tefapCount"/></td>
                </tr>
                <tr>
                    <td align="right">Order Weight:</td>
                    <td align="left"><input id="tefapWeight"/></td>
                </tr>
            </table>
            <div style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 1em;">
                <input style="margin-right: 5px;" type="button" id="completeTefapButton" value="Complete Order" />
                <input id="cancelTefapButton" type="button" value="Cancel" />
            </div>
        </div>
    </div>
</div>
</body>
</html>