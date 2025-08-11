<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Add New Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="addProductForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="productName" class="form-label required">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="productCategory" class="form-label required">Category</label>
                        <select class="form-select" id="productCategory" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Price and Stock Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productPrice" class="form-label required">Price (₱)</label>
                                <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productStock" class="form-label required">Stock Quantity</label>
                                <input type="number" class="form-control" id="productStock" name="stock_quantity" min="0" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="productDescription" class="form-label required">Description</label>
                        <textarea class="form-control" id="productDescription" name="description" rows="4" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Product Images -->
                    <div class="mb-3">
                        <label for="productImages" class="form-label">Product Images</label>
                        <input type="file" class="form-control" id="productImages" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            You can select multiple images. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per image.
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Image Preview Container -->
                    <div id="imagePreviewContainer" class="image-preview-container mb-3" style="display: none;">
                        <label class="form-label">Image Preview</label>
                        <div id="imagePreviewGrid" class="image-preview-grid"></div>
                    </div>
                    
                    <!-- Important Notice -->
                    <div class="alert alert-info">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Notice:</strong> New products require admin approval before they become visible to customers.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveProductBtn">
                        <i class="fas fa-save me-2"></i>Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>