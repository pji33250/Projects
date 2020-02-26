<?php
include_once('./login/login.php');   // Includes Login Script
if(isset($_SESSION['login_user'])) { header("location: /library/admin/book"); }
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pageheader.php');
?>

<!-- CONTENT -->
<div id="main" class="signInBox" style="width: 360px;">
    <form action="" method="post">
        <table>
            <tr>
                <td>&nbsp;</td><td colspan="2"><h1>Admin Login</h1></td>
            </tr>
            <tr>
                <td><label style="float: right">Username:</label></td>
                <td><input id="name" name="username" placeholder="" type="text" autofocus /></td>
            </tr>
            <tr>
                <td><label style="float: right">Password:</label></td>
                <td><input id="password" name="password" placeholder="" type="password" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td><td><br><input style="float: center" name="submit" type="submit" value=" Login " /></td>
            </tr>
        </table>
        <span><?php echo $error; ?></span>
    </form>
</div>
<!-- *END* CONTENT -->

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pagefooter.php');
?>
