<?php
require_once '../../database.php';  // Include your database connection

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;  // Current page
$limit = 5;  // Number of forms per page
$offset = ($page - 1) * $limit;

try {
    $pdo = connectDB();

    $totalStmt = $pdo->query("SELECT COUNT(*) FROM contact_forms");
    $totalForms = $totalStmt->fetchColumn();

    $stmt = $pdo->prepare("
    SELECT 
        cf.id, cf.message, cf.status, cf.created_at, cf.updated_at, cf.deleted_at,
        u.name AS user_name, u.email AS user_email
    FROM contact_forms cf
    JOIN users u ON cf.user_id = u.id
    ORDER BY cf.created_at DESC
    LIMIT :offset, :limit
    ");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send JSON response
    echo json_encode([
        'success' => true,
        'data' => $forms,
        'total' => $totalForms,
        'page' => $page,
        'pages' => ceil($totalForms / $limit)
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
