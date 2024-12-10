<?php
require_once '../../database.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['action']; 

    try {
        $pdo = connectDB();
        $stmt = $pdo->prepare("UPDATE contact_forms SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => "Form $status successfully!"]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
