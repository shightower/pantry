<?php
echo "
<div class='logo' id='logo'>
</div>
<div id='menuBar'>
    <ul>
        <li><a href='home.php'>Welcome</a></li>
        <li>Customers
            <ul>
                <li><a href='addCustomer.php'>New</a></li>
                <li><a href='currentCustomers.php'>View Current</a></li>
            </ul>
        </li>
        <li>Orders
            <ul>
                <li><a href='addOrder.php'>Create New</a></li>
                <li><a href='pendingOrders.php'>View Pending</a></li>
                <li><a href='completedOrders.php'>View Completed</a> </li>
            </ul>
        </li>
        <li>Reports
            <ul>
                <li><a href='regularReport.php'>Regular Orders Report</a></li>
                <li><a href='tefapReport.php'>TEFAP Orders Report</a></li>
            </ul>
        </li>
        <li>45 Day Counter
            <ul>
                <li id='showReminderCounter'>Show</li>
            </ul>
        </li>
    </ul>
</div>";