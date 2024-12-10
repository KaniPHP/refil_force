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

document.getElementById('postForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('app/posts.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => alert(data.success ? "Post added!" : "Error adding post."));
});


document.addEventListener('DOMContentLoaded', () => {
    const formList = document.getElementById('form-list');

    // Function to fetch contact forms
    const fetchContactForms = (page = 1) => {
        fetch(`get_contact_forms.php?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayForms(data.data); // Show the fetched forms
                    displayPagination(data.pages, page); // Show pagination
                } else {
                    formList.innerHTML = '<p class="text-danger">Failed to load contact forms.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching contact forms:', error);
                formList.innerHTML = '<p class="text-danger">An error occurred while fetching contact forms.</p>';
            });
    };

    // Function to display contact forms
    const displayForms = (forms) => {
        formList.innerHTML = ''; // Clear the form list
        if (forms.length === 0) {
            formList.innerHTML = '<p class="text-muted">No contact forms available.</p>';
            return;
        }

        forms.forEach(form => {
            const formCard = document.createElement('div');
            formCard.classList.add('card', 'mb-3', 'p-3');
            formCard.innerHTML = `
                <h5>${form.name} (${form.email})</h5>
                <p>${form.message}</p>
                <p><strong>Status:</strong> ${form.status}</p>
                <div>
                    <button class="btn btn-success btn-sm" data-id="${form.id}" data-status="approved">Approve</button>
                    <button class="btn btn-danger btn-sm" data-id="${form.id}" data-status="rejected">Reject</button>
                </div>
            `;
            formList.appendChild(formCard);
        });

        // Attach event listeners to approve/reject buttons
        document.querySelectorAll('button[data-id]').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const status = button.getAttribute('data-status');
                updateContactStatus(id, status);
            });
        });
    };

    // Function to display pagination
    const displayPagination = (totalPages, currentPage) => {
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

    // Function to update the status of a contact form
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
                    fetchContactForms(); // Refresh the contact forms list
                } else {
                    alert('Failed to update contact form status.');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                alert('An error occurred. Please try again.');
            });
    };

    // Initial fetch of contact forms
    fetchContactForms();
});



