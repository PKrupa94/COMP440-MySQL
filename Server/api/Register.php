<?php

  header( "Access-Control-Allow-Origin: *" );
  header( "Access-Control-Allow-Headers: access" );
  header( "Access-Control-Allow-Methods: POST" );
  header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
  header( "Content-Type: application/json; charset=UTF-8" );

  require __DIR__."/../classes/Database.php";

  $db = new Database();
  $conn = $db -> dbConnection();

  $inputData = json_decode( file_get_contents( "php://input" ) );
  $outputMsg = [];

  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status" => $status,
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $outputMsg = msg( 0, 404, "Error: Page not found." );
  } else if( !isset( $inputData -> firstname ) || !isset( $inputData -> lastname ) || !isset( $inputData -> username )
          || !isset( $inputData -> email )     || !isset( $inputData -> password )
          || empty( trim( $inputData -> firstname ) ) || empty( trim( $inputData -> lastname ) ) || empty( trim( $inputData -> username ) )
          || empty( trim( $inputData -> email ) )     || empty( trim( $inputData -> password ) ) ) {

      $fields = [ "fields" => [ "firstname", "lastname", "username", "email", "password" ] ];
      $outputMsg = msg( 0, 422, "Error: Please fill out entire form.", $fields );
  } else {

      $firstname = htmlspecialchars( strip_tags( trim( $inputData -> firstname ) ) );
      $lastname = htmlspecialchars( strip_tags( trim( $inputData -> lastname ) ) );
      $username = htmlspecialchars( strip_tags( trim( $inputData -> username ) ) );
      $email = trim( $inputData -> email );
      $password = password_hash( trim( $inputData -> password ), PASSWORD_DEFAULT );

      if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

          $outputMsg = msg( 0, 422, "Error: Invalid email." );
      } else {

          try {

              $usernameSelectStmt = "SELECT `username` FROM `users` WHERE `username`=:username";
              $emailSelectStmt = "SELECT `email` FROM `users` WHERE `email`=:email";
              $usernameStmt = $conn -> prepare( $usernameSelectStmt );
              $emailStmt = $conn -> prepare( $emailSelectStmt );

              $usernameStmt -> bindValue( ":username", $username, PDO::PARAM_STR );
              $emailStmt -> bindValue( ":email", $email, PDO::PARAM_STR );
              $usernameStmt -> execute();
              $emailStmt -> execute();

              if( $usernameStmt -> rowCount() ) {

                  $outputMsg = msg( 0, 422, "Error: Username already exists." );

              } else if( $emailStmt -> rowCount() ) {

                  $outputMsg = msg( 0, 422, "Error: Email already exists." );

              } else {

                  $insertQuery = "INSERT INTO `users`(`firstname`, `lastname`, `username`, `email`, `password`)
                                                VALUES(:firstname, :lastname, :username, :email, :password)";

                  $insertStmt = $conn -> prepare( $insertQuery );

                  $insertStmt -> bindValue( ":firstname", $firstname, PDO::PARAM_STR );
                  $insertStmt -> bindValue( ":lastname",  $lastname,  PDO::PARAM_STR );
                  $insertStmt -> bindValue( ":username",  $username,  PDO::PARAM_STR );
                  $insertStmt -> bindValue( ":email",     $email,     PDO::PARAM_STR );
                  $insertStmt -> bindValue( ":password",  $password,  PDO::PARAM_STR );

                  $insertStmt -> execute();

                  $outputMsg = msg( 1, 201, "Successfully registered." );

              }

          } catch( PDOException $e ) {

              $outputMsg = msg( 0, 500, $e -> getMessage() );
          }
      }
  }

  echo json_encode( $outputMsg );

?>
