<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');

$job = '';
if (isset($_GET['job'])) { $job = $_GET['job']; }

if ($job == 'get_records')
{
    // storing  request (ie, get/post) global array to a variable
    $requestData= $_REQUEST;

    // datatable column index  => database column name
    $columns = array(
    	0 => 'ID',
    	1 => 'NAME',
    	2 => 'Action'
    );

    // getting total number records without any search
    $query=executeQuery("SELECT ID FROM AUTHOR");
    $totalData = mysqli_num_rows($query);
    $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

    $sql = "SELECT * FROM AUTHOR WHERE 1=1";
    if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    	$sql .= " AND ( NAME LIKE '%".$requestData['search']['value']."%' )";

        $query=executeQuery($sql);
        $totalFiltered = mysqli_num_rows($query);
    }

    $sql .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
    $query=executeQuery($sql);


    $mysql_data = array();
    while( $row=mysqli_fetch_assoc($query) ) {  // preparing an array
    	$nestedData=array();

    	foreach($row as $index=>$value) {
    		$nestedData[$index] = $value;
    	}

    	$mysql_data[] = $nestedData;
    }

    $json_data = array(
    			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    			"recordsTotal"    => intval( $totalData ),  // total number of records
    			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    			"records"         => $mysql_data   // total data array
    );
}
echo json_encode($json_data);  // send data as json format

?>
