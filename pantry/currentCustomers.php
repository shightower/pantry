<?php
include_once 'common/showErrors.php';
require_once 'services/CustomerService.php';

if(isset($_POST['action']) && $_POST['action'] == 'updateCustomer') {
    $cs = new CustomerService();
    $cs->updateCustomer();
}

if(isset($_GET['action']) && $_GET['action'] == 'getAllCustomers') {
    $cs = new CustomerService();
    $cs->getAllCustomers();
}
?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/customers.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jquery.noty.packaged.min.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/customers.js"></script>
    <script type="text/javascript" src="js/jquery.mask.min.js"></script>
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
                    <li><a href="#">Current</a></li>
                </ul>
            </li>
            <li>Orders
                <ul>
                    <li><a href="addOrder.php">Add</a></li>
                    <li><a href="managePendingOrders.php">Pending</a></li>
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
    <!-- End of Common Content -->

    <div class='titleDiv'>
        <p id='pageTitle'>Current Customers</p>
    </div>
    <div class='searchInput'>
        <center>
            <input type='text' id='searchBox'/>
            <input type='button' id='searchButton' value='Search'/>
        </center>
    </div>

    <div id="customersGrid" class="searchResults"></div>
    <div id='clearSearchDiv' class="clearSearchDiv">
        <input type='button' id='clearButton' value='Clear Search'/>
    </div>

    <div class="bottomPadding">&nbsp</div>

    <div id="popupWindow">
        <div>Edit Customer</div>
        <div style="overflow: hidden;">
            <table>
                <tr>
                    <td align="right">Id:</td>
                    <td align="left"><input id="id" disabled/></td>
                </tr>
                <tr>
                    <td align="right">First Name:</td>
                    <td align="left"><input id="firstName" /></td>
                </tr>
                <tr>
                    <td align="right">Last Name:</td>
                    <td align="left"><input id="lastName" /></td>
                </tr>
                <tr>
                    <td align="right">Phone:</td>
                    <td align="left"><input id="phone" class="us_phone"/></td>
                </tr>
                <tr>
                    <td align="right">Street:</td>
                    <td align="left"><input id="street"/></td>
                </tr>
                <tr>
                    <td align="right">City:</td>
                    <td align="left"><input id="city"/></td>
                </tr>
                <tr>
                    <td align="right">State:</td>
                    <td align="left">
                        <select name="state" id="state">
                            <option value="MD">Maryland</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">Zip:</td>
                    <td align="left"><input id="zip"/></td>
                </tr>
                <tr>
                    <td align="right"># of Adults:</td>
                    <td align="left"><div id="numAdults"></div></td>
                </tr>
                <td align="right"># of Kids (Under age of 12):</td>
                <td align="left"><div id="numKids"></div></td>
                </tr>
                <tr>
                    <td align="right">Ethnicity:</td>
                    <td>
                        <select name="ethnicity" id="ethnicity">
                            <option value="Asian">Asian</option>
                            <option value="Black or African American">Black or African American</option>
                            <option value="Hispanic">Hispanic</option>
                            <option value="Native American or Alaskan Native">Native American or Alaskan Native</option>
                            <option value="Native Hawaiian or Pacific Islander">Native Hawaiian or Pacific Islander</option>
                            <option value="White">White</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">BCC Attendee?</td>
                    <td>
                        <select name="isAttendee" id="isAttendee">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">Service:</td>
                    <td>
                        <select name="service" id="service">
                            <option value="N/A">N/A</option>
                            <option value="8">8</option>
                            <option value="10">10</option>
                            <option value="10:30">10:30</option>
                            <option value="12">12</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"></td>
                    <td style="padding-top: 10px;" align="left"><input style="margin-right: 5px;" type="button" id="saveButton" value="Save" /><input id="cancelButton" type="button" value="Cancel" /></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>