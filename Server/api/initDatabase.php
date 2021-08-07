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

$conn = new PDO('mysql:host='.$this -> DATABASE_HOST.';dbname='.$this -> DATABASE_NAME,
$this -> DATABASE_USER,$this -> DATABASE_PASSWORD);
$conn -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_errno;
    echo "<br/>Error: " . $con->connect_error;
}

$outputMsg = [];

function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

if( $_SERVER[ "REQUEST_METHOD" ] != "GET" ) {
    $outputMsg = msg( 0, 404, "Error: Page Not Found." );
} else{
    $query = file_get_contents("DBProject_Summer2021-1.sql");

$stmt = $conn->prepare($query);

if ($stmt->execute()){
    $outputMsg = msg( 0, 200, "Success" );
}else{ 
    $outputMsg = msg( 0, 422, "Failure" );
}

}
echo json_encode( $outputMsg );
?>