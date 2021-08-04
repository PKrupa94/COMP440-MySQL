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

  $subject = htmlspecialchars( strip_tags( trim( $inputData -> subject ) ) );
  $description = htmlspecialchars( strip_tags( trim( $inputData -> description ) ) );
  $tags = htmlspecialchars( strip_tags( trim( $inputData -> tags ) ) );

  $outputMsg = [];

  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $outputMsg = msg( 0, 404, "Error: Page not found." );
  } else if( !isset( $subject ) || !isset( $description ) || !isset( $tags )
           || empty( $subject ) ||  empty( $description ) ||  empty( $tags ) ) {

      $fields = [ "fields" => [ "subject", "description", "tags" ] ];
      $outputMsg = msg( 0, 422, "Error: Please fill out entire form.", $fields );
  } else {

      try {

          $insertBlog = $conn -> prepare( "INSERT INTO `blogs`(`subject`, `description`)
                                           VALUES(:subject, :description)" );
          $insertTag = $conn  -> prepare( "INSERT INTO `blogstags`(`tag`) VALUES(:tags)" );

          $insertBlog -> bindValue( ":subject", $subject, PDO::PARAM_STR );
          $insertBlog -> bindValue( ":description",  $description,  PDO::PARAM_STR );
          $insertTag  -> bindValue( ":tags",  $tags,  PDO::PARAM_STR );

          $insertBlog -> execute();
          $insertTag  -> execute();

          $outputMsg = msg( 1, 1, "Blog successfully inserted." );

      } catch( Exception $e ) {

          $outputMsg = msg( 0, 500, $e -> getMessage() );
      }
  }

  echo json_encode( $outputMsg );

?>
