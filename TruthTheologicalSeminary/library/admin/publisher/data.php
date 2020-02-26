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
            0 => 'ID',
            1 => 'NAME',
            2 => 'PHONE_NUMBER',
            3 => 'WEBSITE',
            4 => 'CITY',
            5 => 'COUNTRY',
            6 => 'Actions'
        );

        // getting total number records without any search
        $query = executeQuery("SELECT ID FROM PUBLISHER");
        $totalData = mysqli_num_rows($query);

        // when there is no search parameter then total-number-rows = total-number-filtered-rows.
        $totalFiltered = $totalData;

        // the main query for the fields we need
        $sql = "SELECT ID, NAME, PHONE_NUMBER, WEBSITE, CITY, COUNTRY FROM PUBLISHER WHERE 1=1";

        // if there is a search parameter, $requestData['search']['value'] contains search parameter
        if ( !empty($requestData['search']['value']) )
        {
        	$sql .= " AND ( NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR PHONE_NUMBER LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR WEBSITE LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR CITY LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR COUNTRY LIKE '%".$requestData['search']['value']."%' )";

            $query = executeQuery($sql);

            // got total-number-of-filtered-rows
            $totalFiltered = mysqli_num_rows($query);
        }

        // added sorting, limit to the query
        $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
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
        $rec = fetchFirstRecord('PUBLISHER', 'ID', $myid);
        if ($rec) {
            $result  = 'success';
            $message = 'get_record() success';
            $mysql_data[] = array(
                "NAME"         => $rec['NAME'],
                "PHONE_NUMBER" => $rec['PHONE_NUMBER'],
                "FAX_NUMBER"   => $rec['FAX_NUMBER'],
                "WEBSITE"      => $rec['WEBSITE'],
                "ADDRESS" => $rec['ADDRESS'],
                "CITY"    => $rec['CITY'],
                "STATE"   => $rec['STATE'],
                "ZIP"     => $rec['ZIP'],
                "COUNTRY" => $rec['COUNTRY'],
            );
        }
        else {
            $message = 'get_record() failed: record not found';
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
      $myname = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHER_NAME']) );
      if (empty($myname)) {
          $message = 'add_record() skipped: publisher-name cannot be empty';
      }
      else {
            // Check if author exists, if not then add or skip it
            $rec = fetchFirstRecord('PUBLISHER', 'NAME', $myname);
            if (!$rec) {
                // Add publisher
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "INSERT INTO PUBLISHER SET NAME='$myname', ADDED_DATE='$mytime'";
                if (isset($_GET['PHONE_NUMBER'])) { $query .= ", PHONE_NUMBER = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PHONE_NUMBER']) . "'"; }
                if (isset($_GET['FAX_NUMBER']))   { $query .= ", FAX_NUMBER   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['FAX_NUMBER'])   . "'"; }
                if (isset($_GET['WEBSITE'])) { $query .= ", WEBSITE = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['WEBSITE']) . "'"; }
                if (isset($_GET['EMAIL']))   { $query .= ", EMAIL   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['EMAIL'])   . "'"; }
                if (isset($_GET['ADDRESS'])) { $query .= ", ADDRESS = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ADDRESS']) . "'"; }
                if (isset($_GET['CITY']))    { $query .= ", CITY    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['CITY'])    . "'"; }
                if (isset($_GET['STATE']))   { $query .= ", STATE   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['STATE'])   . "'"; }
                if (isset($_GET['ZIP']))     { $query .= ", ZIP     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ZIP'])     . "'"; }
                if (isset($_GET['COUNTRY'])) { $query .= ", COUNTRY = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['COUNTRY']) . "'"; }

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
                $message = 'add_record() skipped: the record ('.$myname.') exists already';
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
        $myname = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHER_NAME']) );
        if (empty($myname)) {
            $message = 'edit_record() skipped: publisher-name cannot be empty';
        }
        else {
            // Check if record exists, if not then add or skip it
            $existingRecordQuery = "SELECT * FROM PUBLISHER WHERE NAME='$myname' AND ID<>$myid";
            $rec = fetchFirstRecordByQuery($existingRecordQuery);
            if (!$rec) {
                // update record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "UPDATE PUBLISHER SET NAME='$myname', UPDATED_DATE='$mytime'";
                if (isset($_GET['PHONE_NUMBER'])) { $query .= ", PHONE_NUMBER = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PHONE_NUMBER']) . "'"; }
                if (isset($_GET['FAX_NUMBER']))   { $query .= ", FAX_NUMBER   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['FAX_NUMBER'])   . "'"; }
                if (isset($_GET['WEBSITE'])) { $query .= ", WEBSITE = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['WEBSITE']) . "'"; }
                if (isset($_GET['EMAIL']))   { $query .= ", EMAIL   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['EMAIL'])   . "'"; }
                if (isset($_GET['ADDRESS'])) { $query .= ", ADDRESS = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ADDRESS']) . "'"; }
                if (isset($_GET['CITY']))    { $query .= ", CITY    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['CITY'])    . "'"; }
                if (isset($_GET['STATE']))   { $query .= ", STATE   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['STATE'])   . "'"; }
                if (isset($_GET['ZIP']))     { $query .= ", ZIP     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ZIP'])     . "'"; }
                if (isset($_GET['COUNTRY'])) { $query .= ", COUNTRY = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['COUNTRY']) . "'"; }
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
        $queryBookCheck = "SELECT ID FROM BOOK WHERE PUBLISHER_ID=$myid";
        $rec = fetchFirstRecordByQuery($queryBookCheck);
        if (!$rec) {
            // Only if the author has not been referred, can be deleted from DB
            $query = "DELETE FROM PUBLISHER WHERE ID=$myid";
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
