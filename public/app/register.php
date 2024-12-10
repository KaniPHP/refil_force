<?php
require '../database.php';  

$pdo = connectDB();  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $message = $_POST['message'];
    $subscribe = isset($_POST['subscribe']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(["success" => false, "message" => "This email is already registered. Please use a different email."]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password,subscribe) VALUES (?, ?, ? , ?)");
            $stmt->execute([$name, $email, $password,$subscribe]);

            $user_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO contact_forms (user_id, message) VALUES (?, ?)");
            $stmt->execute([$user_id, $message]);

            echo json_encode(["success" => true, "message" => "Contact form successfully submitted."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
}
?>
