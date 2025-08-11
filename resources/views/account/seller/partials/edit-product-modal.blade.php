<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="editProductForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editProductId" name="product_id" value="{{ $product->id }}">
                
                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="editProductName" class="form-label required">Product Name</label>
                        <input type="text" class="form-control" id="editProductName" name="name" value="{{ $product->name }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="editProductCategory" class="form-label required">Category</label>
                        <select class="form-select" id="editProductCategory" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Price and Stock Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editProductPrice" class="form-label required">Price (₱)</label>
                                <input type="number" class="form-control" id="editProductPrice" name="price" 
                                       value="{{ $product->price }}" step="0.01" min="0" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editProductStock" class="form-label required">Stock Quantity</label>
                                <input type="number" class="form-control" id="editProductStock" name="stock_quantity" 
                                       value="{{ $product->stock_quantity }}" min="0" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label required">Description</label>
                        <textarea class="form-control" id="editProductDescription" name="description" rows="4" required>{{ $product->description }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- Existing Images -->
                    @if($product->images && $product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Current Images</label>
                        <div class="current-images-grid">
                            @foreach($product->images as $image)
                            <div class="current-image-item" data-image-id="{{ $image->id }}">
                                <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text }}" class="current-image">
                                <button type="button" class="btn-remove-image" data-image-id="{{ $image->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Add New Images -->
                    <div class="mb-3">
                        <label for="editProductImages" class="form-label">Add New Images</label>
                        <input type="file" class="form-control" id="editProductImages" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            You can add more images. Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB per image.
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <!-- New Image Preview Container -->
                    <div id="editImagePreviewContainer" class="image-preview-container mb-3" style="display: none;">
                        <label class="form-label">New Images Preview</label>
                        <div id="editImagePreviewGrid" class="image-preview-grid"></div>
                    </div>
                    
                    <!-- Status Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Current Status:</strong> {{ ucfirst($product->status) }}
                        @if($product->status === 'pending')
                        <br><small>This product is pending admin approval.</small>
                        @endif
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateProductBtn">
                        <i class="fas fa-save me-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>