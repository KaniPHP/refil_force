<?php
require_once '../../database.php';  
require '../../../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = connectDB();  

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM posts");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);  
        echo json_encode($posts);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $title = $_POST['title'] ?? '';  
        $content = $_POST['content'] ?? '';  

        if (empty($title) || empty($content)) {
            throw new Exception("Title and Content are required fields.");
        }

        // Call the function to upload the image
        $image_path = NULL;
        if (isset($_FILES['image'])) {
            $image_path = uploadImage($_FILES['image']);  // Upload the image and get the path
        }

        // Insert post data into the database
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, image_path) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $image_path]);

        // Fetch all subscribed users to send email notifications
        $stmt = $pdo->query("SELECT email FROM users WHERE subscribe = 1");
        $subscribedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mail = new PHPMailer(true);

        foreach ($subscribedUsers as $user) {
            $to = $user['email'];

            if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                error_log("Invalid email: $to. Skipping email notification.");
                continue;  
            }

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'padmavathykani678@gmail.com'; 
                $mail->Password = 'hcvl bopx kbmv sidk';  
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('padmavathykani678@gmail.com', 'Admin'); 
                $mail->addAddress($to); 
                $mail->isHTML(true);
                $mail->Subject = "New Post Added: $title";
                $mail->Body = "Hello,<br><br>A new post has been added:<br><br><strong>Title:</strong> $title<br><strong>Content:</strong> $content<br><br>Visit our site for more details.";
                $mail->AltBody = "Hello,\n\nA new post has been added:\n\nTitle: $title\nContent: $content\n\nVisit our site for more details.";

                $mail->send();
            } catch (Exception $e) {
                error_log("Failed to send email to $to: {$mail->ErrorInfo}");
            }
        }

        echo json_encode(["success" => true, "message" => "Post added successfully and email notifications sent."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

/**
 * Function to handle image upload
 */
function uploadImage($file) {
    $uploadDir = '../../uploads/';
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error. Please try again.');
    }

    // Allowed MIME types for the image
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedMimeTypes)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
    }

    // Generate unique filename and move the file to the uploads directory
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueFileName = 'post_' . uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFileName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to move uploaded file.');
    }

    return str_replace('../../', '', $uploadPath); // Return the path relative to the public directory
}
?>
