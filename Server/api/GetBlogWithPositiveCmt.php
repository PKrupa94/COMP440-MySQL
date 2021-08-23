<?php

header( "Access-Control-Allow-Origin: *" );
header( "access-control-allow-credentials: true" );
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header('Access-Control-Allow-Methods: GET, POST');
header( "Content-Type: application/json; charset=UTF-8" );

require __DIR__."/../classes/Database.php";

$db = new Database();
$conn = $db -> dbConn();

$inputData = json_decode( file_get_contents( "php://input" ) );
$userId = htmlspecialchars( strip_tags( trim( $inputData -> userId ) ) );

$outputMsg = [];
$bloglist = [];

function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {
    $outputMsg = msg( 0, 404, "Error: Page Not Found." );
}else{
    try{
        $blogSelect = $conn -> prepare("SELECT * FROM blogs WHERE userid = :userid AND blogid IN ( 
            SELECT DISTINCT blogid FROM comments WHERE blogid NOT IN (
            SELECT 
                 DISTINCT blogid 
             FROM comments 
             WHERE sentiment = 'negative'))");
          $blogSelect -> bindValue( ":userid" , $userId , PDO::PARAM_STR );
          $blogSelect -> execute();
  
          if( $blogSelect -> rowCount() >= 1 ) {
            while( $row = $blogSelect -> fetch( PDO::FETCH_ASSOC ) ) {
                $bloglist[] = $row;
            }
            $outputMsg = msg( 1, 201, "Successfully retrieved Blogs" );
            $outputMsg['bloglist'] = $bloglist;
          } else {
              $outputMsg = msg( 0, 422, "Error: No Blogs to display." );
          }
    }catch( Exception $e ){
        $outputMsg = msg( 0, 500, $e -> getMessage() );
    }
}
echo json_encode($outputMsg);

?>