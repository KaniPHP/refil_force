<section id="testimonials" class="mb-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-primary mb-4">Manage Testimonials</h2>

        <form id="testimonialForm" class="mb-4">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="mb-3">
                <textarea name="testimonial" class="form-control" placeholder="Testimonial" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Add Testimonial</button>
        </form>

        <div id="testimonial-display" class="border p-3" style="min-height: 200px; background-color: #f9f9f9;">
        </div>
        <div id="pagination"></div>
    </div>
</section>


 