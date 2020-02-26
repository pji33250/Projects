<?php
  require_once 'sqlcommon.php';

  //
  //Code examples, not used at all
  //
  function insertLogFile($userIdentifier, $directoryName, $fileName, $creationDate, $logAlerts)
  {
    $logFile['Identifier'] = $userIdentifier;
    $logFile['DirectoryName'] = $directoryName;
    $logFile['FileName'] = $fileName;
    $logFile['CreationDate'] = $creationDate;
    $logFile['LogAlerts'] = $logAlerts;

    insertDuplicatesIgnored('LogFile', $logFile);
  }

  function insertTransmission($type, $id)
  {
    $transmission['Type'] = $type;
    $transmission['Identifier'] = $id;
    $transmission['TransmissionDate'] = gmdate('Y-m-d H:i:s');

    insertDuplicatesIgnored('Transmissions', $transmission);
  }

  function insertSystemData($data)
  {
    insertDuplicatesIgnored('SystemData', $data);
  }


  // Inserts a JSON session record into the DB
  //
  function insertRecordInDB($record)
  {
    debug("h3", "insertRecordInDB");
    debug("hr", "");

    insertPatient($record['Patient']);
    insertGenerator($record['Generator']);
    insertSession($record['Session']);

    debug("hr", "");
  }

  // Inserts a JSON patient into the DB
  //
  function insertPatient($incomingPatient)
  {
    debug("h3", "insertPatient");

    $dbPatient = fetchFirstRow("Patient", "Identifier", $incomingPatient['Identifier']);

    if ($dbPatient)
    {
      $dateFormat = "m/d/Y g:i:s";

      debug("p", $incomingPatient['LastUpdateDate']);
      debug("p", $dbPatient['LastUpdateDate']);

      $incomingDate = new DateTime($incomingPatient['LastUpdateDate'], new DateTimeZone('UTC'));
      $incomingDate = $incomingDate->format($dateFormat);

      $dbDate = new DateTime($dbPatient['LastUpdateDate'], new DateTimeZone('UTC'));
      $dbDate = $dbDate->format($dateFormat);

      debug("p", $incomingDate);
      debug("p", $dbDate);

      if ($incomingDate > $dbDate)
      {
        updateFields("Patient", $incomingPatient, "Identifier", $incomingPatient['Identifier'], array("FirstName", "LastName", "LastUpdateDate"));
      }
    }
    else
    {
      insertDuplicatesIgnored("Patient", $incomingPatient);
    }
  }

  // Inserts a JSON Generator into the DB
  //
  function insertGenerator($generator)
  {
    debug("h3", "insertGenerator");
    insertDuplicatesIgnored('Generator', $generator);
  }

  // Inserts a JSON Session into the DB
  //
  function insertSession($session)
  {
    debug("h3", "insertSession");

    $Leads = $session['Leads'];
    unset($session['Leads']);

    $Programs = $session['Programs'];
    unset($session['Programs']);

    $SessionToPrograms = $session['SessionToPrograms'];
    unset($session['SessionToPrograms']);

    $Stimsets = $session['Stimsets'];
    unset($session['Stimsets']);

    insertDuplicatesIgnored('Session', $session);

    insertLeads($Leads);
    insertPrograms($Programs);
    insertSessionToPrograms($SessionToPrograms);
    insertStimsets($Stimsets);
  }

  function insertLeads($leads)
  {
    debug("h3", "insertLeads");
    foreach ($leads as $lead)
    {
      insertDuplicatesIgnored('Leads', $lead);
    }
  }

  function insertPrograms($Programs)
  {
    debug("h3", "insertPrograms");
    foreach ($Programs as $Program)
    {
      insertDuplicatesIgnored('Programs', $Program);
    }
  }

  function insertSessionToPrograms($SessionToPrograms)
  {
    debug("h3", "insertSessionToPrograms");
    foreach ($SessionToPrograms as $SessionToProgram)
    {
      insertDuplicatesIgnored('SessionToPrograms', $SessionToProgram);
    }
  }

  function insertStimsets($Stimsets)
  {
    debug("h3", "insertStimsets");

    foreach ($Stimsets as $Stimset)
    {
      insertDuplicatesIgnored('Stimsets', $Stimset);
    }
  }

  // Insert the key value pairs specified by the $map argument into the table
  // specified by the $table argument.  If already exists, then entry is not
  // inserted
  function insertDuplicatesIgnored($table, $map)
  {
    debug("h3", "insertDuplicatesIgnored");

    $map = formatNullValues($map);
    $query = "";
    $keys = implode(", ", array_keys($map));
    $values = "'" . implode("','", array_values($map)) . "'";

    $query =
      "INSERT INTO $table " .
      "($keys) VALUES ($values);";

    debug("p", "$query");

    $queryResult = executeQuery($query);
  }

  function updateFields($table, $pairs, $keyToMatch, $valueToMatch, $keysToUpdate)
  {
    debug("h3", "updateFields");

    $query = "UPDATE $table SET ";

    foreach ($keysToUpdate as $key)
    {
      $query .= "$key = '$pairs[$key]', ";
    }
    $query = rtrim($query,', ');

    $query .= " WHERE $keyToMatch = '$valueToMatch';";

    debug("p", $query);
    $queryResult = executeQuery($query);
  }

  function clearAllData()
  {
    $queryResult = executeQuery("SET foreign_key_checks = 0;");
    $queryResult = executeQuery("DELETE FROM Generator;");
    $queryResult = executeQuery("DELETE FROM Leads;");
    $queryResult = executeQuery("DELETE FROM LogFile;");
    $queryResult = executeQuery("DELETE FROM Patient;");
    $queryResult = executeQuery("DELETE FROM Programs;");
    $queryResult = executeQuery("DELETE FROM Session;");
    $queryResult = executeQuery("DELETE FROM SessionToPrograms;");
    $queryResult = executeQuery("DELETE FROM Stimsets;");
    $queryResult = executeQuery("DELETE FROM SystemData;");
    $queryResult = executeQuery("DELETE FROM Transmissions;");
    $queryResult = executeQuery("SET foreign_key_checks = 1;");

    return $queryResult;
  }
?>
