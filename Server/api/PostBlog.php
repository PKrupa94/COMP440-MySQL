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

  $userid = htmlspecialchars( strip_tags( trim( $inputData -> userid ) ) );
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

  $userIDExists = $conn -> prepare( "SELECT `userid` FROM `users` WHERE `userid`=:userid" );
  $userIDExists -> bindValue( ":userid", $userid, PDO::PARAM_STR );
  $userIDExists -> execute();

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $outputMsg = msg( 0, 404, "Error: Page not found." );

  } else if( $userIDExists -> rowCount() != 1 ) {

      $outputMsg = msg( 0, 422, "Error: UserID does not exist." );

  } else if( !isset( $userid ) || !isset( $subject ) || !isset( $description ) || !isset( $tags )
           || empty( $userid ) || empty( $subject ) ||  empty( $description ) ||  empty( $tags ) ) {

      $fields = [ "fields" => [ "subject", "description", "tags" ] ];
      $outputMsg = msg( 0, 422, "Error: Please fill out entire form.", $fields );

  } else {

      try {


          $tagsArr = preg_split("/\,/", $tags);

          $blogCount = $conn -> query( "SELECT * FROM `blogs`
                                          WHERE `userid` = '$userid'
                                          AND DATE(`pdate`) = CURDATE()" );
          $blogCount -> execute();

          if($blogCount -> rowCount() >= 2) {

            $outputMsg = msg( 0, 422, "Error: You have exceeded posting limits, please try again in 24 hours." );
            echo json_encode( $outputMsg );
            exit;
          }

          $insertBlog = $conn -> prepare( "INSERT INTO `blogs`(`userid`,`subject`, `description`)
                                                        VALUES(:userid, :subject, :description)" );

          $insertBlog -> bindValue( ":userid",  $userid,  PDO::PARAM_STR );
          $insertBlog -> bindValue( ":subject", $subject, PDO::PARAM_STR );
          $insertBlog -> bindValue( ":description",  $description,  PDO::PARAM_STR );
          $insertBlog -> execute();

          $blogID = $conn -> query( "SELECT `blogid` FROM `blogs`
                                     WHERE `subject`     = '$subject'
                                       AND `description` = '$description'
                                       AND `userid`      = '$userid'" );
          $blogID -> execute();
          $blogID = $blogID -> fetch( PDO::FETCH_ASSOC );
          $arrBlogID = $blogID;

          $blogID = implode("", $blogID);

          for( $i = 0; $i < count($tagsArr); $i++ ){

            $tagsArr[$i] = trim($tagsArr[$i], " ");
            $tempHolder = $tagsArr[$i];

            $insertTag = $conn  -> prepare( "INSERT INTO `blogstags`(`tag`,`blogid`) VALUES(:tempHolder, :blogID)" );
            $insertTag  -> bindValue( ":tempHolder", $tempHolder, PDO::PARAM_STR );
            $insertTag  -> bindValue( ":blogID", $blogID, PDO::PARAM_STR );
            $insertTag  -> execute();
          }

          $outputMsg = msg( 1, 201, "Blog successfully inserted.", $arrBlogID );

      } catch( Exception $e ) {

          $outputMsg = msg( 0, 500, $e -> getMessage() );
      }
  }

  echo json_encode( $outputMsg );

?>
