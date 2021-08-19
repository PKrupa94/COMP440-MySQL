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

  $pdate = htmlspecialchars( strip_tags( trim( $inputData -> pdate ) ) );

  $outputMsg = [];
  $userlist = [];


  function msg( $isSuccess, $status, $msg, $optional = [] ) {
    return array_merge( [
      "Is Success" => $isSuccess,
      "Status Code" => $status,
      "Message" => $msg
    ], $optional );
  }

  if( $_SERVER[ "REQUEST_METHOD" ] != "POST" ) {

      $outputMsg = msg( 0, 404, "Error: Page Not Found." );
  } else {
      try {

          $blogselect = $conn -> prepare( "SELECT *
                                           FROM(
                                                  SELECT `blogs`.userid, COUNT(*) AS blogcount,
                                                         RANK() OVER (ORDER BY COUNT(*) DESC) AS ranknum
                                                  FROM `blogs`
                                                  WHERE `blogs`.pdate LIKE CONCAT('%', :pdate, '%')
                                                  GROUP BY `blogs`.userid
                                               ) i
                                           WHERE ranknum = 1" );

          $blogselect -> bindValue( ":pdate" , $pdate , PDO::PARAM_STR );
          $blogselect -> execute();

          if( $blogselect -> rowCount() >= 1 ) {

            while( $row = $blogselect -> fetch( PDO::FETCH_ASSOC ) ) {

                $userlist[] = $row;
            }

            $outputMsg = msg( 1, 201, "Successfully retrieved users who posted most blogs on: $pdate." );

            for( $i = 0; $i < sizeof($userlist); $i++ ) {

              $userid = $userlist[$i]['userid'];

              $userselect = $conn -> prepare( "SELECT `users`.userid, `users`.firstname,
                                                      `users`.lastname, `users`.username, `users`.email
                                               FROM `users`
                                               WHERE `users`.userid = :userid" );
              $userselect -> bindValue( ":userid", $userid, PDO::PARAM_STR );
              $userselect -> execute();

              $rowValues = $userselect -> fetch( PDO::FETCH_ASSOC );

              $userlist[$i] += $rowValues;
            }

            $outputMsg['userlist'] = $userlist;

          } else {

              $outputMsg = msg( 0, 422, "Error: No users to display." );
          }

      } catch( Exception $e ) {

          $outputMsg = msg( 0, 500, $e -> getMessage() );
      }
  }

  echo json_encode( $outputMsg );

?>
