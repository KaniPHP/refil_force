<?php 
include '../database.php'; 
session_start();  

$pdo = connectDB(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("
            SELECT u.*, cf.status AS form_status 
            FROM users u
            LEFT JOIN contact_forms cf ON u.id = cf.user_id
            WHERE u.email = :email
            ORDER BY cf.created_at DESC LIMIT 1
        ");

        
        $stmt->bindParam(':email', $email);
        $stmt->execute();  

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];  
                $_SESSION['role'] = $user['role'];  
                $_SESSION['subscribe'] = $user['subscribe']; 
                $_SESSION['status'] = $user['form_status']; 

                echo json_encode([
                    "success" => true,
                    "message" => "Login successful!",
                    "role" => $_SESSION['role']
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid password."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "User not found."
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ]);
    }
}
?>
