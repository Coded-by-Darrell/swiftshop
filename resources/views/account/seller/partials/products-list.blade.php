<div class="products-grid">
    @forelse($products as $product)
    <div class="product-card" data-product-id="{{ $product->id }}">
        <!-- Product Image -->
        <div class="product-image-container">
            @if($product->images && $product->images->count() > 0)
                <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="product-image">
            @else
                <div class="product-image-placeholder">
                    <i class="fas fa-image"></i>
                    <span>No Image</span>
                </div>
            @endif
            
            <!-- Product Status Badge -->
            <div class="product-status-badge status-{{ $product->status }}">
                {{ ucfirst($product->status) }}
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="product-info">
            <h6 class="product-name">{{ $product->name }}</h6>
            <p class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</p>
            <div class="product-price">₱{{ number_format($product->getDisplayPrice(), 2) }}</div>
            <div class="product-stock">
                Stock: {{ $product->getTotalStock() }} units
                @if($product->getTotalStock() <= 0)
                    <span class="stock-warning">Out of Stock</span>
                @endif
            </div>
        </div>
        
        <!-- Product Actions -->
        <div class="product-actions">
            <!-- Visibility Toggle (Eye Icon) -->
            @if($product->status !== 'pending')
            <button type="button" 
                    class="action-btn visibility-btn" 
                    data-product-id="{{ $product->id }}"
                    data-current-status="{{ $product->status }}"
                    title="Toggle Visibility">
                <i class="fas {{ $product->status === 'active' ? 'fa-eye' : 'fa-eye-slash' }}"></i>
            </button>
            @else
            <button type="button" class="action-btn visibility-btn disabled" disabled title="Pending Approval">
                <i class="fas fa-clock"></i>
            </button>
            @endif
            
            <!-- Edit Button (Pencil Icon) -->
            @if($product->status !== 'pending')
            <button type="button" 
                    class="action-btn edit-btn" 
                    data-product-id="{{ $product->id }}"
                    title="Edit Product">
                <i class="fas fa-pencil-alt"></i>
            </button>
            @else
            <button type="button" class="action-btn edit-btn disabled" disabled title="Cannot Edit Pending">
                <i class="fas fa-pencil-alt"></i>
            </button>
            @endif
            
            <!-- Delete Button (Trash Icon) -->
            <button type="button" 
                    class="action-btn delete-btn" 
                    data-product-id="{{ $product->id }}"
                    title="Delete Product">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="empty-products-state">
        <div class="empty-state-content">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted mb-2">No Products Found</h5>
            <p class="text-muted mb-4">Start by adding your first product to your store</p>
            <button type="button" class="btn btn-primary" id="addFirstProductBtn">
                <i class="fas fa-plus me-2"></i>Add Your First Product
            </button>
        </div>
    </div>
    @endforelse
</div>