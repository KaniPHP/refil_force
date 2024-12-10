<?php
require_once '../../database.php';  

$pdo = connectDB(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
    $postId = $_POST['id'] ?? null;

    if (!$postId) {
        echo json_encode(["success" => false, "message" => "Banner ID is required."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE banners SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$postId]);

        echo json_encode(["success" => true, "message" => "Banner deleted successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>
