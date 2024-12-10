<?php
require_once '../../database.php';

header('Content-Type: application/json');

try {
    $pdo = connectDB();
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Fetch specific banner by ID
        $stmt = $pdo->prepare("SELECT id, image_path, description, status, created_at, updated_at FROM banners WHERE id = :id AND deleted_at IS NULL");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner) {
            echo json_encode(["success" => true, "data" => $banner]);
        } else {
            echo json_encode(["success" => false, "message" => "Banner not found."]);
        }
    } else {
        // Pagination logic for fetching banners
        $recordsPerPage = 10; // Number of records per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1 if not provided
        $offset = ($page - 1) * $recordsPerPage; 

        // Fetch the total number of records (to calculate total pages)
        $totalRecordsStmt = $pdo->query("SELECT COUNT(*) as total FROM banners WHERE deleted_at IS NULL");
        $totalRecords = $totalRecordsStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage); // Calculate the total number of pages

        // Fetch the banners for the current page
        $stmt = $pdo->prepare("SELECT id, image_path, description, status, created_at, updated_at FROM banners WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the banners data along with pagination info
        echo json_encode([
            "success" => true,
            "data" => $banners,
            "current_page" => $page,
            "total_pages" => $totalPages
        ]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
