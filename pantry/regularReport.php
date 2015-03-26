<?php

include_once 'common/showErrors.php';

require_once 'services/OrderService.php';

if(isset($_POST['action']) && $_POST['action'] == 'generateRegularReport') {
    $os = new OrderService();
    $os->generateRegularOrderReport();
}

if(isset($_POST['action']) && $_POST['action'] == 'generateTefapReport') {
    $os = new OrderService();
    $os->generateTefapOrderReport();
}
?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/reports.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jquery.noty.packaged.min.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/regularReport.js"></script>
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

    <div class='titleDiv'>
        <p id='pageTitle'>Cupboard Regular Orders Report</p>
    </div>
    <!-- End of Common Content <div id='bccMemberChart' class="pieChartDiv"></div>-->

    <div style="width: 600px;" class="centeredBlock">
        <table>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            <tr>
                <td>
                    <div id="startDateSelection"/>
                </td>
                <td>
                    <div id="endDateSelection"/>
                </td>
            </tr>
        </table>
    </div>

    <div style="width: 220px;" class="centeredBlock">
        <input type="button" value="Generate Report" id="generateReportButton"/>
    </div>

    <div class="bottomPadding">&nbsp</div>

    <div id="reportSummaryDiv">
        <!-- Regular Report -->
        <table class="reportSummaryTable" id="regularReport">
            <tr style="width: 100%; font-size: 1.5em">
                <td colspan="7">Regular Order Summary</td>
            </tr>
            <tr>
                <th>
                    Total Families
                </th>
                <th>
                    Total Weight
                </th>
                <th>
                    Total # of Adults
                </th>
                <th>
                    Total # of Kids
                </th>
                <th>
                    # of Bridgeway Attendees
                </th>
                <th>
                    # of Non Bridgeway Attendees
                </th>
            </tr>
            <tbody>
                <tr>
                    <td id="totalFamilies"></td>
                    <td id="totalWeight"></td>
                    <td id="totalAdults"></td>
                    <td id="totalKids"></td>
                    <td id="totalBccAttendees"></td>
                    <td id="totalNonBccAttendees"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bottomPadding">&nbsp</div>
</div>
</body>
</html>