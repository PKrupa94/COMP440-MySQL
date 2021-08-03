<?php

  header( "Access-Control-Allow-Origin: *" );
  header( "Access-control-Allow-credentials: true" );
  header( "Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding" );
  header( "Access-Control-Allow-Methods: GET, POST" );
  header( "Content-Type: application/json; charset=UTF-8" );

  require __DIR__."/../classes/Database.php";
  require __DIR__."/../classes/JwtHandler.php";

  $db = new Database();
  $conn = $db -> dbConn();

  $inputData = json_decode( file_get_contents( "php://input" ) );

  $username = trim( $inputData -> username );
  $password = trim( $inputData -> password );

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
  } else if( !isset( $username ) || !isset( $password )
           || empty( $username ) || empty( $password ) ) {

      $fields = [ "fields" => [ "username", "password" ] ];
      $outputMsg = msg( 0, 422, "Error: Please enter a valid username and password.", $fields );
  } else {
          try{

              $userselect = $conn -> prepare( "SELECT * FROM `users` WHERE `username` = :username" );
              $userselect -> bindValue( ":username", $username, PDO::PARAM_STR );
              $userselect -> execute();

              if( $userselect -> rowCount() == 1 ) {

                  $row = $userselect -> fetch( PDO::FETCH_ASSOC );

                  if( password_verify( $password, $row[ "password" ] ) ) {

                      $jwt = new JwtHandler();

                      $token = $jwt -> _jwt_encode_data (
                          "http://localhost/php_auth_api/",
                          array( "user_id" => $row[ "userid" ] )
                      );

                      $outputMsg = [
                          "Is Success" => 1,
                          "Message" => "You have successfully logged in.",
                          "Token" => $token,
                          "username" => $username
                      ];

                    } else {

                      $outputMsg = msg( 0, 422, "Error: Invalid Password." );
                    }

              } else {

                  $outputMsg = msg( 0, 422, "Error: Invalid Username." );
              }

          } catch( Exception $e ) {

              $outputMsg = msg( 0, 500, $e -> getMessage() );
          }
      }

  echo json_encode( $outputMsg );
?>
