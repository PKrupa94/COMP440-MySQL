<?php

  header( "Access-Control-Allow-Origin: *" );
  header( "Access-Control-Allow-Headers: access" );
  header( "Access-Control-Allow-Methods: POST" );
  header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
  header( "Content-Type: application/json; charset=UTF-8" );

  require __DIR__."/../classes/Database.php";
  require __DIR__."/../classes/JwtHandler.php";

  $db = new Database();
  $conn = $db -> dbConnection();

  $inputData = json_decode( file_get_contents( "php://input" ) );
  $outputMsg = [];

  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $outputMsg = msg( 0, 404, "Error: Page Not Found." );
  } else if( !isset( $inputData -> email ) || !isset( $inputData -> password )
          || empty( trim( $inputData -> email ) ) || empty( trim( $inputData -> password ) ) ) {

      $fields = [ "fields" => [ "email", "password" ] ];
      $outputMsg = msg( 0, 422, "Error: Please enter a valid email and password.", $fields );
  } else {

      $email = trim( $inputData -> email );
      $password = trim( $inputData -> password );

      if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

          $outputMsg = msg( 0, 422, "Error: Invalid Email Address." );
      } else {

          try{

              $emailSelectStmt = "SELECT * FROM `users` WHERE `email` = :email";
              $emailQuery = $conn -> prepare( $emailSelectStmt );
              $emailQuery -> bindValue( ":email", $email, PDO::PARAM_STR );
              $emailQuery -> execute();

              if( $emailQuery -> rowCount() ) {

                  $row = $emailQuery -> fetch( PDO::FETCH_ASSOC );
                  $verifyPass = password_verify( $password, $row[ "password" ] );

                  if( $verifyPass ) {

                      $jwt = new JwtHandler();

                      $token = $jwt -> _jwt_encode_data (
                          "http://localhost/php_auth_api/",
                          array( "user_id" => $row[ "id" ] )
                      );

                      $outputMsg = [
                          "Is Success" => 1,
                          "Message" => "You have successfully logged in.",
                          "Token" => $token
                      ];

                    } else {

                      $outputMsg = msg( 0, 422, "Error: Invalid Password." );
                    }

              } else {

                  $outputMsg = msg( 0, 422, "Error: Invalid Email Address." );
              }

          } catch( PDOException $e ) {

              $outputMsg = msg( 0, 500, $e -> getMessage() );
          }
        }
      }

  echo json_encode( $outputMsg );

?>
