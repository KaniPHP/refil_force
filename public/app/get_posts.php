<?php
require_once '../../database.php';

header('Content-Type: application/json');

try {
    $pdo = connectDB();
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); 
        $stmt = $pdo->prepare("SELECT id, image_path, title, content, created_at, updated_at FROM posts WHERE id = :id AND deleted_at IS NULL");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner) {
            echo json_encode(["success" => true, "data" => $banner]);
        } else {
            echo json_encode(["success" => false, "message" => "Banner not found."]);
        }
    } else {
     
        $recordsPerPage = 10; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
        $offset = ($page - 1) * $recordsPerPage; 

        $totalRecordsStmt = $pdo->query("SELECT COUNT(*) as total FROM posts WHERE deleted_at IS NULL");
        $totalRecords = $totalRecordsStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage); // Calculate the total number of pages

        $stmt = $pdo->prepare("SELECT id, image_path, title, content, created_at, updated_at FROM posts WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $posts,
            "current_page" => $page,
            "total_pages" => $totalPages
        ]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
