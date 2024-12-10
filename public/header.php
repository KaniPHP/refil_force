<header class="bg-primary text-white py-4">
    <div class="container">
        <div class="logo">
            <h1>ResilForce</h1>
        </div>

        <nav>
            <ul>
            <li><a href="#" class="menu-link text-white" data-page="index">Home</a></li>
            <li><a href="#" class="menu-link text-white" data-page="about">About Us</a></li>
                <li><a href="#" class="menu-link text-white" data-page="contact">Contact</a></li>
                <?php
                if (isset($_SESSION['user_id'])) {
                    // Check form status and apply corresponding badge color
                    if ($_SESSION['status'] == 'approved') {
                        echo '<li><a href="#" class="menu-link text-white" data-page="blog">Blog</a></li>';
                        echo '<li class="text-white">Contact form Status <span class="badge bg-success">Approved</span></li>';
                    } else {
                        echo '<li class="text-white">Contact form Status <span class="badge bg-warning">Pending</span></li>';
                    }

                    echo '<li class="text-white">Welcome, ' . $_SESSION['user_name'] . '</li>
                          <li><a href="logout.php" class="menu-link text-white">Log out</a></li>';
                } else {
                    echo '<li><a href="#" class="menu-link text-white" data-page="login">Login</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>
