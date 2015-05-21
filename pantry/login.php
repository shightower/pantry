<?php
include_once 'common/showErrors.php';

if(session_id() == '') {
    session_start();
}

require_once 'security/auth.php';

$error = ''; // Variable To Store Error Message

if(isset($_POST['action']) && $_POST['action'] === 'login') {

    // Define $username and $password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // To protect MySQL injection for Security purpose
    $username = stripslashes($username);
    $password = stripslashes($password);
    $auth = new Auth();

    $authenticated = $auth->authenticateUser($username, $password);

    if($authenticated) {
        //Start new session
        $_SESSION['login_user'] = $username;
        header("location: home.php");
    } else {
        //TODO Figure out what to do for error
        $error = "Invalid Username/Password, please try again.";
    }
}
?>
<html>
<head>
    <title>Bridgeway Food Pantry</title>
    <link rel="stylesheet" href="css/jqx.base.css" type="text/css"/>
    <link rel="stylesheet" href="css/jqx.ui-sunny.css" type="text/css"/>
    <link rel="stylesheet" href="css/cupboard.css" type="text/css"/>
    <link rel="stylesheet" href="css/home.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery-ui.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.corner.js"></script>
    <script type="text/javascript" src="js/jqx-all.js"></script>
    <script type="text/javascript" src="js/cupboard.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#username").jqxInput({width: '40%', height: '22px'});
            $("#password").jqxPasswordInput({width: '40%', height: '22px'});
            $("#submit").jqxButton({theme: theme});

            $("#loginForm").jqxValidator({
                rules: [
                    { input: "#username", message: "Username is required!", action: 'keyup,blur', rule: 'required'},
                    { input: "#password", message: "Password is required!", action: 'keyup,blur', rule: 'required'}
                ]
            });

            $("#submit").click(function() {
                $("#loginForm").jqxValidator('validate');
            });

            $("#loginForm").on('validationSuccess', function(event) {
                var params = $('#loginForm').serialize();

                $.post('login.php', params, function(resp) {
                }).fail(function(xhr, status, error) {
                    alert('failure. \n' + xhr);
                });
            });

            $("#invalid-login-popup").dialog({
                autoOpen: false,
                resizable: false,
                width: 250,
                height: 250,
                modal: false,
                position: {my: "top", at: "center"},
                show: {effect: "shake", duration: 800}
            });
        });
    </script>
</head>
<body>
<div id='content' class='content centeredBlock'>
    <div class='logo' id='logo'>
    </div>
    <div class="bottomPadding">&nbsp</div>

    <div id="loginDiv" class="centeredBlock" style="width: 40%;">
        <div style="font-family: Verdana, Arial, sans-serif; font-size: 13px;">
            <form id="loginForm" style="overflow: hidden; margin: 10px">
                <input type="hidden" name="action" value="login">
                <table style="margin: 10px 0 10px 0; background-color: transparent !important;">
                    <tr>
                        <td colspan="2">
                            Username
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input id="username"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Password
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input id="password" type="password"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input id="submit" type="button" value="Login">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="bottomPadding">&nbsp</div>
</div>
<div id="invalid-login-popup" title="Login Unsuccessful">
    <p><span class="ui-icon ui-icon-error" style="float:left; margin:0 7px 0 0;"></span>
        The Username and/or Password provided was incorrect.
    </p>
</div>
</body>
</html>