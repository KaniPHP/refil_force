fetch('app/posts.php')
    .then(response => response.json())
    .then(posts => {
        const postList = document.getElementById('post-list');
        posts.forEach(post => {
            postList.innerHTML += `
                <div>
                    <h3>${post.title}</h3>
                    <p>${post.content}</p>
                    ${post.image_path ? `<img src="${post.image_path}" alt="Post Image">` : ''}
                </div>
            `;
        });
    });

fetch('/app/contact_status.php?user=1') 
    .then(response => response.json())
    .then(status => {
        document.getElementById('status').innerText = `Form Status: ${status}`;
    });
