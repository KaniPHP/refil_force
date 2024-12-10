fetch('app/contact_status.php')
    .then(response => response.json())
    .then(data => {
        const formList = document.getElementById('form-list');
        data.forEach(form => {
            formList.innerHTML += `
                <div>
                    <p>${form.message}</p>
                    <button onclick="approveForm(${form.id})">Approve</button>
                    <button onclick="rejectForm(${form.id})">Reject</button>
                </div>
            `;
        });
    });

function approveForm(id) {
    fetch(`app/contact_status.php?id=${id}&action=approve`, { method: 'POST' })
        .then(() => alert('Form approved!'));
}

function rejectForm(id) {
    fetch(`app/contact_status.php?id=${id}&action=reject`, { method: 'POST' })
        .then(() => alert('Form rejected!'));
}





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


    const updateContactStatus = (id, status) => {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', status);

        fetch('app/update_contact_status.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    fetchContactForms(); 
                } else {
                    alert('Failed to update contact form status.');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                alert('An error occurred. Please try again.');
            });
    };




