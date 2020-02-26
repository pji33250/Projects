<?php
    require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');

    $data=array();
    if (isset($_POST["query"]) && isset($_POST["table"]))
    {
        $term = mysqli_real_escape_string($GLOBALS['db'], $_POST['query']);
        $term = trim($term);

        //only do a search if the term has at least 2 characters
        if (!empty($term) && strlen($term)>=2)
        {
            $mytable=$_POST['table'];
            $query = "SELECT ID, NAME FROM $mytable WHERE NAME LIKE '%$term%'";
            $result = executeQuery($query);

            while($row = mysqli_fetch_array($result))
            {
                array_push($data, $row['ID']."|".$row['NAME']);
            }
        }
    }
    echo json_encode($data);
?>
