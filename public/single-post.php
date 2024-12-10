<?php
session_start();  // Start the session to access session variables
require_once 'database.php';  // Include the database connection

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If the post ID is valid
if ($post_id > 0) {
    try {
        $pdo = connectDB();

        // Fetch the post by ID
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the post exists
        if ($post) {
            // Display the post
        } else {
            echo "Post not found.";
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid post ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post - <?php echo htmlspecialchars($post['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<?php include "header.php"; ?>

<body>
    <section id="single-post" class="py-2">
        <div class="container">
        <h2 class="text-center text-primary">Blog - <?php echo htmlspecialchars($post['title']); ?></h2>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-lg">
                        <img src="<?php echo $post['image_path'] ? $post['image_path'] : 'https://via.placeholder.com/1500x500'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <div class="card-body">
                            <h2 class="card-title text-primary"><?php echo htmlspecialchars($post['title']); ?></h2>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            <p><small class="text-muted">Posted on: <?php echo date('F j, Y', strtotime($post['created_at'])); ?></small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="blog.php" class="btn btn-outline-primary">Back to Blog</a>
            </div>
        </div>
    </section>

    <?php include "footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
