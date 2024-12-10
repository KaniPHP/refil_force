<?php
require_once '../../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        $imagePath = null; // Initialize the image path as null

        // Handle file upload if a file is provided
        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
            try {
                $imagePath = uploadImage($_FILES['banner_image']);  
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }

        try {
            $pdo = connectDB();

            // Prepare the update query
            $query = "UPDATE banners SET description = :description, status = :status";
            if ($imagePath) {
                $query .= ", image_path = :image_path"; // Update image path only if a new file was uploaded
            }
            $query .= " WHERE id = :id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);

            if ($imagePath) {
                $stmt->bindParam(':image_path', $imagePath);
            }

            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Banner updated successfully.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update banner: ' . $e->getMessage()]);
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
    $uniqueFileName = 'banner_' . uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFileName;
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to move uploaded file.');
    }
    return str_replace('../../', '', $uploadPath); // Return path relative to the public directory
}
?>
