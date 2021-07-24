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

  require __DIR__.'/classes/Database.php';
  require __DIR__.'/classes/JwtHandler.php';

  $db = new Database();
  $conn = $db -> dbConnection();

  $data = json_decode( file_get_contents( "php://input" ) );
  $returnData = [];

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $returnData = msg( 0, 404, 'Error: Page Not Found.' );
  } else if( !isset( $data -> email ) || !isset( $data -> password )
          || empty( trim( $data -> email ) ) || empty( trim( $data -> password ) ) ) {

      $fields = [ 'fields' => [ 'email', 'password' ] ];
      $returnData = msg( 0, 422, 'Error: Please enter a valid email and password.', $fields );
  } else {

      $email = trim( $data -> email );
      $password = trim( $data -> password );

      if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

          $returnData = msg( 0, 422, 'Error: Invalid Email Address.' );
      } else {

          try{

              $emailSelectStmt = "SELECT * FROM `users` WHERE `email` = :email";
              $query = $conn -> prepare( $emailSelectStmt );
              $query -> bindValue( ':email', $email, PDO::PARAM_STR );
              $query -> execute();

              if( $query -> rowCount() ) {

                  $row = $query -> fetch( PDO::FETCH_ASSOC );
                  $verifyPass = password_verify( $password, $row[ 'password' ] );

                  if( $verifyPass ) {

                      $jwt = new JwtHandler();

                      $token = $jwt -> _jwt_encode_data (
                          'http://localhost/php_auth_api/',
                          array( "user_id" => $row[ 'id' ] )
                      );

                      $returnData = [
                          'success' => 1,
                          'message' => 'You have successfully logged in.',
                          'token' => $token
                      ];

                    } else {

                      $returnData = msg( 0, 422, 'Invalid Password.' );
                    }

              } else {

                  $returnData = msg( 0, 422, 'Invalid Email Address.' );
              }

          } catch( PDOException $e ) {

              $returnData = msg( 0, 500, $e -> getMessage() );
          }
        }
      }

  echo json_encode( $returnData );

?>
