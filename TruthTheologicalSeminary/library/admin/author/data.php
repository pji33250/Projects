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
            2 => 'Actions'
        );

        // getting total number records without any search
        $query = executeQuery("SELECT ID FROM AUTHOR");
        $totalData = mysqli_num_rows($query);

        // when there is no search parameter then total-number-rows = total-number-filtered-rows.
        $totalFiltered = $totalData;

        // the main query for the fields we need
        $sql = "SELECT ID, NAME FROM AUTHOR WHERE 1=1";

        // if there is a search parameter, $requestData['search']['value'] contains search parameter
        if ( !empty($requestData['search']['value']) )
        {
        	$sql .= " AND ( NAME LIKE '%".$requestData['search']['value']."%' )";
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
    elseif ($job == 'get_record')   // Get one record for editing
    {
        $myid = $_GET['id'];
        $rec = fetchFirstRecord('AUTHOR', 'ID', $myid);
        if ($rec) {
            $result  = 'success';
            $message = 'get_record() success';
            $mysql_data[] = array(
                "NAME" => $rec['NAME']
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
    elseif ($job == 'add_record') // Add new record
    {
        $myname = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['AUTHOR_NAME']) );
        if (empty($myname)) {
            $message = 'add_record() skipped: author-name cannot be empty';
        }
        else {
            // Check if record exists, if not then add or skip it
            $rec = fetchFirstRecord('AUTHOR', 'NAME', $myname);
            if (!$rec) {
                // Add record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "INSERT INTO AUTHOR SET NAME = '$myname', ADDED_DATE = '$mytime'";
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
        $myname = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['AUTHOR_NAME']) );
        if (empty($myname)) {
            $message = 'edit_record() skipped: author-name cannot be empty';
        }
        else {
            // Check if record exists, if not then add or skip it
            $existingRecordQuery = "SELECT * FROM AUTHOR WHERE NAME = '$myname' AND ID <> $myid";
            $rec = fetchFirstRecordByQuery($existingRecordQuery);
            if (!$rec) {
                // update record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "UPDATE AUTHOR SET NAME='$myname', UPDATED_DATE='$mytime' WHERE ID=$myid";
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
        $queryBookCheck = "SELECT ID FROM BOOK WHERE (AUTHOR_ID=$myid OR TRANSLATOR_ID=$myid OR ENGLISH_AUTHOR_ID=$myid)";
        $rec = fetchFirstRecordByQuery($queryBookCheck);
        if (!$rec) {
            // Only if the author has not been referred, can be deleted from DB
            $query = "DELETE FROM AUTHOR WHERE ID=$myid";
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
