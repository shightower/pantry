<?php
include_once 'common/showErrors.php';

require_once 'services/OrderService.php';

if(isset($_POST['action']) && $_POST['action'] == 'addOrder') {
    $os = new OrderService();
    $os->addOrder();
}

?>
<html>
<head>
    <title>Add Pending Order</title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.energyblue.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/jquery-ui.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jquery.noty.packaged.min.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/addOrder.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
</head>
<body>
<div id='content' class='content centeredBlock'>
    <!-- Common Content, do not change -->
    <?php require 'menu.php';?>
    <!-- End of Common Content -->

    <div class='titleDiv'>
        <p id='pageTitle'>Add Pending Order</p>
    </div>
    <!-- End of Common Content -->

    <!-- Customer Search Filter -->
    <div class='searchInput'>
        <center>
            <input type='text' id='searchBox'/>
            <input type='button' id='searchButton' value='Search'/>
        </center>
    </div>

    <!-- JqxGrid for adding an order -->
    <div id="addOrderGrid" class="searchResults"></div>

    <!-- Div to show clear search filter -->
    <div id='clearSearchDiv' class="divCenteredButton">
        <input type='button' id='clearButton' value='Clear Search'/>
    </div>

    <div class="bottomPadding">&nbsp</div>

    <!-- Pop-up info -->
    <div id="new-order-confirm" title="Create Regular Order?">
        <p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 0 0;"></span>
        <input type="checkbox" name="includeTefapOrder" id="addTefap">Include Tefap Order?
        </p>
    </div>

    <div id="order-override-confirm" title="Order is Too Soon!">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>
            You are attempting to create an order for a customer before their next scheduled available order date. Would
            you like to continue with the order anyways?
        </p>
    </div>
</div>
</body>
</html>