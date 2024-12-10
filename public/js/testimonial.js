function displayTestimonials(testimonials, currentPage, totalPages) {
    const TestimonialList = document.getElementById('testimonial-display');
    TestimonialList.innerHTML = ''; 

    if (testimonials.length === 0) {
        TestimonialList.innerHTML = '<p class="text-muted">No testimonials available.</p>';
        return;
    }

    const recordsPerPage = 10; 
    const startSerial = (currentPage - 1) * recordsPerPage + 1; 
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped', 'table-bordered', 'display'); 
    table.id = "testimonial-table"; 
    table.innerHTML = `
        <thead>
            <tr>
                <th>S.No</th>
                <th>Title</th>
                <th>Testimonial</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            ${testimonials.map((b, index) => `
                <tr>
                    <td>${startSerial + index}</td>   
                    <td>${b.name}</td>
                    <td>${b.testimonial}</td>
                    <td>${b.status ? b.status.charAt(0).toUpperCase() + b.status.slice(1).toLowerCase() : '-'}</td>
                    <td>${b.created_at}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="editTestimonials(${b.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteTestimonials(${b.id})">Delete</button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    TestimonialList.appendChild(table);


    displayPagination(currentPage, totalPages);  
}

function fetchTestimonials(page = 1) {
    fetch(`app/get_testimonials.php?page=${page}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayTestimonials(data.data, data.current_page, data.total_pages); 
            } else {
                alert('Failed to load testimonials.');
            }
        })
        .catch((error) => {
            console.error('Error fetching testimonials:', error);
            alert('An error occurred while loading testimonials.');
        });
}


fetchTestimonials();  


function displayPagination(currentPage, totalPages) {
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = ''; 

    
    const prevButton = document.createElement('button');
    prevButton.classList.add('btn', 'btn-primary', 'm-1');
    prevButton.textContent = 'Previous';
    prevButton.disabled = currentPage === 1; 
    prevButton.addEventListener('click', () => fetchTestimonials(currentPage - 1));
    paginationContainer.appendChild(prevButton);

    
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.classList.add('btn', 'btn-primary', 'm-1');
        pageButton.textContent = i;
        pageButton.disabled = i === currentPage;  
        pageButton.addEventListener('click', () => fetchTestimonials(i));
        paginationContainer.appendChild(pageButton);
    }

   
    const nextButton = document.createElement('button');
    nextButton.classList.add('btn', 'btn-primary', 'm-1');
    nextButton.textContent = 'Next';
    nextButton.disabled = currentPage === totalPages;  
    nextButton.addEventListener('click', () => fetchTestimonials(currentPage + 1));
    paginationContainer.appendChild(nextButton);
}

function deleteTestimonials(id) {
    if (confirm('Are you sure you want to delete this testimonial?')) {
        const formData = new FormData();
        formData.append('id', id); 

        fetch(`app/delete_testimonial.php`, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Testimonials deleted successfully.');
                fetchTestimonials();  
            } else {
                alert(`Failed to delete testimonial: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the testimonial.');
        });
    }
}

function editTestimonials(id) {
    fetch(`app/get_testimonials.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            const testimonial = data.data;    
            document.querySelector('input[name="name"]').value = testimonial.name;
            document.querySelector('textarea[name="testimonial"]').value = testimonial.testimonial;
            document.querySelector('select[name="status"]').value = testimonial.status;
            document.querySelector('form').setAttribute('data-id', testimonial.id); // Store the testimonial ID in form data attribute
            } else {
                alert('Failed to load testimonial data for editing.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading the testimonial data.');
        });
}


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
