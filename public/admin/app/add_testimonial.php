<?php
require_once '../../database.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $testimonial = $_POST['testimonial'];

    // Insert testimonial into the database
    $pdo = connectDB();
    $stmt = $pdo->prepare("INSERT INTO testimonials (name, testimonial) VALUES (?, ?)");
    $stmt->execute([$name, $testimonial]);

    echo json_encode(["success" => true, "message" => "Testimonial added successfully."]);
}

