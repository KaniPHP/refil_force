<?php
require_once '../../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $content = $_POST['content'];
        $title = $_POST['title'];

        $imagePath = null;

       
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            try {
                $imagePath = uploadImage($_FILES['image']);  
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }

        try {
            $pdo = connectDB();

           
            $query = "UPDATE posts SET title = :title, content = :content";
            if ($imagePath) {
                $query .= ", image_path = :image_path"; // Update image path only if a new file was uploaded
            }
            $query .= " WHERE id = :id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':id', $id);

            if ($imagePath) {
                $stmt->bindParam(':image_path', $imagePath);
            }

            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Post updated successfully.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update post: ' . $e->getMessage()]);
        }
    }
}

/**
 * Function to handle image upload
 */
function uploadImage($file) {
    $uploadDir = '../../uploads/';
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error. Please try again.');
    }
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedMimeTypes)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
    }
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueFileName = 'post_' . uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFileName;
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to move uploaded file.');
    }
    return str_replace('../../', '', $uploadPath); // Return path relative to the public directory
}
?>
