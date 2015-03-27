<?php
include_once 'common/showErrors.php';

require_once 'common/Carbon.php';

$nextDate = \Carbon\Carbon::now()->addDays(45)->toFormattedDateString();
?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/reminder.css" type="text/css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/default.js"></script>
</head>
<body>
<div class="centeredBlock"><span>45 days from now is <br/><?php echo $nextDate;?></span></div>
</body>
</html>