<?php

  header( "Access-Control-Allow-Origin: *" );
  header( "Access-Control-Allow-Headers: access" );
  header( "Access-Control-Allow-Methods: POST" );
  header( "Content-Type: application/json; charset=UTF-8" );
  header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );

  function msg( $success, $status, $message, $extra = [] ) {
      return array_merge([
          'success' => $success,
          'status' => $status,
          'message' => $message
      ], $extra);
  }

  require __DIR__.'/Classes/Database.php';

  $db = new Database();
  $conn = $db -> dbConnection();

  $data = json_decode( file_get_contents( "php://input" ) );
  $returnData = [];

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $returnData = msg( 0, 404, 'Error: Page not found!' );
  } else if( !isset( $data -> firstname ) || !isset( $data -> lastname ) || !isset( $data -> username )
          || !isset( $data -> email )     || !isset( $data -> password )
          || empty( trim( $data -> firstname ) ) || empty( trim( $data -> lastname ) ) || empty( trim( $data -> username ) )
          || empty( trim( $data -> email ) )     || empty( trim( $data -> password ) ) ) {

      $fields = [ 'fields' => [ 'firstname', 'lastname', 'username', 'email', 'password' ] ];
      $returnData = msg( 0, 422, 'Please enter all information.', $fields );
  } else {

      $firstname = trim( $data -> firstname );
      $lastname = trim( $data -> lastname );
      $username = trim( $data -> username );
      $email = trim( $data -> email );
      $password = trim( $data -> password );

      if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

          $returnData = msg( 0, 422, 'Error: Invalid email.' );
      } else {

          try {

              $usernameSelectStmt = "SELECT `username` FROM `users` WHERE `username`=:username";
              $usernameStmt = $conn -> prepare( $usernameSelectStmt );
              $emailSelectStmt = "SELECT `email` FROM `users` WHERE `email`=:email";
              $emailStmt = $conn -> prepare( $emailSelectStmt );

              $usernameStmt -> bindValue( ':username', $username, PDO::PARAM_STR );
              $usernameStmt -> execute();
              $emailStmt -> bindValue( ':email', $email, PDO::PARAM_STR );
              $emailStmt -> execute();

              if( $usernameStmt -> rowCount() ) {

                  $returnData = msg( 0, 422, 'Error: Username already exists.' );

              } else if( $emailStmt -> rowCount() ) {

                  $returnData = msg( 0, 422, 'Error: Email already exists.' );

              } else {

                  $insertQuery = "INSERT INTO `users`(`firstname`, `lastname`, `username`, `email`, `password`)
                                                VALUES(:firstname, :lastname, :username, :email, :password)";

                  $insertStmt = $conn -> prepare( $insertQuery );

                  $insertStmt -> bindValue( ':firstname', htmlspecialchars( strip_tags( $firstname ) ), PDO::PARAM_STR );
                  $insertStmt -> bindValue( ':lastname', htmlspecialchars( strip_tags( $lastname ) ), PDO::PARAM_STR );
                  $insertStmt -> bindValue( ':username', htmlspecialchars( strip_tags( $username ) ), PDO::PARAM_STR );
                  $insertStmt -> bindValue( ':email', $email, PDO::PARAM_STR );
                  $insertStmt -> bindValue( ':password', password_hash( $password, PASSWORD_DEFAULT ), PDO::PARAM_STR );

                  $insertStmt -> execute();

                  $returnData = msg( 1, 201, 'You have successfully registered.' );

              }

          } catch( PDOException $e ) {

              $returnData = msg( 0, 500, $e -> getMessage() );
          }
      }
  }

  echo json_encode( $returnData );

?>
