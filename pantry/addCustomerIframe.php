<?php
include_once 'common/showErrors.php';

require_once 'services/CustomerService.php';

if(isset($_POST['action']) && $_POST['action'] == 'addUser') {
    $cs = new CustomerService();
    $cs->addCustomer();
}
?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/addCustomer.css" type="text/css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/jquery.noty.packaged.min.js"></script>
    <script type="text/javascript" src="js/default.js"></script>
    <script type="text/javascript" src="js/addCustomer.js"></script>
    <script type="text/javascript" src="js/jquery.mask.min.js"></script>
</head>
<body>
<div class="addFormDiv">
    <form id="addCustForm" class="formLayout" >
        <label for="fisrtName"><em>*</em> First Name:</label>
        <input type="text" name="firstName" size="25" required/>
        <br/>
        <label for="lastName"><em>*</em> Last Name:</label>
        <input type="text" name="lastName" size="25" required/>
        <br/>
        <label for="street"><em>*</em> Street:</label>
        <input type="text" name="street" size="35" required/>
        <br/>
        <label for="city" ><em>*</em> City:</label>
        <input type="text" name="city" size="15" required/>
        <br/>
        <label for="state" ><em>*</em> State:</label>
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
        <br/>
        <label for="zip" ><em>*</em> Zip:</label>
        <input type="text" name="zip" size="5" required/>
        <br/>
        <label for="phone" ><em>*</em> Phone:</label>
        <input type="text" name="phone" class="us_phone" size="12" required/>
        <br/>
        <label for="numOfAdults" ><em>*</em> # of Adults:</label>
        <input type="number" name="numOfAdults" id="numAdults" min="0" value="0" required/>
        <br/>
        <label for="numOfKids" ><em>*</em> # of Kids (Under age of 12):</label>
        <input type="number" name="numOfKids" id="numKids" min="0" value="0" required/>
        <br/>
        <label for="ethnicity" ><em>*</em> Ethnicity:</label>
        <select name="ethnicity">
            <option value="African American">African American</option>
            <option value="Asian">Asian</option>
            <option value="Caucasian">Caucasian</option>
            <option value="Hispanic">Hispanic</option>
            <option value="Other">Other</option>
        </select>
        <br/>
        <label for="isAttendee" ><em>*</em> BCC Attendee?</label>
        <select id="isAttendee" name="isAttendee">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
        <br/>
        <div id="serviceDiv">
        </div>
        <input type="button" value="Add Customer" id="addCustButton">
        <input type="hidden" name="action" value="addUser">
    </form>
</div>
</body>
</html>