<?php
session_start();  // Start the session to access session variables
require_once '../database.php';  // Include the database connection file

// Set up pagination
$limit = 6;  // Number of posts per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;  // Current page
$offset = ($page - 1) * $limit;

// Connect to the database
$pdo = connectDB();

try {
    // Fetch total number of posts
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM posts");
    $totalPosts = $totalStmt->fetchColumn();
    
    // Fetch posts for the current page
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total number of pages
    $totalPages = ceil($totalPosts / $limit);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>


    <section id="blog" class="py-2">
        <div class="container">
            <h2 class="text-center text-primary">Our Blog</h2>
            <div class="row">
                <?php
                if ($posts) {
                    foreach ($posts as $post) {
                        echo '<div class="col-md-4 mb-4">
                                <div class="card shadow-sm">
                                    <img src="' . ($post['image_path'] ? $post['image_path'] : 'https://via.placeholder.com/500') . '" class="card-img-top" alt="' . $post['title'] . '">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $post['title'] . '</h5>
                                        <p class="card-text">' . $post['content'] . '...</p>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo '<p class="text-center">No blog posts available.</p>';
                }
                ?>
            </div>
        </div>
    </section>

 
