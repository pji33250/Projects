<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');

session_start(); // Starting Session
$error=''; 		 // Variable To Store Error Message
if (isset($_POST['submit']))
{
    if (empty($_POST['username']) || empty($_POST['password']))
    {
        $error = "Username or Password is invalid";
    }
    else
    {
        // Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];

        // To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $username = mysqli_real_escape_string($GLOBALS['db'], $username);

        $password = stripslashes($password);
        $password = mysqli_real_escape_string($GLOBALS['db'], $password);

        // Selecting Database
        $query = "SELECT * FROM USER WHERE PASSWORD='$password' AND LOGON='$username' AND ADMIN=1 AND ACTIVE=1";

        // SQL query to fetch information of registerd users and finds user match.
        $queryResult = executeQuery($query);
        $rows = mysqli_num_rows($queryResult);
        if ($rows == 1)
        {
            $_SESSION['login_user'] = $username;  // Initializing Session
            header("location: /library/admin/book");      // Redirecting To Other Page
        } else
        {
            $error = "Username or Password is invalid";
        }
    }
}
?>
