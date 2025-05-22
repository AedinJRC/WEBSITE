<?php
   $hostname = "localhost";
   $username = "dlmprvzg_vehicle";
   $password = "Vehicle1234";
   $dbname = "dlmprvzg_vehicle";
   
 
   try {
      // Enable MySQLi exceptions
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

      // Attempt to connect to the database
      $conn = mysqli_connect($hostname, $username, $password, $dbname);
   } catch (mysqli_sql_exception $e) {
      // Redirect to dberror.php with the error message as a query parameter
      $error_message = urlencode($e->getMessage()); // Encode the error message for the URL
      header("Location: dberror.php?error=$error_message");
      exit; // Ensure no further code is executed
   }
?>
