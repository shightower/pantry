<?php

include_once 'common/showErrors.php';

require_once 'services/OrderService.php';

if(isset($_POST['action']) && $_POST['action'] == 'getPendingOrders') {
    $os = new OrderService();
    $os->getPendingOrders();
}

if(isset($_GET['action']) && $_GET['action'] == 'getCompletedOrders') {
    $os = new OrderService();
    $os->getCompletedOrders();
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
    <?php require 'menu.php';?>
    <!-- End of Common Content -->

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

    <div id="completedRegularOrdersGrid" class="searchResults"></div>
    <div style="width: 810px !important;" class="divCenteredButton">
        <table style="float: right;">
            <tr>
                <td>
                    <input type="button" id="exportRegularButtonPdf" value="Export PDF"/>
                </td>
                <td>
                    <input type="button" id="exportRegularButtonExcel" value="Export Excel"/>
                </td>
            </tr>
        </table>
    </div>

    <div class='titleDiv'>
        <p id='pageTitle'>Completed Tefap Orders</p>
    </div>

    <div id="completedTefapOrdersGrid" class="searchResults"></div>
    <div style="width: 810px !important;" class="divCenteredButton">
        <table style="float: right;">
            <tr>
                <td>
                    <input type="button" id="exportTefapButtonPdf" value="Export PDF"/>
                </td>
                <td>
                    <input type="button" id="exportTefapButtonExcel" value="Export Excel"/>
                </td>
            </tr>
        </table>
    </div>

    <div id='clearSearchDiv' class="divCenteredButton">
        <input type='button' id='clearButton' value='Clear Search'/>
    </div>

    <div class="bottomPadding">&nbsp</div>
</div>
</body>
</html>