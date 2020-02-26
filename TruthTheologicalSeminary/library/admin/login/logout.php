<?php
session_start();
if(session_destroy()) // Destroying All Sessions
{
    header("Location: /library/admin/index.php"); // Redirecting to logon page
}
?>
