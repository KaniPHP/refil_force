
<section id="banner" class="mb-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-success mb-4">Manage Banner</h2>

        <form id="bannerForm" class="mb-4" enctype="multipart/form-data">
            <div id="current-image" class="mb-3">
            </div>
            <div class="mb-3">
                <input type="file" name="banner_image" class="form-control">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Banner Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <textarea name="description" class="form-control" placeholder="Banner Description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Upload Banner</button>
        </form>

        <div id="banner-display" class="border p-3" style="min-height: 200px; background-color: #f9f9f9;">
        </div>
        <div id="pagination"></div>
    </div>
</section>
