<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');

$login_session = null;

// Starting Session
session_start();

// Storing Session
$user_check=$_SESSION['login_user'];
if (!empty($user_check))
{
    // SQL Query To Fetch Complete Information Of User
    $row = fetchFirstRecord('USER', 'LOGON', $user_check);
    if(!empty($row))
    {
        $login_session =$row['LOGON'];
    }
}

//if $login_session is empty, redirect to log-in page
if (empty($login_session))
{
    header('Location: /library/admin/index.php'); // Redirecting To logon page
}
?>
