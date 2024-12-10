<?php
require_once '../../database.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); 
    $status = $_POST['status']; 

    try {
        $pdo = connectDB();
        
        $stmt = $pdo->prepare("SELECT 
            cf.id, cf.message, cf.status, cf.created_at, cf.updated_at, cf.deleted_at,
            u.name AS user_name, u.email AS user_email
        FROM contact_forms cf
        JOIN users u ON cf.user_id = u.id 
        WHERE cf.id = :id");  
        
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Form not found.']);
            exit;
        }

        $userEmail = $user['user_email']; 

       
        $stmt = $pdo->prepare("UPDATE contact_forms SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

      
        $mail = new PHPMailer(true);
        $mail->isSMTP();  
        $mail->Host       = 'smtp.gmail.com';  
        $mail->SMTPAuth   = true;  
        $mail->Username   = 'padmavathykani678@gmail.com'; 
        $mail->Password   = 'hcvl bopx kbmv sidk';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $mail->Port       = 587;  

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Admin');  
        $mail->addAddress($userEmail);  

        // Content
        $mail->isHTML(false);  
        $mail->Subject = "Your Contact Form Status";
        $mail->Body    = "Hello {$user['user_name']},\n\nYour contact form has been $status.\n\nThank you for reaching out to us.\n\nBest regards,\nThe Team";

        // Send email
        if ($mail->send()) {
            echo json_encode(['success' => true, 'message' => "Form $status successfully! An email has been sent."]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send the email.']);
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
