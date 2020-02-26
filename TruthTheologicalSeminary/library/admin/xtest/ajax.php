<?php
  require_once ( $_SERVER['DOCUMENT_ROOT'].'/lib/sqlapis.php');

  $term=$_POST['query'];
  $query = "SELECT * FROM AUTHOR WHERE NAME LIKE '%$term%'";
  $result = executeQuery($query);

  $data=array();
  while($row = mysqli_fetch_array($result))
  {
    array_push($data, $row['ID']."|".$row['NAME']);
  }
  echo json_encode($data);
?>
