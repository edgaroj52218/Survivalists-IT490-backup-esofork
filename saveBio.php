<?php
require __DIR__.'/vendor/autoload.php';

//header('Content-Type: application/json');

if (!isset($_COOKIE['SessionKey'])) {
    header("Location: login.php");
    exit();
}

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$collection = $db->reg_users;

//  i added try because if were missing info, this prevent everthing from breaking. And the catch helps me to find the error
try {
    $sessionKey = $_COOKIE['SessionKey'];

    $dob = $_POST['dob'] ?? null;
    $age = null;

    if ($dob) {
        $dobDate = new DateTime($dob);
        $today = new DateTime();
         $age = $today->diff($dobDate)->y;
    }

    $bioData = ["name" => $_POST['name'],"email" => $_POST['email'],"dob" => $dob,"age" => $age,"facebook" => $_POST['facebook'],"instagram" => $_POST['instagram'],];

    $collection->updateOne(
        ['keySession' => $sessionKey],['$set' => ['bio' => $bioData]]);


    header("Location: userProfile.php");
    exit();
  //adding catch to see why isnt working
} catch (Exception $errorMessage) {
    echo json_encode(["status" => "error","message" => $errorMessage->getMessage()]);
}
