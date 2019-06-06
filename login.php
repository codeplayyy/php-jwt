<?php
    include_once 'config/headers.php';
    include_once 'config/database.php';
    include_once 'objects/user.php';
    include_once 'config/core.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    $user->email = $data->email;
    $email_exists = $user->emailExists();

    include_once 'libs/php-jwt-master/src/BeforeValidException.php';
    include_once 'libs/php-jwt-master/src/ExpiredException.php';
    include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
    include_once 'libs/php-jwt-master/src/JWT.php';
    
    use \Firebase\JWT\JWT;

    if($email_exists && password_verify($data->password, $user->password)){
        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "data" => array(
                "id"    => $user->id,
                "name"  => $user->name,
                "email" => $user->email
            )
        );
        http_response_code(200);
    
        $jwt = JWT::encode($token, $key);
        echo json_encode(
                array(
                    "success"   => true,
                    "message"   => "Login successful",
                    "jwt"       => $jwt
                )
            );
    } else {
        http_response_code(401);
        echo json_encode(array(
                                "success" => false, 
                                "message" => "Login failed"));
    }
?>