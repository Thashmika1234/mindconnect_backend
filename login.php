<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();


$username = $_POST["username"];
$email = $_POST["email"];
$usertype = $_POST["usertype"];
$password = $_POST["password"]; 
$confirmpassword = $_POST["confirmpassword"];

try{
    $sql = "INSERT INTO users(username,email,usertype,password,confirmpassword)VALUES(:username,:email,:usertype,:password,:confirmpassword)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':usertype',$usertype);
    $stmt->bindParam(':password',$password);
    $stmt->bindParam(':confirmpassword',$confirmpassword);

    if($stmt->execute()){
        $response = ['status' => 1,'message' => 'Record created succesfully'];
    }
    else{
        $response = ['status' => 0,'message' => 'Failed to create record'];
    }

    var_dump("Added User"); 
}catch(Exception $err){
    var_dump($err);
}


?>