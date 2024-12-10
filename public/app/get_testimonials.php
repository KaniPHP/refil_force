<?php
require_once '../../database.php';

header('Content-Type: application/json');

try {
    $pdo = connectDB();
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); 
        $stmt = $pdo->prepare("SELECT id, name, testimonial, status, created_at, updated_at FROM testimonials WHERE id = :id AND deleted_at IS NULL");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner) {
            echo json_encode(["success" => true, "data" => $banner]);
        } else {
            echo json_encode(["success" => false, "message" => "Testimonials not found."]);
        }
    } else {
     
        $recordsPerPage = 10; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $offset = ($page - 1) * $recordsPerPage; 

        $totalRecordsStmt = $pdo->query("SELECT COUNT(*) as total FROM testimonials WHERE deleted_at IS NULL");
        $totalRecords = $totalRecordsStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);

        $stmt = $pdo->prepare("SELECT id, name, testimonial, status, created_at, updated_at FROM testimonials WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $testimonials,
            "current_page" => $page,
            "total_pages" => $totalPages
        ]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
