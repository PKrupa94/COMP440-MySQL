<?php

  require __DIR__."/../jwt/JWT.php";

  use \Firebase\JWT\JWT;

  class JwtHandler {

      protected $jwtPass;
      protected $token;
      protected $issuedAt;
      protected $expiredAt;
      protected $jwt;

      function construct() {

          date_default_timezone_set( "America/Los_Angeles" );

          $this -> issuedAt = time();
          $this -> expiredAt = $this -> issuedAt + 1800;
          $this -> jwtPass = "mysql12345";
      }

      function _jwt_encode_data( $issue, $data ) {

          $this -> token = array(
              "iss" => $issue,
              "aud" => $issue,
              "iat" => $this -> issuedAt,
              "exp" => $this -> expiredAt,
              "data"=> $data
          );

          $this -> jwt = JWT::encode( $this -> token, $this -> jwtPass );

          return $this -> jwt;
      }

      function errMsg( $msg ) {

          return [ "Authorization" => 0, "Message" => $msg ];
      }

      function _jwt_decode_data( $jwtToken ) {

          try {
              $decode = JWT::decode( $jwtToken, $this -> jwtPass, array( "HS256" ) );
              return [ "Authorization" => 1, "Data" => $decode -> data ];

          } catch( Exception $e ) {

              return $this -> errMsg( $e -> getMessage() );
          }
      }
  }

?>
