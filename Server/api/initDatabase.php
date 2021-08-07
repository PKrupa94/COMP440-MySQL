<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-control-Allow-credentials: true" );
header( "Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding" );
header( "Access-Control-Allow-Methods: GET, POST" );
header( "Content-Type: application/json; charset=UTF-8" );

$DATABASE_HOST = "localhost";
$DATABASE_NAME = "testcomp440_project";
$DATABASE_USER = "comp440";
$DATABASE_PASSWORD = "pass1234";

# MySQL with PDO_MYSQL

$outputMsg = [];

  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

  //public function dbConn() {
    try{

      $conn = @new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_NAME);

      // Check connection
      if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: " . $conn->connect_errno;
          echo "<br/>Error: " . $conn->connect_error;
      }

      //$conn = new PDO('mysql:host='.$DATABASE_HOST.';dbname='.$DATABASE_NAME, $DATABASE_USER, $DATABASE_PASSWORD);
      //echo "after try";
      //$conn -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

      //return $conn;

    } catch( Exception $e ) {

        echo "test";
        $outputMsg = msg( 0, 500, $e -> getMessage() );
        //$outputMsg;
        exit;
    }
  //}


// if ($conn->connect_errno) {
//     echo "Failed to connect to MySQL: " . $conn->connect_errno;
//     echo "<br/>Error: " . $conn->connect_error;
// }

  //dbConn();
  //$conn = dbConn();



  if( $_SERVER[ "REQUEST_METHOD" ] != "GET" ) {

      $outputMsg = msg( 0, 404, "Error: Page Not Found." );
  } else {

      //echo "after catch";
      $query = file("DBProject_Summer2021-1.sql");
      //echo $query;

      $templine = '';

      foreach ($query as $line) {
      // Skip it if it's a comment
          if (substr($line, 0, 2) == '--' || $line == '')
              continue;

      // Add this line to the current segment
          $templine .= $line;
      // If it has a semicolon at the end, it's the end of the query
          if (substr(trim($line), -1, 1) == ';') {
              // Perform the query
              $conn -> query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />');
              // Reset temp variable to empty
              $templine = '';
          }
      }


      $stmt = $conn -> prepare($query);

      if ( $stmt -> execute() ) {
          $outputMsg = msg( 0, 200, "Success" );
      }else{
          $outputMsg = msg( 0, 422, "Failure" );
      }

  }
  echo json_encode( $outputMsg );

?>
