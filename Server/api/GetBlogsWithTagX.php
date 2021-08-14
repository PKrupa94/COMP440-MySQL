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

  $inputTag = htmlspecialchars( strip_tags( trim( $inputData -> inputTag ) ) );

  $outputMsg = [];
  $bloglist = [];


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

          $blogselect = $conn -> prepare( "SELECT `blogs`.*, `blogstags`.*
                                         FROM `blogs`
                                         INNER JOIN `blogstags`
                                         ON `blogs`.blogid = `blogstags`.blogid
                                         WHERE `blogstags`.tag = :inputTag" );
          $blogselect -> bindValue( ":inputTag" , $inputTag , PDO::PARAM_STR );
          $blogselect -> execute();

          if( $blogselect -> rowCount() >= 1 ) {

            while( $row = $blogselect -> fetch( PDO::FETCH_ASSOC ) ) {

                $bloglist[] = $row;
            }

            $outputMsg = msg( 1, 201, "Successfully retrieved blogs with tag: $inputTag." );

            $outputMsg['blogslist'] = $bloglist;

          } else {

              $outputMsg = msg( 0, 422, "Error: No blogs to display." );
          }

      } catch( Exception $e ) {

          $outputMsg = msg( 0, 500, $e -> getMessage() );
      }
  }

  echo json_encode( $outputMsg );

?>
