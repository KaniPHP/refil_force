function displayBanners(banners, currentPage, totalPages) {
    const bannerList = document.getElementById('banner-display');
    bannerList.innerHTML = ''; 

    if (banners.length === 0) {
        bannerList.innerHTML = '<p class="text-muted">No banners available.</p>';
        return;
    }

    const recordsPerPage = 10; 
    const startSerial = (currentPage - 1) * recordsPerPage + 1; 
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped', 'table-bordered', 'display'); 
    table.id = "banner-table"; 
    table.innerHTML = `
        <thead>
            <tr>
                <th>S.No</th>
                <th>Image</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            ${banners.map((b, index) => `
                <tr>
                    <td>${startSerial + index}</td> 
                    <td>
                        ${b.image_path ? `<img src="../${b.image_path}" alt="Banner Image" style="width: 100px; height: auto;">` : '-'}
                    </td>     
                    <td>${b.description}</td>
                    <td>${b.status ? b.status.charAt(0).toUpperCase() + b.status.slice(1).toLowerCase() : '-'}</td>
                    <td>${b.created_at}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="editBanner(${b.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteBanner(${b.id})">Delete</button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    bannerList.appendChild(table);


    displayPaginationBanner(currentPage, totalPages);  
}

function fetchBanners(page = 1) {
    fetch(`app/get_banners.php?page=${page}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayBanners(data.data, data.current_page, data.total_pages); 
            } else {
                alert('Failed to load banners.');
            }
        })
        .catch((error) => {
            console.error('Error fetching banners:', error);
            alert('An error occurred while loading banners.');
        });
}


fetchBanners();  


function displayPaginationBanner(currentPage, totalPages) {
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = ''; 

    
    const prevButton = document.createElement('button');
    prevButton.classList.add('btn', 'btn-primary', 'm-1');
    prevButton.textContent = 'Previous';
    prevButton.disabled = currentPage === 1; 
    prevButton.addEventListener('click', () => fetchBanners(currentPage - 1));
    paginationContainer.appendChild(prevButton);

    
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.classList.add('btn', 'btn-primary', 'm-1');
        pageButton.textContent = i;
        pageButton.disabled = i === currentPage;  
        pageButton.addEventListener('click', () => fetchBanners(i));
        paginationContainer.appendChild(pageButton);
    }

   
    const nextButton = document.createElement('button');
    nextButton.classList.add('btn', 'btn-primary', 'm-1');
    nextButton.textContent = 'Next';
    nextButton.disabled = currentPage === totalPages;  
    nextButton.addEventListener('click', () => fetchBanners(currentPage + 1));
    paginationContainer.appendChild(nextButton);
}

function deleteBanner(id) {
    if (confirm('Are you sure you want to delete this banner?')) {
        const formData = new FormData();
        formData.append('id', id); 

        fetch(`app/delete_banner.php`, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Banner deleted successfully.');
                fetchBanners();  
            } else {
                alert(`Failed to delete banner: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the banner.');
        });
    }
}

function editBanner(id) {
    fetch(`app/get_banners.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            const banner = data.data;
                const imageDisplay = document.getElementById('current-image');
                if (banner.image_path) {
                    imageDisplay.innerHTML = `<img src="../${banner.image_path}" alt="Banner Image" style="max-width: 100%; height: auto;">`;
                } else {
                    imageDisplay.innerHTML = '<p>No image available</p>';
                }

                document.querySelector('textarea[name="description"]').value = banner.description;
                document.querySelector('select[name="status"]').value = banner.status;
                document.querySelector('form').setAttribute('data-id', banner.id); // Store the banner ID in form data attribute
            } else {
                alert('Failed to load banner data for editing.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading the banner data.');
        });
}


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



