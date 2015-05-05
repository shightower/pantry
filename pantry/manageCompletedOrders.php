<?php

include_once 'common/showErrors.php';

require_once 'services/OrderService.php';

if(isset($_POST['action']) && $_POST['action'] == 'getPendingOrders') {
    $os = new OrderService();
    $os->getPendingOrders();
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
    <script type="text/javascript" src="js/completedOrders.js"></script>
    <script type="text/javascript" src="js/jquery-dateFormat.min.js"></script>
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
            <li>Export
                <ul id="export">
                    <li id="pdfExport">PDF</li>
                    <li id="excelExport">Excel</li>
                </ul>
            </li>
        </ul>
    </div>

    <div class='titleDiv'>
        <p id='pageTitle'>Completed Orders</p>
    </div>
    <div class='searchInput'>
        <center>
            <input type='text' id='searchBox'/>
            <input type='button' id='searchButton' value='Search'/>
        </center>
    </div>
    <!-- End of Common Content -->

    <div id="completedOrdersGrid" class="searchResults"></div>
    <div id='clearSearchDiv' class="clearSearchDiv">
        <input type='button' id='clearButton' value='Clear Search'/>
    </div>

    <div class="bottomPadding">&nbsp</div>
</div>
</body>
</html>