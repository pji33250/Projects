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
        	0 => 'CALL_ID',
        	1 => 'CHN_NAME',
            2 => 'ENG_NAME',
            3 => 'ANAME',
            4 => 'ENAME',
            5 => 'SNAME',
            6 => 'PNAME',
            7 => 'Actions'
        );

        // getting total number records without any search
        $query = executeQuery("SELECT ID FROM BOOK");
        $totalData = mysqli_num_rows($query);

        // when there is no search parameter then total-number-rows = total-number-filtered-rows.
        $totalFiltered = $totalData;

        // the main query for the fields we need
        $sql = $GLOBALS['AllBookQuery']." WHERE 1=1 ";

        // if there is a search parameter, $requestData['search']['value'] contains search parameter
        if ( !empty($requestData['search']['value']) )
        {
        	$sql .= " AND ( CALL_ID LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR CHN_NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR ENG_NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR A.NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR E.NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR T.NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR S.NAME LIKE '%".$requestData['search']['value']."%' ";
            $sql .= " OR P.NAME LIKE '%".$requestData['search']['value']."%' )";
            // $sql .= " OR EDITION LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR QTY LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR ISBN LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR PUBLISHED_CITY LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR PUBLISHED_YEAR LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR SUBJECTII LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR SUBJECTIII LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR NOTE1 LIKE '%".$requestData['search']['value']."%' ";
            // $sql .= " OR NOTE2 LIKE '%".$requestData['search']['value']."%' )";
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
        $rec = fetchBookByID($myid);
        if ($rec) {
            $result  = 'success';
            $message = 'get_record() success';

            $mysql_data[] = array(
                "CALL_ID"  => $rec['CALL_ID'],
                "CHN_NAME" => $rec['CHN_NAME'],
                "ENG_NAME" => $rec['ENG_NAME'],
                "EDITION"  => $rec['EDITION'],
                "QTY"      => $rec['QTY'],
                "ISBN"     => $rec['ISBN'],
                "AUTHOR_ID"           => $rec['AUTHOR_ID'],
                "AUTHOR_NAME"         => $rec['ANAME'],
                "TRANSLATOR_ID"       => $rec['TRANSLATOR_ID'],
                "TRANSLATOR_NAME"     => $rec['TNAME'],
                "ENGLISH_AUTHOR_ID"   => $rec['ENGLISH_AUTHOR_ID'],
                "ENGLISH_AUTHOR_NAME" => $rec['ENAME'],
                "PUBLISHER_ID"        => $rec['PUBLISHER_ID'],
                "PUBLISHER_NAME" => $rec['PNAME'],
                "PUBLISHED_CITY" => $rec['PUBLISHED_CITY'],
                "PUBLISHED_YEAR" => $rec['PUBLISHED_YEAR'],
                "SUBJECT_ID"     => $rec['SUBJECT_ID'],
                "SUBJECT_NAME"   => $rec['SNAME'],
                "SUBJECTII"      => $rec['SUBJECTII'],
                "SUBJECTIII"     => $rec['SUBJECTIII'],
                "NOTE1"          => $rec['NOTE1'],
                "NOTE2"          => $rec['NOTE2'],
                "RESERVED"       => $rec['RESERVED'],
                "REFERENCE"      => $rec['REFERENCE'],
                "CHECKED_OUT"    => $rec['CHECKED_OUT']
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
        $mycallid = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['CALL_ID']) );
        if (empty($mycallid)) {
            $message = 'add_record() skipped: book-call-id filed cannot be empty';
        }
        else {
            // Check if author exists, if not then add or skip it
            $rec = fetchFirstRecord('BOOK', 'CALL_ID', $mycallid);
            if (!$rec) {
                // Add record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "INSERT INTO BOOK SET CALL_ID='$mycallid', ADDED_DATE='$mytime'";
                if (isset($_GET['CHN_NAME']))  { $query .= ", CHN_NAME  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['CHN_NAME'])  . "'"; }
                if (isset($_GET['ENG_NAME']))  { $query .= ", ENG_NAME  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ENG_NAME'])  . "'"; }
                if (isset($_GET['EDITION']))   { $query .= ", EDITION   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['EDITION'])   . "'"; }
                if (isset($_GET['QTY']))       { $query .= ", QTY       = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['QTY'])       . "'"; }
                if (isset($_GET['AUTHOR_ID'])) { $query .= ", AUTHOR_ID = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['AUTHOR_ID']) . "'"; }
                if (isset($_GET['ENGLISH_AUTHOR_ID'])) { $query .= ", ENGLISH_AUTHOR_ID = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ENGLISH_AUTHOR_ID']) . "'"; }
                if (isset($_GET['TRANSLATOR_ID']))     { $query .= ", TRANSLATOR_ID     = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['TRANSLATOR_ID'])     . "'";   }
                if (isset($_GET['PUBLISHER_ID']))      { $query .= ", PUBLISHER_ID      = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHER_ID'])      . "'";   }
                if (isset($_GET['PUBLISHED_CITY']))    { $query .= ", PUBLISHED_CITY    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHED_CITY'])    . "'";   }
                if (isset($_GET['PUBLISHED_YEAR']))    { $query .= ", PUBLISHED_YEAR    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHED_YEAR'])    . "'";   }
                if (isset($_GET['ISBN']))       { $query .= ", ISBN       = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ISBN'])       . "'";   }
                if (isset($_GET['SUBJECT_ID'])) { $query .= ", SUBJECT_ID = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['SUBJECT_ID']) . "'";   }
                if (isset($_GET['SUBJECTII']))  { $query .= ", SUBJECTII  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['SUBJECTII'])  . "'";   }
                if (isset($_GET['SUBJECTIII'])) { $query .= ", SUBJECTIII = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['SUBJECTIII']) . "'";   }
                if (isset($_GET['NOTE1']))      { $query .= ", NOTE1      = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['NOTE1'])      . "'";   }
                if (isset($_GET['NOTE2']))      { $query .= ", NOTE2      = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['NOTE2'])      . "'";   }
                if (isset($_GET['RESERVED']))   { $query .= ", RESERVED   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['RESERVED'])   . "'";   }
                if (isset($_GET['REFERENCE']))  { $query .= ", REFERENCE  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['REFERENCE'])  . "'";   }

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
        $mycallid = trim( mysqli_real_escape_string($GLOBALS['db'], $_GET['CALL_ID']) );
        if (empty($mycallid)) {
            $message = 'edit_record() skipped: book-call-id filed cannot be empty';
        }
        else {
            // Check if record exists, if not then add or skip it
            $existingRecordQuery = "SELECT * FROM BOOK WHERE CALL_ID='$mycallid' AND ID<>$myid";
            $rec = fetchFirstRecordByQuery($existingRecordQuery);
            if (!$rec) {
                // Update record
                $mytime = gmdate('Y-m-d H:i:s');
                $query = "UPDATE BOOK SET CALL_ID='$mycallid', UPDATED_DATE='$mytime'";
                if (isset($_GET['CHN_NAME']))  { $query .= ",CHN_NAME  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['CHN_NAME']) . "'"; }
                if (isset($_GET['ENG_NAME']))  { $query .= ",ENG_NAME  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ENG_NAME']) . "'"; }
                if (isset($_GET['EDITION']))   { $query .= ",EDITION   = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['EDITION'])  . "'"; }
                if (isset($_GET['QTY']))       { $query .= ",QTY       = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['QTY']);       }
                if (isset($_GET['AUTHOR_ID'])) { $query .= ",AUTHOR_ID = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['AUTHOR_ID']); }
                if (isset($_GET['ENGLISH_AUTHOR_ID'])) { $query .= ",ENGLISH_AUTHOR_ID = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['ENGLISH_AUTHOR_ID']); }
                if (isset($_GET['TRANSLATOR_ID']))     { $query .= ",TRANSLATOR_ID     = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['TRANSLATOR_ID']);     }
                if (isset($_GET['PUBLISHER_ID']))      { $query .= ",PUBLISHER_ID      = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHER_ID']);      }
                if (isset($_GET['PUBLISHED_YEAR']))    { $query .= ",PUBLISHED_YEAR    = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHED_YEAR']);    }
                if (isset($_GET['PUBLISHED_CITY']))    { $query .= ",PUBLISHED_CITY    = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['PUBLISHED_CITY']) . "'"; }
                if (isset($_GET['ISBN']))       { $query .= ", ISBN       = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['ISBN'])       . "'"; }
                if (isset($_GET['SUBJECT_ID'])) { $query .= ", SUBJECT_ID = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['SUBJECT_ID']);   }
                if (isset($_GET['SUBJECTII']))  { $query .= ", SUBJECTII  = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['SUBJECTII'])  . "'"; }
                if (isset($_GET['SUBJECTIII'])) { $query .= ", SUBJECTIII = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['SUBJECTIII']) . "'"; }
                if (isset($_GET['NOTE1']))      { $query .= ", NOTE1      = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['NOTE1'])      . "'"; }
                if (isset($_GET['NOTE2']))      { $query .= ", NOTE2      = '" . mysqli_real_escape_string($GLOBALS['db'], $_GET['NOTE2'])      . "'"; }
                if (isset($_GET['RESERVED']))   { $query .= ", RESERVED   = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['RESERVED']);   }
                if (isset($_GET['REFERENCE']))  { $query .= ", REFERENCE  = " . mysqli_real_escape_string($GLOBALS['db'], $_GET['REFERENCE']);  }

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
                $message = 'edit_record() skipped: the record ('.$mycallid.') exists already';
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
        $queryBookCheck = "SELECT * FROM CIRCULATION WHERE BOOK_ID=$myid";
        $rec = fetchFirstRecordByQuery($queryBookCheck);
        if (!$rec) {
            // Only if the author has not been referred, can be deleted from DB
            $query = "DELETE FROM BOOK WHERE ID=$myid";
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
