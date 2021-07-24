<?php

  require __DIR__.'/../jwt/JWT.php';
  require __DIR__.'/../jwt/ExpiredException.php';
  require __DIR__.'/../jwt/SignatureInvalidException.php';
  require __DIR__.'/../jwt/BeforeValidException.php';

  use \Firebase\JWT\JWT;

  class JwtHandler {

      protected $jwtPass;
      protected $token;
      protected $issuedAt;
      protected $expiredAt;
      protected $jwt;

      public function construct() {

          date_default_timezone_set( 'America/Los_Angeles' );

          $this -> issuedAt = time();
          $this -> expiredAt = $this -> issuedAt + 3600;
          $this -> jwtPass = "mysql12345";
      }

      public function _jwt_encode_data( $issue, $data ) {

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

      protected function errMsg( $msg ) {
          return [
              "auth" => 0,
              "message" => $msg
          ];
      }

      public function _jwt_decode_data( $jwtToken ) {

          try {

              $decode = JWT::decode( $jwtToken, $this -> jwtPass, array( 'HS256' ) );
              return [
                  "auth" => 1,
                  "data" => $decode -> data
              ];
          } catch( \Firebase\JWT\ExpiredException $e ) {

              return $this -> errMsg( $e -> getMessage() );
          } catch( \Firebase\JWT\SignatureInvalidException $e ) {

              return $this -> errMsg( $e -> getMessage() );
          } catch( \Firebase\JWT\BeforeValidException $e ) {

              return $this -> errMsg( $e -> getMessage() );
          } catch( \DomainException $e ) {

              return $this -> errMsg( $e -> getMessage() );
          } catch( \InvalidArgumentException $e ) {

              return $this -> errMsg( $e -> getMessage() );
          } catch( \UnexpectedValueException $e ) {

              return $this -> errMsg( $e -> getMessage() );
          }
      }
  }

?>
