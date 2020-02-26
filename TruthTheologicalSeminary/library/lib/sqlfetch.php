<?php
  require_once 'sqlcommon.php';


//
//Search the all books, with author/translator/english author/subject/publisher names, etc.
//

$AllBookQuery = "SELECT B.*, A.NAME ANAME, T.NAME TNAME, E.NAME ENAME, S.NAME SNAME, P.NAME PNAME ".
                " FROM BOOK B ".
                " LEFT JOIN AUTHOR A ON B.AUTHOR_ID=A.ID ".
                " LEFT JOIN AUTHOR T ON B.TRANSLATOR_ID=T.ID ".
                " LEFT JOIN AUTHOR E ON B.ENGLISH_AUTHOR_ID=E.ID ".
                " LEFT JOIN SUBJECT S ON B.SUBJECT_ID=S.ID ".
                " LEFT JOIN PUBLISHER P ON B.PUBLISHER_ID=P.ID ";

function fetchAllBooks()
{
    return fetchBookByID('');
}

function fetchBookByID($bookId)
{
    //build the query, by inserting the ID to the pre-defined all-book-search-query
    $query = $GLOBALS['AllBookQuery'];
    if ($bookId)
    {
        $query = $query."WHERE B.ID=".$bookId;
        return fetchFirstRecordByQuery($query);
    }
    else {
        return executeQuery($query);
    }
}

function fetchFirstRecord($table, $field, $value)
{
    $query = "SELECT * FROM $table WHERE $field = '$value'";
    return fetchFirstRecordByQuery($query);
}

function fetchFirstRecordByQuery($query)
{
    $firstRecord = null;

    $queryResult = executeQuery($query);
    if ($queryResult)
    {
        $num = mysqli_num_rows($queryResult);
        if ($num > 0)
        {
            $firstRecord = mysqli_fetch_assoc($queryResult);
        }
    }
    return $firstRecord;
}


//
//Search for books with different categories and the search string
//
function fetchSearchBooksByTerm($cat, $text)
{
    $query = null;

    if ($cat == "title")
    	$query = $GLOBALS['AllBookQuery']." WHERE (B.CHN_NAME LIKE '%$text%' OR B.ENG_NAME LIKE '%$text%')";
    else if ($cat == "subject")
    	$query = $GLOBALS['AllBookQuery']." WHERE (S.NAME LIKE '%$text%' OR B.SUBJECTII LIKE '%$text%' OR B.SUBJECTIII LIKE '%$text%')";
    else if ($cat == "author")
    	$query = $GLOBALS['AllBookQuery']." WHERE (A.NAME LIKE '%$text%' OR E.NAME LIKE '%$text%' OR T.NAME LIKE '%$text%')";
    else if ($cat == "publisher")
    	$query = $GLOBALS['AllBookQuery']." WHERE (P.NAME LIKE '%$text%')";
    else if ($cat == "callid")
    	$query = $GLOBALS['AllBookQuery']." WHERE (B.CALL_ID LIKE '%$text%')";

    //Limit first 100 records, if more than 100, user should narrow search criteria
    //$query = $query." LIMIT 100";

    //print_r($query);
    $queryResult = executeQuery($query);

    return $queryResult;
}



function fetchSearchBooksById($cat, $myid)
{
    $query = null;

    if ($cat == "title")
    	$query = $GLOBALS['AllBookQuery']." WHERE B.ID=$myid";
    else if ($cat == "subject")
    	$query = $GLOBALS['AllBookQuery']." WHERE S.ID=$myid";
    else if ($cat == "author")
    	$query = $GLOBALS['AllBookQuery']." WHERE B.AUTHOR_ID=$myid OR B.TRANSLATOR_ID=$myid OR B.ENGLISH_AUTHOR_ID=$myid";
    else if ($cat == "publisher")
    	$query = $GLOBALS['AllBookQuery']." WHERE B.PUBLISHER_ID=$myid";

    //Limit first 100 records, if more than 100, user should narrow search criteria
    //$query = $query." LIMIT 100";

    //print_r($query);
    $queryResult = executeQuery($query);

    return $queryResult;
}

// function fetchAllAuthors() { return fetchAuthorByID(''); }
// function fetchAuthorByID($id)
// {
//   $query = "SELECT * FROM AUTHOR ORDER BY NAME";
//   if ($id) { $query = "SELECT * FROM AUTHOR WHERE ID=$id"; }
//   return executeQuery($query);
// }
//
//
// function fetchAllSubjects() { return fetchSubjectByID(''); }
// function fetchSubjectByID($id)
// {
//   $query = "SELECT * FROM SUBJECT ORDER BY NAME";
//   if ($id) {  $query = "SELECT * FROM SUBJECT WHERE ID=$id"; }
//   return executeQuery($query);
// }
//
// function fetchAllPublishers() { return fetchPublisherByID(''); }
// function fetchPublisherByID($id)
// {
//   $query = "SELECT * FROM PUBLISHER ORDER BY NAME";
//   if ($id) { $query = "SELECT * FROM PUBLISHER WHERE ID=$id"; }
//   return executeQuery($query);
// }




