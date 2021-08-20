<?php
  header( "Access-Control-Allow-Origin: *" );
  header( "Access-control-Allow-credentials: true" );
  header( "Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding" );
  header( "Access-Control-Allow-Methods: GET, POST" );
  header( "Content-Type: application/json; charset=UTF-8" );

  require __DIR__."/../classes/Database.php";

  $db = new Database();
  $conn = $db -> dbConn();

  $inputData = json_decode( file_get_contents( "php://input" ) );

  $username1 = htmlspecialchars( strip_tags( trim( $inputData -> username1 ) ) );
  $username2 = htmlspecialchars( strip_tags( trim( $inputData -> username2 ) ) );

  $outputMsg = [];
  $userlist = [];

  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {
      $outputMsg = msg( 0, 404, "Error: Page Not Found." );
  } else {
      try {
          $userSelect = $conn -> prepare("SELECT username FROM users WHERE userid IN (
            SELECT 
                DISTINCT leaderid 
             FROM follows 
             WHERE followerid IN (SELECT userid FROM users WHERE username in (
                 :username1)) 
             and leaderid in (SELECT DISTINCT leaderid FROM follows WHERE followerid IN (SELECT userid FROM users WHERE username IN (:username2))));" );

          $userSelect -> bindValue( ":username1" , $username1 , PDO::PARAM_STR );
          $userSelect -> bindValue( ":username2" , $username2 , PDO::PARAM_STR );

          $userSelect -> execute();
  
          if( $userSelect -> rowCount() >= 1 ) {
            while( $row = $userSelect -> fetch( PDO::FETCH_ASSOC ) ) {
                $userlist[] = $row;
            }
            $outputMsg = msg( 1, 201, "Successfully retrieved users" );
            $outputMsg['userlist'] = $userlist;
          } else {
              $outputMsg = msg( 0, 422, "Error: No users to display." );
          }

      } catch( Exception $e ) {
          $outputMsg = msg( 0, 500, $e -> getMessage() );
      }
  }
  echo json_encode( $outputMsg );
?>
