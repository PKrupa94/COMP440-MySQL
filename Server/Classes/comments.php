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

  // turn special chars into html-friendly characters
  // and also strip html/php tags from the non-empty input data
  $sentiment = htmlspecialchars( strip_tags( trim( $inputData -> sentiment ) ) );
  $description = htmlspecialchars( strip_tags( trim( $inputData -> description ) ) );
  $blogid = htmlspecialchars( strip_tags( trim( $inputData -> blogid ) ) );
  $authorid = htmlspecialchars( strip_tags( trim( $inputData -> authorid )));

  $outputMsg = [];

  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess, // 0 for false, 1 for true
      "Status" => $status, // status code
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {
      $outputMsg = msg( 0, 404, "Error: Page not found." );
  }  else {
          try {
                  $insertComment = $conn -> prepare("INSERT INTO `comments`(`sentiment`, `description`, `blogid`, `authorid`)
                                                   VALUES(:sentiment, :description, :blogid, :authorid)");
                  $insertComment -> bindValue( ":sentiment", $sentiment, PDO::PARAM_STR );
                  $insertComment -> bindValue( ":description" ,$description , PDO::PARAM_STR );
                  $insertComment -> bindValue( ":blogid" , $blogid, PDO::PARAM_STR );
                  $insertComment -> bindValue( ":authorid" , $authorid, PDO::PARAM_STR );
                  $insertComment -> execute();
                  $outputMsg = msg( 1, 201, "Comment is succesfully added");
              
          } catch( Exception $e ) {

              $outputMsg = msg( 0, 500, $e -> getMessage() );
          }
      }
  echo json_encode( $outputMsg );

?>
