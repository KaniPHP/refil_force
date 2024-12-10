

<?php
session_start();  
?>

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

