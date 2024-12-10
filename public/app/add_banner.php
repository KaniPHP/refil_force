<?php
require_once '../../database.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        
        if (!isset($_FILES['banner_image']) || $_FILES['banner_image']['error'] != UPLOAD_ERR_OK) {
            throw new Exception("Please upload a valid banner image.");
        }

      
        $status = $_POST['status'] ?? 'inactive';  
        $description = $_POST['description'] ?? ''; 

   
        $banner_image = $_FILES['banner_image'];
        $upload_dir = '../../uploads/';  
        $filename = uniqid('banner_') . '.' . pathinfo($banner_image['name'], PATHINFO_EXTENSION);
        $banner_path = $upload_dir . $filename;

    
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);  
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($banner_image['type'], $allowedMimeTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        $maxFileSize = 5 * 1024 * 1024; 
        if ($banner_image['size'] > $maxFileSize) {
            throw new Exception("File size exceeds the maximum limit of 5MB.");
        }

        if (!move_uploaded_file($banner_image['tmp_name'], $banner_path)) {
            throw new Exception("Error uploading banner.");
        }

        $pdo = connectDB();
        $stmt = $pdo->prepare("INSERT INTO banners (image_path, status, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([str_replace('../../', '', $banner_path), $status, $description]);

        echo json_encode(["success" => true, "message" => "Banner uploaded successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
