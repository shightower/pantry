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
    <title>Login</title>
</head>
<body>
<!-- End of Common Content -->
<div style="width: 400px; height: 200px; margin-left: auto; margin-right: auto; margin-top: 4em;border: 1px solid black; background-color: #6C5035">
    <h2 style="background-color: #D6AD33"><center>BCC Foodpantry Login</center></h2>
    <form method="POST" action=''>
        <table border="0" cellspacing="5" style="width: 90%; margin-left: auto; margin-right: auto">
            <tr>
                <th align="right">Username:</th>
                <td align="left"><input type="text" name="username" autofocus></td>
            </tr>
            <tr>
                <th align="right">Password:</th>
                <td align="left"><input type="password" name="password"></td>
            </tr>
            <tr>
                <td align="right"><input type="submit" value="Log In"></td>
            </tr>
            <input type="hidden" name="action" value="login"/>
        </table>
    </form>
    <div>
        <span><?php echo $error ?></span>
    </div>
</div>
</body>
</html>