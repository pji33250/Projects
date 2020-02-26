<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');

// Get job: such as edit/add/...
$job = '';
if (isset($_GET['job'])) { $job = $_GET['job']; }

// Prepare array
$json_data = array();       // initial return data array in json format
$mysql_data = array();      // initial return record array from database query
$result  = 'error';         // initial return status, assuming ERROR
$message = 'initial error'; // initial return message, assuming ERROR

if ($job)
{
    // Execute job
    if ($job == 'get_records') // Get all records
    {
        // storing  request (ie, get/post) global array to a variable
        $requestData= $_REQUEST;

        // datatable column index => database column name
        $columns = array(
        	0 => 'PID',
        	1 => 'TYPE',
            2 => 'CHN_NAME',
            3 => 'ENG_NAME',
            4 => 'PHONE',
            5 => 'EMAIL',
            6 => 'ADMIN',
            7 => 'ACTIVE',
            8 => 'Actions'
        );

        // getting total number records without any search
        $query = executeQuery("SELECT ID FROM USER WHERE ACTIVE=1");
        $totalData = mysqli_num_rows($query);

        // when there is no search parameter then total-number-rows = total-number-filtered-rows.
        $totalFiltered = $totalData;

        // the main query for the fields we need
        $sql = "SELECT * FROM USER WHERE ACTIVE=1";

        // if there is a search parameter, $requestData['search']['value'] contains search parameter
        if ( !empty($requestData['search']['value']) )
        {
        	$sql .= " AND ( PID LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR TYPE LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR CHN_NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR ENG_NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR PHONE LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR EMAIL LIKE '%".$requestData['search']['value']."%' )";
            $query = executeQuery($sql);

            // got total-number-of-filtered-rows
            $totalFiltered = mysqli_num_rows($query);
        }

        // added sorting, limit to the query
        $sql .= " ORDER BY ADMIN DESC, ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = executeQuery($sql);

        // preparing an array
        while( $row = mysqli_fetch_assoc($query) ) {
        	$nestedData = array();
        	foreach($row as $index=>$value) {
        		$nestedData[$index] = $value;
        	}
            $mysql_data[] = $nestedData;
        }

        $result  = 'success';
        $message = 'get_records() success';

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval( $totalData ),      // total number of records
            "recordsFiltered" => intval( $totalFiltered ),  // total number of records after searching, if there is no searching then totalFiltered = totalData
            "records"         => $mysql_data    // total data array
        );

    }
    elseif ($job == 'get_record') // Get one record for editing
    {
        $myid = $_GET['id'];
        $rec = fetchFirstRecord('USER', 'ID', $myid);
        if ($rec) {
            $result  = 'success';
            $message = 'get_record() success';

            $mysql_data[] = array(
                "PID"      => $rec['PID'],
                "TYPE"     => $rec['TYPE'],
                // "TITLE"    => $rec['TITLE'],
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
            $message = 'get_record() failed: no record found';
        }

        // Prepare data
        $json_data = array(
            "result"  => $result,
            "message" => $message,
            "data"    => $mysql_data
        );
    }
    elseif ($job == 'add_record') // Add new author record
    {
        $myuserid = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['PID']) );
        if (empty($myuserid)) {
            $message = 'add_record() skipped: personal-id field cannot be empty';
        }
        else {
            // Check if record exists, if not then add or skip it
            $rec = fetchFirstRecord('USER', 'PID', $myuserid);
            if (!$rec) {
                // Add record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "INSERT INTO USER SET PID = '$myuserid', ADDED_DATE = '$mytime'";
                if (isset($_GET['TYPE']))     { $query .= ", TYPE     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TYPE'])     . "'"; }
                if (isset($_GET['LOGON']))    { $query .= ", LOGON    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['LOGON'])    . "'"; }
                if (isset($_GET['PASSWORD'])) { $query .= ", PASSWORD = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PASSWORD']) . "'"; }
                // if (isset($_GET['TITLE']))    { $query .= ", TITLE    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TITLE'])    . "'"; }
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
                else {
                    $message = 'add_record() query execution failed: ['.$query.']';
                }
            }
            else {
                $message = 'add_record() skipped: the record ('.$mylogon.') exists already';
            }
        }

        // Prepare data
        $json_data = array(
            "result"  => $result,
            "message" => $message,
            "data"    => $mysql_data
        );
    }
    elseif ($job == 'edit_record') // Edit an existing record
    {
        $myid = $_GET['id'];
        $myuserid = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['PID']) );
        if (empty($myuserid)) {
            $message = 'edit_record() skipped: personal-id filed cannot be empty';
        }
        else {
            // Check if record exists, if not then add or skip it
            $existingRecordQuery = "SELECT * FROM USER WHERE PID='$myuserid' AND ID<>$myid";
            $rec = fetchFirstRecordByQuery($existingRecordQuery);
            if (!$rec) {
                // update record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "UPDATE USER SET PID='$myuserid', UPDATED_DATE='$mytime'";
                if (isset($_GET['TYPE']))     { $query .= ", TYPE     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TYPE'])     . "'"; }
                if (isset($_GET['LOGON']))    { $query .= ", LOGON    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['LOGON'])    . "'"; }
                if (isset($_GET['PASSWORD'])) { $query .= ", PASSWORD = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PASSWORD']) . "'"; }
                // if (isset($_GET['TITLE']))    { $query .= ", TITLE    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TITLE'])    . "'"; }
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
                else {
                    $message = 'edit_record() query execution failed: ['.$query.']';
                }
            }
            else {
                $message = 'edit_record() skipped: the record ('.$myname.') exists already';
            }
        }

        // Prepare data
        $json_data = array(
            "result"  => $result,
            "message" => $message,
            "data"    => $mysql_data
        );
    }
    elseif ($job == 'delete_record') // Delete the selected record
    {
        $myid = $_GET['id'];
        $queryCirculationCheck = "SELECT LIBRARY_ID FROM CIRCULATION WHERE LIBRARY_ID=$myid";
        $rec = fetchFirstRecordByQuery($queryCirculationCheck);
        if (!$rec) {
            // Only if the user has not been referred, can be deleted from DB
            $query = "DELETE FROM USER WHERE ID=$myid";
            $queryRet = executeQuery($query);
            if ($queryRet) {
                $result  = 'success';
                $message = 'delete_record() success';
            }
            else {
                $message = 'delete_record() query execution failed: ['.$query.']';
            }
        }
        else {
            $message = 'delete_record() skipped: the record has been referred in DB';
        }

        // Prepare data
        $json_data = array(
            "result"  => $result,
            "message" => $message,
            "data"    => $mysql_data
        );
    }
}

// Convert PHP array to JSON array
echo json_encode($json_data);

?>
