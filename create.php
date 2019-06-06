<?php
    include_once 'config/headers.php';
    include_once 'config/database.php';
    include_once 'objects/user.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));
    
    $user->name     = $data->name;
    $user->email    = $data->email;
    $user->password = $data->password;

    if(
        !empty($user->name)         &&
        !empty($user->email)        &&
        !empty($user->password)     &&
        $user->create()
    ) {
        http_response_code(200);
        echo json_encode(array(
            "success" => true,
            "message" => "User created"
        ));
    } else {
        http_response_code(400);
        echo json_encode(array(
            "success" => false,
            "message" => "User already exists"
        ));
    }

?>