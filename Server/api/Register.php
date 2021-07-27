<?php

  header( "Access-Control-Allow-Origin: *" );
  header( "access-control-allow-credentials: true" );
  header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
  header('Access-Control-Allow-Methods: GET, POST');
  header( "Content-Type: application/json; charset=UTF-8" );

  require __DIR__."/../classes/Database.php";

  //database connection
  $db = new Database();
  $conn = $db -> dbConn();

  $inputData = json_decode( file_get_contents( "php://input" ) );

  // turn special chars into html-friendly characters
  // and also strip html/php tags from the non-empty input data
  $firstname = htmlspecialchars( strip_tags( trim( $inputData -> firstname ) ) );
  $lastname = htmlspecialchars( strip_tags( trim( $inputData -> lastname ) ) );
  $username = htmlspecialchars( strip_tags( trim( $inputData -> username ) ) );

  $email = trim( $inputData -> email );
  $password = trim( $inputData -> password );

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
  //check for missing form fields
  } else if( !isset( $firstname ) || !isset( $lastname ) || !isset( $username ) || !isset( $email ) || !isset( $password )
          || empty( $firstname ) || empty( $lastname ) || empty( $username )  || empty( $email ) || empty( $password ) ) {
      $fields = [ "fields" => [ "firstname", "lastname", "username", "email", "password" ] ];
      $outputMsg = msg( 0, 422, "Error: Please fill out entire form.", $fields );
  } else {
      // make sure password is the same as the hashed password in the table
      $password = password_hash( $password, PASSWORD_DEFAULT );
      //email validation
      if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
          $outputMsg = msg( 0, 422, "Error: Invalid email." );
      } else {
          try {
              $usernameCount = $conn -> prepare( "SELECT `username` FROM `users` WHERE `username`=:username" );
              $emailCount = $conn -> prepare( "SELECT `email` FROM `users` WHERE `email`=:email" );

              $usernameCount -> bindValue( ":username", $username, PDO::PARAM_STR );
              $emailCount -> bindValue( ":email", $email, PDO::PARAM_STR );
              $usernameCount -> execute();
              $emailCount -> execute();

              //if use alredy register return error
              if( $usernameCount -> rowCount() != 0 ) {
                  $outputMsg = msg( 0, 422, "Error: Username already exists." );

              } else if( $emailCount -> rowCount() != 0 ) {
                  $outputMsg = msg( 0, 422, "Error: Email already exists." );

              } else {
                  $insertUser = $conn -> prepare( "INSERT INTO `users`(`firstname`, `lastname`, `username`, `email`, `password`)
                                                   VALUES(:firstname, :lastname, :username, :email, :password)" );
                  $insertUser -> bindValue( ":firstname", $firstname, PDO::PARAM_STR );
                  $insertUser -> bindValue( ":lastname",  $lastname,  PDO::PARAM_STR );
                  $insertUser -> bindValue( ":username",  $username,  PDO::PARAM_STR );
                  $insertUser -> bindValue( ":email",     $email,     PDO::PARAM_STR );
                  $insertUser -> bindValue( ":password",  $password,  PDO::PARAM_STR );
                  $insertUser -> execute();
                  $outputMsg = msg( 1, 201, "User successfully registered." );

              }
          } catch( Exception $e ) {
              $outputMsg = msg( 0, 500, $e -> getMessage() );
          }
      }
  }

  echo json_encode( $outputMsg );

?>
