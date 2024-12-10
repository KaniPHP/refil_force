document.addEventListener('DOMContentLoaded', () => {
    const app = document.getElementById('app');
    const loadedScripts = new Set(); // Track loaded scripts to prevent reloading
    let currentPage = ''; // Track the current page for SPA navigation

    // Function to dynamically load page content
    function loadContent(url) {
        fetch(url)
            .then((response) => response.text())
            .then((html) => {
                app.querySelector(".content-area").innerHTML = html;
                initializePageScripts(); // Reinitialize any page-specific logic
            })
            .catch((error) => console.error("Error loading content:", error));
    }

    // Function to load scripts dynamically and prevent duplicates
    function loadScript(src) {
        if (!loadedScripts.has(src)) {
            const script = document.createElement('script');
            script.src = src;
            script.type = 'text/javascript';
            script.onload = () => loadedScripts.add(src);
            document.body.appendChild(script);
        }
    }

    // Page-specific initialization logic
    function initializePageScripts() {
      
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
    
                fetch('app/login.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
    
                        if (data.success) {
                            alert(data.message);
    
                            if (data.role === 'admin') {
                                window.location.href = 'admin';
                            } else {
                                window.location.href = 'index.php';
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred, please try again.');
                    });
            });
        }
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('app/register.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        alert(data.message);
                        if (data.success) this.reset();
                    })
                    .catch((error) => console.error('Error:', error));
            });
        }

        // Banner Carousel
        const bannerCarousel = document.getElementById('banner-carousel');
        if (bannerCarousel) {
            fetch('admin/app/get_banners.php')
                .then((response) => response.json())
                .then((result) => {
                    const activeBanners = result.data.filter((banner) => banner.status === 'active');
                    bannerCarousel.innerHTML = activeBanners.length
                        ? activeBanners
                              .map(
                                  (banner, index) => `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <img src="${banner.image_path}" class="d-block w-100" alt="Banner">
                        </div>`
                              )
                              .join('')
                        : `<div class="carousel-item active">
                            <img src="https://via.placeholder.com/1500x500" class="d-block w-100" alt="Default Banner">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>No Active Banners</h5>
                            </div>
                        </div>`;
                })
                .catch((error) => console.error('Error fetching banners:', error));
        }
    }

    // Call initializePageScripts for the initial page
    initializePageScripts();

    // SPA navigation
    document.body.addEventListener('click', (event) => {
        if (event.target.classList.contains('menu-link')) {
            event.preventDefault();
            const page = event.target.getAttribute('data-page');
            if (page && page !== currentPage) {
                currentPage = page;
                loadContent(`pages/${page}.php`);
                window.history.pushState({}, '', page);
            }
        }
    });

    // Handle back/forward navigation
    window.addEventListener('popstate', () => {
        const page = window.location.pathname.split('/').pop() || 'home';
        if (page !== currentPage) {
            currentPage = page;
            loadContent(`pages/${page}.php`);
        }
    });
});
