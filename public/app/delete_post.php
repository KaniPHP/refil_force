<?php
require_once '../../database.php';  


$pdo = connectDB(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['id'];

    try {
        
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$postId]);

        
        echo json_encode(["success" => true, "message" => "Post deleted successfully."]);
    } catch (Exception $e) {
      
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>
