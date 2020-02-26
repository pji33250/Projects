<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');

// Get job: such as edit/add/...
$job = '';
if (isset($_GET['job'])) { $job = $_GET['job']; }

// Prepare array
$mysql_data = array();
$result  = 'error';
$message = 'initial error';

if ($job)
{
    // Execute job
    if ($job == 'get_records')
    {
      // Get/List all authors
      $query = "SELECT * FROM USER ORDER BY ADMIN DESC, ACTIVE DESC, CHN_NAME ASC, ENG_NAME ASC";
      $allRecords = executeQuery($query);
      $num = mysqli_num_rows($allRecords);
      if ($num > 0) {
        $result  = 'success';
        $message = 'get_records() success';

        while ($rec = mysqli_fetch_array($allRecords)) {
          // Edit/Delete functions (buttons)
          $functions  = '<div class="function_buttons"><ul>';
          $functions .= '<li class="function_edit"><a data-id="' . $rec['ID'] . '" data-name="' . $rec['PID'] . '"><span>Edit</span></a></li>';
          $functions .= '<li class="function_delete"><a data-id="' . $rec['ID'] . '" data-name="' . $rec['PID'] . '"><span>Delete</span></a></li>';
          $functions .= '</ul></div>';

          // List ID and NAME only
          $IsAdmin  = ($rec['ADMIN'] == '1') ? "Yes" : "No";
          $IsActive = ($rec['ACTIVE'] == '1') ? "Yes" : "No";
          $mysql_data[] = array(
            //"ID"        => $rec['ID'],
            "PID"       => $rec['PID'],
            "TYPE"      => $rec['TYPE'],
            // "LOGON"     => $rec['LOGON'],
            // "PASSWORD"  => "******",
            "TITLE"     => $rec['TITLE'],
            "CHN_NAME"  => $rec['CHN_NAME'],
            "ENG_NAME"  => $rec['ENG_NAME'],
            "PHONE"     => $rec['PHONE'],
            "EMAIL"     => $rec['EMAIL'],
            "ADMIN"     => $IsAdmin,
            "ACTIVE"    => $IsActive,
            "functions" => $functions
            );
        }
      }
    }
    elseif ($job == 'get_record')
    {
        // Get author for editing
        if (isset($_GET['id'])) {
            $myid = $_GET['id'];
            $rec = fetchFirstRecord('USER', 'ID', $myid);
            if ($rec) {
                $result  = 'success';
                $message = 'get_record() success';

                $mysql_data[] = array(
                    "PID"      => $rec['PID'],
                    "TYPE"     => $rec['TYPE'],
                    "TITLE"    => $rec['TITLE'],
                    "CHN_NAME" => $rec['CHN_NAME'],
                    "ENG_NAME" => $rec['ENG_NAME'],
                    "PHONE"    => $rec['PHONE'],
                    "EMAIL"    => $rec['EMAIL'],
                    "ADMIN"    => $rec['ADMIN'],
                    "ACTIVE"   => $rec['ACTIVE'],
                    "LOGON"    => $rec['LOGON'],
                    "PASSWORD" => $rec['PASSWORD'],
                );
            }
            else {
                $message = 'get_record() failed';
            }
        }
        else {
            $message = 'get_record() failed: id missing';
        }
    }
    elseif ($job == 'add_record')
    {
        $adding_record = true;      // will add-new-user-record
        $mylogon = '';

        if (isset($_GET['PID'])) {
            // Check if logon exists: we cannot add another user with the same LOGON
            $mylogon = mysqli_real_escape_string($GLOBALS['db'], $_GET['PID']);
            $rec = fetchFirstRecord('USER', 'PID', $mylogon);
            if ($rec) { $adding_record = false; }
        }

        // if the flag is still true, add the new record
        if ($adding_record == true) {
            // Add publisher
            $query = "INSERT INTO USER SET PID = '$mylogon'";
            if (isset($_GET['TYPE']))     { $query .= ", TYPE     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TYPE'])     . "'"; }
            if (isset($_GET['LOGON']))    { $query .= ", LOGON    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['LOGON'])    . "'"; }
            if (isset($_GET['PASSWORD'])) { $query .= ", PASSWORD = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PASSWORD']) . "'"; }
            if (isset($_GET['TITLE']))    { $query .= ", TITLE    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TITLE'])    . "'"; }
            if (isset($_GET['CHN_NAME'])) { $query .= ", CHN_NAME = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['CHN_NAME']) . "'"; }
            if (isset($_GET['ENG_NAME'])) { $query .= ", ENG_NAME = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ENG_NAME']) . "'"; }
            if (isset($_GET['EMAIL']))    { $query .= ", EMAIL    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['EMAIL'])    . "'"; }
            if (isset($_GET['PHONE']))    { $query .= ", PHONE    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PHONE'])    . "'"; }
            if (isset($_GET['ADMIN']))    { $query .= ", ADMIN    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ADMIN'])    . "'"; }
            if (isset($_GET['ACTIVE']))   { $query .= ", ACTIVE   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ACTIVE'])   . "'"; }

            $queryRet = executeQuery($query);
            if ($queryRet) {
                $result  = 'success';
                $message = 'add_record() success';
            }
        }
    }
    elseif ($job == 'edit_record')
    {
        // Edit an existing author
        if (isset($_GET['id'])) {
            $myid = $_GET['id'];

            $adding_record = true;      // will add-new-user-record
            $mylogon = null;

            if (isset($_GET['PID'])) {
                // If the same name exists with different ID, no editing
                $mylogon = mysqli_real_escape_string($GLOBALS['db'], $_GET['PID']);
                $preQuery = "SELECT * FROM USER WHERE PID='$mylogon' AND ID<>$myid";
                $rec = fetchFirstRecordByQuery($preQuery);
                if ($rec) { $adding_record = false; }
            }

            if ($adding_record == true) {
                // Edit publisher
                $query = "UPDATE USER SET PID='$mylogon'";
                if (isset($_GET['TYPE']))     { $query .= ", TYPE     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TYPE'])     . "'"; }
                if (isset($_GET['LOGON']))    { $query .= ", LOGON    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['LOGON'])    . "'"; }
                if (isset($_GET['PASSWORD'])) { $query .= ", PASSWORD = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PASSWORD']) . "'"; }
                if (isset($_GET['TITLE']))    { $query .= ", TITLE    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TITLE'])    . "'"; }
                if (isset($_GET['CHN_NAME'])) { $query .= ", CHN_NAME = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['CHN_NAME']) . "'"; }
                if (isset($_GET['ENG_NAME'])) { $query .= ", ENG_NAME = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ENG_NAME']) . "'"; }
                if (isset($_GET['EMAIL']))    { $query .= ", EMAIL    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['EMAIL'])    . "'"; }
                if (isset($_GET['PHONE']))    { $query .= ", PHONE    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PHONE'])  . "'"; }
                if (isset($_GET['ADMIN']))    { $query .= ", ADMIN    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ADMIN'])    . "'"; }
                if (isset($_GET['ACTIVE']))   { $query .= ", ACTIVE   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ACTIVE'])   . "'"; }
                $query .= " WHERE ID=$myid";

                $queryRet = executeQuery($query);
                if ($queryRet) {
                    $result  = 'success';
                    $message = 'edit_record() success';
                }
            }
        }
        else {
            $message = 'edit_record() failed: id missing';
        }
    }
    elseif ($job == 'delete_record')
    {
        // Delete the selected author
        if (isset($_GET['id'])) {
            $myid = $_GET['id'];
            $queryBookCheck = "SELECT LIBRARY_ID FROM CIRCULATION WHERE LIBRARY_ID=$myid";
            $rec = fetchFirstRecordByQuery($queryBookCheck);
            if (!$rec) {
                // Only if the author has not been referred, can be deleted from DB
                $query = "UPDATE USER SET ACTIVE=0 WHERE ID=$myid";
                $queryRet = executeQuery($query);
                if ($queryRet) {
                    $result  = 'success';
                    $message = 'delete_record() success';
                }
            }
            else {
                $message = 'delete_record() failed: the user is still in circulation';
            }
        }
        else {
            $message = 'delete_record() failed: id missing';
        }
    }
}

// Prepare data
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

// Convert PHP array to JSON array
$json_data = json_encode($data);
print $json_data;

?>