//TO BE DELETED ================
// function fetchBookTableData()
// {
// 	// Prepare array
// 	$mysql_data = array();
//
// 	$queryResult = executeQuery("SELECT * FROM BOOK ORDER BY CHN_NAME, ENG_NAME");
//
// 	if (!$queryResult)
// 	{
// 		$result  = 'error';
// 		$message = 'query error';
// 	}
// 	else
// 	{
// 		$result  = 'success';
// 		$message = 'query success';
// 		while ($book= mysqli_fetch_array($queryResult))
// 		{
// 			$mysql_data[] = array(
// 			  "ID"   		=> $book['ID'],
// 			  "CALL_ID"  	=> $book['CALL_ID'],
// 			  "CHN_NAME" 	=> $book['CHN_NAME'],
// 			  "ENG_NAME"    => $book['ENG_NAME'],
// 			  "AUTHOR"    	=> fetchAuthorName($book['AUTHOR_ID']),
// 			  "PUBLISHER"   => fetchPublisherName($book['PUBLISHER_ID']),
// 			  "SUBJECT"   	=> fetchSubjectName($book['SUBJECT_ID'])
// 			);
// 		}
// 	}
//
// 	// Prepare data
// 	$data = array(
// 	  "result"  => $result,
// 	  "message" => $message,
// 	  "data"    => $mysql_data
// 	);
//
// 	// Convert PHP array to JSON array
// 	$json_data = json_encode($data);
//
// 	return $json_data;
// }
//
// function fetchAuthorName($Id)
// {
// 	$aName = null;
//
// 	$query = "SELECT NAME FROM AUTHOR WHERE ID = ".$Id;
// 	$queryResult = executeQuery($query);
//
// 	if ($queryResult)
// 	{
// 		$author = mysqli_fetch_assoc($queryResult);
// 		$aName = $author["NAME"];
// 	}
// 	return $aName;
// }
// function fetchPublisherName($Id)
// {
// 	$aName = null;
//
// 	$query = "SELECT NAME FROM PUBLISHER WHERE ID = ".$Id;
// 	$queryResult = executeQuery($query);
//
// 	if ($queryResult)
// 	{
// 		$publisher = mysqli_fetch_assoc($queryResult);
// 		$aName = $publisher["NAME"];
// 	}
// 	return $aName;
// }
// function fetchSubjectName($Id)
// {
// 	$aName = null;
//
// 	$query = "SELECT NAME FROM CATEGORY WHERE ID = ".$Id;
// 	$queryResult = executeQuery($query);
//
// 	if ($queryResult)
// 	{
// 		$subject = mysqli_fetch_assoc($queryResult);
// 		$aName = $subject["NAME"];
// 	}
// 	return $aName;
// }
//
//
//
// function fetchEngTitleList($term)
// {
// 	$data_array = array();
//
// 	$query = "SELECT ENG_NAME FROM BOOK WHERE ENG_NAME LIKE '%".$term."%'";
// 	$queryResult = executeQuery($query);
//
// 	if ($queryResult)
// 	{
// 		while ($row= mysqli_fetch_array($queryResult))
// 		{
// 			$data_array[] = $row['ENG_NAME'];
// 		}
// 	}
// 	echo json_encode($data_array);;
// }
// function fetchCHNTitleList($term)
// {
// 	$data_array = array();
//
// 	$query = "SELECT CHN_NAME FROM BOOK WHERE CHN_NAME LIKE '%".$term."%'";
// 	$queryResult = executeQuery($query);
//
// 	if ($queryResult)
// 	{
// 		while ($row= mysqli_fetch_array($queryResult))
// 		{
// 			$data_array[] = $row['CHN_NAME'];
// 		}
// 	}
// 	echo json_encode($data_array);;
// }
// function fetchAuthorList($term)
// {
// 	$data_array = array();
//
// 	$query = "SELECT NAME FROM AUTHOR WHERE NAME LIKE '%".$term."%'";
// 	$queryResult = executeQuery($query);
//
// 	if ($queryResult)
// 	{
// 		while ($row= mysqli_fetch_array($queryResult))
// 		{
// 			$data_array[] = $row['NAME'];
// 		}
// 	}
// 	echo json_encode($data_array);;
// }

?>
