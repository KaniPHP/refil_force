document.addEventListener("DOMContentLoaded", () => {
    const app = document.getElementById("app");  

    const defaultPage = 'dashboard'; 

    const loadedScripts = new Set(); 

    function loadScript(src) {
        if (loadedScripts.has(src)) { // Check if the script has already been loaded
            return;
        }
        
        const script = document.createElement('script');
        script.src = src;
        script.type = 'text/javascript';
        script.onload = () => loadedScripts.add(src); // Mark the script as loaded after it is executed
        document.body.appendChild(script);
    }

    function loadContent(url) {
        fetch(url)
            .then((response) => response.text())
            .then((html) => {
                app.querySelector(".content-area").innerHTML = html;
                initializePageScripts();  // Initialize page-specific scripts after loading content
            })
            .catch((error) => console.error("Error loading content:", error));
    }

    function loadPageScripts(page) {
        switch (page) {
            case 'posts':
                loadScript('js/post.js');
                break;
            case 'banners':
                loadScript('js/banner.js');
                break;
            case 'contact_forms':
                loadScript('js/contact_forms.js');
                break;
            case 'testimonials':
                loadScript('js/testimonial.js');
                break;
            default:
                loadScript('js/script.js');
                break;
        }
    }

    // Initialize page-specific functions after content is loaded
    function initializePageScripts() {
        const page = window.location.pathname.split('/').pop(); // Get the current page name

        switch (page) {
            case 'posts':
                initializePostPage();  // Initialize specific functions for the posts page
                break;
            case 'banners':
                initializeBannerPage();  // Initialize specific functions for the banners page
                break;
            case 'contact_forms':
                initializeContactFormPage();  // Initialize specific functions for contact forms page
                break;
            case 'testimonials':
                initializeTestimonialPage();  // Initialize specific functions for testimonials page
                break;
            default:
                console.log('No page-specific initialization required');
                break;
        }
    }


    function initializePostPage() {
        fetchPosts(); 
        const postForm = document.getElementById('postForm');
        if (postForm) {
            postForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const postId = this.getAttribute('data-id'); // Get the post ID for update

                const endpoint = postId ? `app/update_posts.php?id=${postId}` : 'app/add_post.php';

                fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            fetchPosts();
                            this.reset();
                            this.removeAttribute('data-id');
                            document.getElementById('current-image').innerHTML = '';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while submitting the post.');
                    });
            });
        }
    }

    // Initialize banner page specific functionality
    function initializeBannerPage() {
        fetchBanners();  
        
    const bannerForm = document.getElementById('bannerForm');
    if (bannerForm) {
   bannerForm.addEventListener('submit', function(e) {
       e.preventDefault();
       const formData = new FormData(this);
       const bannerId = this.getAttribute('data-id'); // Get the banner ID for the update
   
       const endpoint = bannerId ? `app/update_banner.php?id=${bannerId}` : 'app/add_banner.php';
   
       fetch(endpoint, {
           method: 'POST',
           body: formData,
       })
       .then(response => response.json())
       .then(data => {
           if (data.success) {
               alert(data.message);
               fetchBanners(); 
               this.reset();
               this.removeAttribute('data-id'); 
               document.getElementById('current-image').innerHTML='';
           } else {
               alert(data.message);
           }
       })
       .catch(error => {
           console.error('Error:', error);
           alert('An error occurred while submitting the banner.');
       });
   });
} else {
   console.error('Banner form not found');
}
    }

    // Initialize contact forms page specific functionality
    function initializeContactFormPage() {
       

        const formList = document.getElementById('form-list');


    const fetchContactForms = (page = 1) => {
        fetch(`app/get_contact_forms.php?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayForms(data.data); 
                    displayPaginationContactForm(data.pages, page); 
                } else {
                    formList.innerHTML = '<p class="text-danger">Failed to load contact forms.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching contact forms:', error);
                formList.innerHTML = '<p class="text-danger">An error occurred while fetching contact forms.</p>';
            });
    };
    fetchContactForms();
    
    const displayForms = (forms) => {

        if (!formList) {
            console.error("Error: form-list element not found.");
            return;
        }
        formList.innerHTML = ''; 

        if (forms.length === 0) {
            formList.innerHTML = '<p class="text-muted">No contact forms available.</p>';
            return;
        }

      
        const table = document.createElement('table');
        table.classList.add('table', 'table-striped', 'table-bordered');
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ${forms.map(form => `
                    <tr>
                        <td>${form.user_name}</td>
                        <td>${form.user_email}</td>
                        <td>${form.message}</td>
                        <td>${form.status}</td>
                        <td>
                            <button class="btn btn-success btn-sm" data-id="${form.id}" data-status="approved">Approve</button>
                            <button class="btn btn-danger btn-sm" data-id="${form.id}" data-status="rejected">Reject</button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        `;
        formList.appendChild(table);

        document.querySelectorAll('button[data-id]').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const status = button.getAttribute('data-status');
                updateContactStatus(id, status);
            });
        });
    };

  
    const displayPaginationContactForm = (totalPages, currentPage) => {
        const pagination = document.createElement('div');
        pagination.classList.add('pagination', 'mt-3', 'text-center');
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.classList.add('btn', 'btn-primary', 'm-1');
            pageBtn.textContent = i;
            pageBtn.disabled = i === currentPage;
            pageBtn.addEventListener('click', () => fetchContactForms(i));
            pagination.appendChild(pageBtn);
        }

        formList.appendChild(pagination);
    };
    }

    // Initialize testimonials page specific functionality
    function initializeTestimonialPage() {
      fetchTestimonials();  
    const testimonialForm = document.getElementById('testimonialForm');
             if (testimonialForm) {
            testimonialForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const testimonialId = this.getAttribute('data-id'); // Get the testimonial ID for the update
            
                const endpoint = testimonialId ? `app/update_testimonial.php?id=${testimonialId}` : 'app/add_testimonial.php';
            
                fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        fetchTestimonials(); 
                        this.reset();
                        this.removeAttribute('data-id'); 
                        document.getElementById('current-image')?.remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the testimonial.');
                });
            });
        } else {
            console.error('Testimonials form not found');
        }
    
    }

    document.body.addEventListener("click", (event) => {
        if (event.target.classList.contains("menu-link")) {
            event.preventDefault();  
            const page = event.target.getAttribute("data-page");  
            if (page) {
                loadContent(`pages/${page}.php`);
                loadPageScripts(page);  // Load page-specific scripts
                window.history.pushState({}, '', `${page}`);
            }
        }
    });

    function onRouteChange() {
        const path = window.location.pathname;  
        let page = '';

        if (path === '/RasilForce/public/admin/' || path === '/RasilForce/public/admin') {
            page = defaultPage;  
        } else if (path.includes('banners')) {
            page = 'banners';
        } else if (path.includes('posts')) {
            page = 'posts';
        } 
        else if (path.includes('contact_forms')) {
            page = 'contact_forms';
        } 
        else if (path.includes('testimonials')) {
            page = 'testimonials';
        } 
        else {
            page = 'default'; 
        }

        // If the page has changed, load content and scripts
        if (page !== currentPage) {
            currentPage = page;

            loadContent(`pages/${page}.php`);
            loadPageScripts(page);
        }
    }

    let currentPage = '';

    window.addEventListener('popstate', onRouteChange);

    onRouteChange(); // Initialize on first load

    function navigateTo(page) {
        if (page !== currentPage) {
            window.history.pushState({}, '', `${page}`);
            onRouteChange();  
        }
    }
});
