<?php
  // Globals
  //
  $debugenabled = false;
  $db = dbConnect();

  
  function debug($tag, $message)
  {
    if ($GLOBALS['debugenabled'] == true)
    {
      if ($tag == "hr")
      {
        echo "<hr>";
      }
      else
      {
        echo "<$tag>$message</$tag>";
      }
    }
  }

  // Execute the specified query
  //
  function executeQuery($query)
  {


    // Execute the query
    $queryResult = mysqli_query($GLOBALS['db'], $query);

    if (!$queryResult)
    {
      $result  = 'error';
      $message = 'query error';
    }
    else
    {
        $result  = 'success';
        $message = 'query success';
    }
    //print_r($message);
    //print_r($queryResult);
    //debug("p", "<br>result: $result, message: $message<br>");

    return $queryResult;
  }

  // Open database connection
  //
  function dbConnect()
  {
    // Database details
    $db_server   = 'localhost';
    $db_username = 'peter';
    $db_password = 'peterpeter';
    $db_name     = 'ChnLibrary';

    // Connect to database
    $db = mysqli_connect($db_server, $db_username, $db_password, $db_name);
    mysqli_set_charset($db, "utf8");
    return $db;
  }

  // Close database connection
  function dbClose($db)
  {
    // Close database connection
    mysqli_close($db);
  }





?>
