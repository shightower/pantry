<?php

include_once 'common/showErrors.php';

require_once 'services/OrderService.php';

if(isset($_POST['action']) && $_POST['action'] == 'generateRegularReportSummary') {
    $os = new OrderService();
    $os->generateRegularOrderReportSummary();
}

if(isset($_POST['action']) && $_POST['action'] == 'generateRegularReportDetails') {
    $os = new OrderService();
    $os->generateRegularOrderReportDetails();
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
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/regularReport.js"></script>
</head>
<body>
<div id='content' class='content centeredBlock'>
    <!-- Common Content, do not change -->
    <!-- Common Content, do not change -->
    <?php require 'menu.php';?>
    <!-- End of Common Content -->

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

    <div id="regularReportsSummaryGrid" class="searchResults"></div>
    <div style="width: 1060px !important;" class="divCenteredButton" id="summaryButtonDiv">
        <table style="float: right;">
            <tr>
                <td>
                    <input type="button" id="exportSummaryButtonPdf" value="Export PDF"/>
                </td>
                <td>
                    <input type="button" id="exportSummaryButtonExcel" value="Export Excel"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="bottomPadding">&nbsp</div>

    <div id="regularReportsDetailsGrid" class="searchResults"></div>
    <div style="width: 1100px !important;" class="divCenteredButton" id="detailsButtonDiv">
        <table style="float: right;">
            <tr>
                <td>
                    <input type="button" id="exportDetailsButtonPdf" value="Export PDF"/>
                </td>
                <td>
                    <input type="button" id="exportDetailsButtonExcel" value="Export Excel"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="bottomPadding">&nbsp</div>
</div>
</body>
</html>