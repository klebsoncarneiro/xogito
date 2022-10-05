<?php

use \Mailjet\Resources;

function send_email($to, $subject, $query, $email, $name){
    require 'vendor/autoload.php';      
    $mj = new \Mailjet\Client(MAILJET_APIKEY, MAILJET_SECRETKEY, true, ['version' => 'v3.1']);
    $body = [
        'Messages' => [
        [
            'From' => [
            'Email' => $email,
            'Name' => $name
            ],
            'To' => [
            [
                'Email' => $to,
                'Name' => $to
            ]
            ],
            'Subject' => $subject,
            'TextPart' => $query,
            'HTMLPart' => $query,
            'CustomID' => "XogitoTest"
        ]
        ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    
    if ($response->success()){
        $_SESSION['email_success'] = true;
    } else {
        $_SESSION['email_success'] = false;
    }

}

session_start();

$email = pg_escape_string($_POST['email']);
$password = pg_escape_string($_POST['password']);

$postdata = http_build_query(
    array(
        'email' => $email,
        'password' => $password
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'content' => $postdata,
        'header' => "Content-type: application/x-www-form-urlencoded\r\n"
        . "Content-Length: " . strlen($postdata) . "\r\n",
    )
);

$context = stream_context_create($opts);

$json = file_get_contents('http://localhost/xogito/api/login/', false, $context);

$json_return = json_decode($json);

if ($json_return->status == 'error'){
    $_SESSION['error'] = $json_return->data;

    header("Location: ../");
    die;
} else {

    $json_data = json_decode($json)->data;

    $_SESSION['id'] = $json_data->id;
    $_SESSION['is_admin'] = $json_data->is_admin;
    $_SESSION['name'] = $json_data->name;
    $_SESSION['email'] = $email;

    $json = file_get_contents('http://localhost/xogito/api/login/'.$_SESSION['id']);

    $json_return = json_decode($json);

    if ($json_return->status == 'error'){
        $_SESSION['error'] = $json_return->data;
    
        header("Location: ../");
        die;
    } else {

        $json_data = json_decode($json)->data;

        $_SESSION['mfa'] = $json_data;

        send_email($email, "Xogito - MFA", "Use the following code: ".$_SESSION['mfa']." to login.", 'klebsoncarneiro@gmail.com', 'Xogito Texst');

        header("Location: mfa/");

    }
}