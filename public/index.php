

<?php
session_start();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <!-- Bootstrap CSS -->
    <base href="/RasilForce/public/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<?php include "header.php" ?>

<body>
    <div id="app" class="container-fluid">
        <div class="content-area">
            <section id="banner" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="banner-carousel">
                </div>
                <a class="carousel-control-prev" href="#banner" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#banner" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </section>
            <section id="about" class="py-5 bg-light text-center">
                <div class="container">
                    <h2 class="text-primary">About Us</h2>
                    <p class="lead text-secondary">We are committed to excellence in every aspect of our business.</p>
                    <p class="text-muted">At Resilforce, we specialize in providing top-notch services to rebuild and restore communities affected by disasters. We take pride in our work, ensuring that every project is completed to the highest standards.</p>
                    <p class="text-muted">Our mission is to support the victims of natural disasters like Hurricane Irma by offering timely, professional, and reliable services to help restore their homes and businesses to their original state.</p>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="py-5 text-white bg-secondary">
                <div class="container">
                    <h2 class="text-center">What Our Clients Say</h2>
                    <div id="testimonial-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="testimonial-display">
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonial-carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonial-carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </section>
            
            <section id="contact-form" class="py-5">
                <div class="container">
                    <h2 class="text-center text-primary">Contact Us</h2>
                    <form id="contactForm" class="mt-4 shadow p-4 rounded bg-light">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="subscribe" name="subscribe">
                            <label class="form-check-label" for="subscribe">Subscribe to notifications</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <?php include "footer.php" ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/admin.js"></script>

</body>
</html>
