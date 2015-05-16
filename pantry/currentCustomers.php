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
    <?php require 'menu.php';?>
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
    <div id='clearSearchDiv' class="divCenteredButton">
        <input type='button' id='clearButton' value='Clear Search'/>
    </div>

    <div class="bottomPadding">&nbsp</div>

    <div id="popupWindow">
        <div><h3>Edit Customer</h3></div>
        <div>
            <form id="editCustomerForm">
                <input type="hidden" name="action" value="updateCustomer"/>
                <table class="editCustomerTable">
                    <tr>
                        <td>Id</td>
                        <td><input type="text" id="id" name="id" class="text-input" disabled="true"/></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><input type="text" id="firstNameInput" name="firstName" class="text-input"/></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type="text" id="lastNameInput" name="lastName" class="text-input"/></td>
                    </tr>
                    <tr>
                        <td>Street Address</td>
                        <td><input type="text" id="streetInput" name="street" class="text-input"/></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td><input type="text" id="cityInput" name="city" class="text-input"/></td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td>
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
                        <td>Zip</td>
                        <td><div id="zipInput" name="zip"></div></td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td><div id="phoneInput" name="phone"></div></td>
                    </tr>
                    <tr>
                        <td>Number of Adults</td>
                        <td><div name="numOfAdults" id="numAdultsInput"></div></td>
                    </tr>
                    <tr>
                        <td>Number of Kids (under 12)</td>
                        <td><div name="numOfKids" id="numKidsInput"></div></td>
                    </tr>
                    <tr>
                        <td>Ethnicity</td>
                        <td>
                            <select id="ethnicity" name="ethnicity">
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
                        <td>Attend Bridgeway</td>
                        <td>
                            <select id="isAttendee" name="isAttendee">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="serviceRow">
                        <td>Service</td>
                        <td>
                            <select name="service" id="service">
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="10:30">10:30</option>
                                <option value="12">12</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Note</td>
                        <td>
                            <textarea rows="8" cols="35" id="noteInput" name="note"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;"><input type="button" value="Update Info" id="editCustButton"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
</body>
</html>