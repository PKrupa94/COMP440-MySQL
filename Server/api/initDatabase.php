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

    try{
      $conn = @new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_NAME);
      if ($conn -> connect_errno) {
          echo "Failed to connect to MySQL: " . $conn->connect_errno;
          echo "<br/>Error: " . $conn->connect_error;
      }
    } catch( Exception $e ) {
        $outputMsg = msg( 0, 500, $e -> getMessage() );
        exit;
    }
    

  if( $_SERVER[ "REQUEST_METHOD" ] != "GET" ) {
      $outputMsg = msg( 0, 404, "Error: Page Not Found." );
  } else {
      $query = file("DBProject_Summer2021-1.sql");
      $templine = '';
      foreach ($query as $line) {
          if (substr($line, 0, 2) == '--' || $line == '')
              continue;
          $templine .= $line;
          if (substr(trim($line), -1, 1) == ';') {
              // $conn -> query($templine);
              $conn->query($templine);
              $outputMsg = msg( 1, 200, "Database initialization successfully done!!" );  
              $templine = '';
          }
      }

      // $stmt = $conn -> prepare($query);

      // echo 'prepare done';
      // if ( $stmt -> execute() ) {
      //   echo 'success';
      //   // $outputMsg = msg( 0, 200, "Success" );
      // } else {
      //   echo 'failure';
      //   // $outputMsg = msg( 0, 422, "Failure" );
      // }
  }

  echo json_encode( $outputMsg );

?>
