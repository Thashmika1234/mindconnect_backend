<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();

// Sanitize and validate inputs
$email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
$usertype = htmlspecialchars(trim($_POST["usertype"] ?? ""));
$password = $_POST["password"] ?? "";

// Basic server-side validation
if (empty($email) || empty($usertype) || empty($password)) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email address"]);
    exit;
}

try {
    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (email, usertype, password)
            VALUES (:email, :usertype, :password)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':usertype', $usertype);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User registered successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to register user"]);
    }

} catch (Exception $err) {
    echo json_encode(["success" => false, "message" => "Error: " . $err->getMessage()]);
}
?>
