<section id="posts" class="mb-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-success mb-4">Manage Posts</h2>
        <form id="postForm" class="mb-4">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Post Title" required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" placeholder="Post Content" rows="5" required></textarea>
            </div>
            <div id="current-image" class="mb-3">
            </div>
            <div class="mb-3">
                <input type="file" name="image" id="imageUpload" class="form-control">
            </div>
            <button type="submit" class="btn btn-success w-100">Add Post</button>
        </form>
        <div id="post-display" class="border p-3" style="min-height: 200px; background-color: #f9f9f9;">
        </div>
        <div id="pagination"></div>
    </div>
</section>