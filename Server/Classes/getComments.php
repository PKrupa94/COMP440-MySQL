<?php
  header( "Access-Control-Allow-Origin: *" );
  header( "Access-control-Allow-credentials: true" );
  header( "Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding" );
  header( "Access-Control-Allow-Methods: GET" );
  header( "Content-Type: application/json; charset=UTF-8" );

  require __DIR__."/../classes/Database.php";

  $db = new Database();
  $conn = $db -> dbConn();

  $inputData = json_decode( file_get_contents( "php://input" ) );

  $blogid = trim( $inputData -> blogid );
  

  $outputMsg = [];
  $Commentlist = [];


  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "GET" ) {
    $outputMsg = msg( 0, 404, "Error: Page Not Found." );
} else {
    try {

        $commentSelect = $conn -> query("SELECT * FROM `comments`");
        $commentSelect -> execute();

        if( $commentSelect -> rowCount() >= 1 ) {

          while( $row = $commentSelect -> fetch( PDO::FETCH_ASSOC ) ) {

              $Commentlist[] = $row;
          }

          $outputMsg = msg( 1, 201, "Successfully retrieved Comments." );

          $outputMsg['commentList'] = $Commentlist;

        } else {
            $outputMsg = msg( 0, 422, "Error: No Commets to display." );
        }

    } catch( Exception $e ) {

        $outputMsg = msg( 0, 500, $e -> getMessage() );
    }
}

echo json_encode( $outputMsg );

?>