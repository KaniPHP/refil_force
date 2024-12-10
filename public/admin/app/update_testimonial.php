<?php
require_once '../../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $name = $_POST['name'];
        $testimonial = $_POST['testimonial'];
        $status = $_POST['status'];

        $imagePath = null;

       
       

        try {
            $pdo = connectDB();

           
            $query = "UPDATE testimonials SET name = :name, testimonial = :testimonial , status = :status";
       
            $query .= " WHERE id = :id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':testimonial', $testimonial);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Testimonial updated successfully.']);
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
