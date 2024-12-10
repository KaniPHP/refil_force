fetchPosts();  
function displayPosts(posts, currentPage, totalPages) {
    const postList = document.getElementById('post-display');
    console.log(postList);
    postList.innerHTML = ''; 

    if (posts.length === 0) {
        postList.innerHTML = '<p class="text-muted">No posts available.</p>';
        return;
    }

    const recordsPerPage = 10; 
    const startSerial = (currentPage - 1) * recordsPerPage + 1; 
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped', 'table-bordered', 'display'); 
    table.id = "post-table"; 
    table.innerHTML = `
        <thead>
            <tr>
                <th>S.No</th>
                <th>Image</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            ${posts.map((b, index) => `
                <tr>
                    <td>${startSerial + index}</td> 
                    <td>
                        ${b.image_path ? `<img src="../${b.image_path}" alt="Post Image" style="width: 100px; height: auto;">` : '-'}
                    </td>   
                    <td>${b.title}</td>
                    <td>${b.content}</td>
                    <td>${b.created_at}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="editPost(${b.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deletePost(${b.id})">Delete</button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    postList.appendChild(table);

    displayPagination(currentPage, totalPages);  
}

function fetchPosts(page = 1) {
    fetch(`app/get_posts.php?page=${page}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayPosts(data.data, data.current_page, data.total_pages); 
            } else {
                alert('Failed to load posts.');
            }
        })
        .catch((error) => {
            console.error('Error fetching posts:', error);
            alert('An error occurred while loading posts.');
        });
}



function displayPagination(currentPage, totalPages) {
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = ''; 

    
    const prevButton = document.createElement('button');
    prevButton.classList.add('btn', 'btn-primary', 'm-1');
    prevButton.textContent = 'Previous';
    prevButton.disabled = currentPage === 1; 
    prevButton.addEventListener('click', () => fetchPosts(currentPage - 1));
    paginationContainer.appendChild(prevButton);

    
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.classList.add('btn', 'btn-primary', 'm-1');
        pageButton.textContent = i;
        pageButton.disabled = i === currentPage;  
        pageButton.addEventListener('click', () => fetchPosts(i));
        paginationContainer.appendChild(pageButton);
    }

   
    const nextButton = document.createElement('button');
    nextButton.classList.add('btn', 'btn-primary', 'm-1');
    nextButton.textContent = 'Next';
    nextButton.disabled = currentPage === totalPages;  
    nextButton.addEventListener('click', () => fetchPosts(currentPage + 1));
    paginationContainer.appendChild(nextButton);
}

function deletePost(id) {
    if (confirm('Are you sure you want to delete this post?')) {
        const formData = new FormData();
        formData.append('id', id); 

        fetch(`app/delete_post.php`, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Post deleted successfully.');
                fetchPosts();  
            } else {
                alert(`Failed to delete post: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the post.');
        });
    }
}

function editPost(id) {
    fetch(`app/get_posts.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            const post = data.data;
                const imageDisplay = document.getElementById('current-image');
                if (post.image_path) {
                    imageDisplay.innerHTML = `<img src="../${post.image_path}" alt="Post Image" style="max-width: 100%; height: auto;">`;
                } else {
                    imageDisplay.innerHTML = '<p>No image available</p>';
                }
                document.querySelector('input[name="title"]').value = post.title;

                document.querySelector('textarea[name="content"]').value = post.content;
                document.querySelector('form').setAttribute('data-id', post.id); // Store the post ID in form data attribute
            } else {
                alert('Failed to load post data for editing.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading the post data.');
        });
}


    const postForm = document.getElementById('postForm');
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            console.log("asdasd");
            e.preventDefault();
            const formData = new FormData(this);
            const postId = this.getAttribute('data-id'); // Get the post ID for the update
        
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
                    document.getElementById('current-image').innerHTML='';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the post.');
            });
        });
    } else {
        console.error('Banner form not found');
    }
