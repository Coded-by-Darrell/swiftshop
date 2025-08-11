/**
 * Seller Products Management JavaScript
 * Handles product CRUD operations, search, filtering, and modals
 */

class SellerProducts {
    constructor() {
        this.currentFilter = 'all';
        this.currentSearch = '';
        this.currentPage = 1;
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupCSRFToken();
    }
    
    setupCSRFToken() {
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }
    
    setupEventListeners() {
        // Search functionality
        $('#productSearch').on('input', this.debounce((e) => {
            this.handleSearch(e.target.value);
        }, 500));
        
        // Filter buttons
        $(document).on('click', '.filter-pill', (e) => {
            this.handleFilterClick(e.currentTarget);
        });
        
        // Pagination
        $(document).on('click', '.pagination-btn', (e) => {
            if (!$(e.currentTarget).hasClass('disabled')) {
                const page = $(e.currentTarget).data('page');
                this.loadProducts(this.currentFilter, this.currentSearch, page);
            }
        });
        
        // Add Product Modal
        $(document).on('click', '#addProductBtn, #addFirstProductBtn', () => {
            this.openAddProductModal();
        });
        
        // Product Actions
        $(document).on('click', '.visibility-btn', (e) => {
            if (!$(e.currentTarget).hasClass('disabled')) {
                this.toggleProductVisibility(e.currentTarget);
            }
        });
        
        $(document).on('click', '.edit-btn', (e) => {
            if (!$(e.currentTarget).hasClass('disabled')) {
                this.openEditProductModal(e.currentTarget);
            }
        });
        
        $(document).on('click', '.delete-btn', (e) => {
            this.confirmDeleteProduct(e.currentTarget);
        });
        
        // Form Submissions
        $(document).on('submit', '#addProductForm', (e) => {
            e.preventDefault();
            this.submitAddProductForm();
        });
        
        $(document).on('submit', '#editProductForm', (e) => {
            e.preventDefault();
            this.submitEditProductForm();
        });
        
        // Image Preview
        $(document).on('change', '#productImages, #editProductImages', (e) => {
            this.previewImages(e.currentTarget);
        });
        
        // Remove current images in edit modal
        $(document).on('click', '.btn-remove-image', (e) => {
            this.removeCurrentImage(e.currentTarget);
        });
    }
    
    // Debounce function for search
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Handle search input
    handleSearch(searchValue) {
        this.currentSearch = searchValue;
        this.currentPage = 1;
        this.loadProducts(this.currentFilter, this.currentSearch, 1);
    }
    
    // Handle filter button clicks
    handleFilterClick(button) {
        // Update active filter button
        $('.filter-pill').removeClass('active');
        $(button).addClass('active');
        
        // Get filter value and load products
        this.currentFilter = $(button).data('filter');
        this.currentPage = 1;
        this.loadProducts(this.currentFilter, this.currentSearch, 1);
    }
    
    // Load products with AJAX
    loadProducts(filter, search, page) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();
        
        $.ajax({
            url: '/test-seller/products',
            method: 'GET',
            data: {
                filter: filter,
                search: search,
                page: page
            },
            success: (response) => {
                if (response.success) {
                    $('#productsListContainer').html(response.html);
                    $('#productsPaginationContainer').html(response.pagination);
                    this.currentPage = page;
                }
            },
            error: (xhr) => {
                this.showToast('Error loading products', 'error');
                console.error('Error loading products:', xhr);
            },
            complete: () => {
                this.isLoading = false;
                this.hideLoading();
            }
        });
    }
    
    // Show loading state
    showLoading() {
        $('#productsListContainer').addClass('loading');
    }
    
    // Hide loading state
    hideLoading() {
        $('#productsListContainer').removeClass('loading');
    }
    
    // Open Add Product Modal
    openAddProductModal() {
        $.ajax({
            url: '/test-seller/products/create',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    $('#modalContainer').html(response.html);
                    $('#addProductModal').modal('show');
                }
            },
            error: (xhr) => {
                this.showToast('Error opening add product modal', 'error');
                console.error('Error opening modal:', xhr);
            }
        });
    }
    
    // Submit Add Product Form
    submitAddProductForm() {
        const form = $('#addProductForm')[0];
        const formData = new FormData(form);
        
        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading on submit button
        const submitBtn = $('#saveProductBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        $.ajax({
            url: '/test-seller/products',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    $('#addProductModal').modal('hide');
                    this.showToast(response.message, 'success');
                    this.loadProducts(this.currentFilter, this.currentSearch, this.currentPage);
                } else {
                    this.showToast(response.message, 'error');
                }
            },
            error: (xhr) => {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    this.showValidationErrors(errors);
                } else {
                    this.showToast('Error creating product', 'error');
                }
            },
            complete: () => {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }
    
    // Toggle Product Visibility
    toggleProductVisibility(button) {
        const productId = $(button).data('product-id');
        const currentStatus = $(button).data('current-status');
        
        $.ajax({
            url: `/test-seller/products/${productId}/toggle-visibility`,
            method: 'POST',
            success: (response) => {
                if (response.success) {
                    // Update button icon and data
                    const icon = $(button).find('i');
                    if (response.status === 'active') {
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                        $(button).data('current-status', 'active');
                    } else {
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                        $(button).data('current-status', 'inactive');
                    }
                    
                    // Update status badge
                    const productCard = $(button).closest('.product-card');
                    const statusBadge = productCard.find('.product-status-badge');
                    statusBadge.removeClass('status-active status-inactive')
                              .addClass(`status-${response.status}`)
                              .text(response.status.charAt(0).toUpperCase() + response.status.slice(1));
                    
                    this.showToast(response.message, 'success');
                } else {
                    this.showToast(response.message, 'error');
                }
            },
            error: (xhr) => {
                this.showToast('Error updating product visibility', 'error');
                console.error('Error toggling visibility:', xhr);
            }
        });
    }
    
    // Open Edit Product Modal
    openEditProductModal(button) {
        const productId = $(button).data('product-id');
        
        $.ajax({
            url: `/test-seller/products/${productId}/edit`,
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    $('#modalContainer').html(response.html);
                    $('#editProductModal').modal('show');
                } else {
                    this.showToast(response.message, 'error');
                }
            },
            error: (xhr) => {
                this.showToast('Error opening edit modal', 'error');
                console.error('Error opening edit modal:', xhr);
            }
        });
    }
    
    // Submit Edit Product Form
    submitEditProductForm() {
        const form = $('#editProductForm')[0];
        const formData = new FormData(form);
        const productId = $('#editProductId').val();
        
        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading on submit button
        const submitBtn = $('#updateProductBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        $.ajax({
            url: `/test-seller/products/${productId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    $('#editProductModal').modal('hide');
                    this.showToast(response.message, 'success');
                    this.loadProducts(this.currentFilter, this.currentSearch, this.currentPage);
                } else {
                    this.showToast(response.message, 'error');
                }
            },
            error: (xhr) => {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    this.showValidationErrors(errors, 'edit');
                } else {
                    this.showToast('Error updating product', 'error');
                }
            },
            complete: () => {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }
    
    // Confirm Delete Product
    confirmDeleteProduct(button) {
        const productId = $(button).data('product-id');
        const productCard = $(button).closest('.product-card');
        const productName = productCard.find('.product-name').text();
        
        if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
            this.deleteProduct(productId, productCard);
        }
    }
    
    // Delete Product
    deleteProduct(productId, productCard) {
        $.ajax({
            url: `/test-seller/products/${productId}`,
            method: 'DELETE',
            success: (response) => {
                if (response.success) {
                    // Animate removal
                    productCard.fadeOut(300, () => {
                        productCard.remove();
                        // If no products left, reload to show empty state
                        if ($('.product-card').length === 0) {
                            this.loadProducts(this.currentFilter, this.currentSearch, this.currentPage);
                        }
                    });
                    this.showToast(response.message, 'success');
                } else {
                    this.showToast(response.message, 'error');
                }
            },
            error: (xhr) => {
                this.showToast('Error deleting product', 'error');
                console.error('Error deleting product:', xhr);
            }
        });
    }
    
    // Preview uploaded images
    previewImages(input) {
        const files = input.files;
        const isEdit = input.id === 'editProductImages';
        const containerId = isEdit ? '#editImagePreviewContainer' : '#imagePreviewContainer';
        const gridId = isEdit ? '#editImagePreviewGrid' : '#imagePreviewGrid';
        
        if (files.length > 0) {
            $(containerId).show();
            $(gridId).empty();
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewHtml = `
                            <div class="image-preview-item">
                                <img src="${e.target.result}" alt="Preview ${index + 1}">
                                <span class="image-name">${file.name}</span>
                            </div>
                        `;
                        $(gridId).append(previewHtml);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            $(containerId).hide();
        }
    }
    
    // Remove current image in edit modal
    removeCurrentImage(button) {
        const imageItem = $(button).closest('.current-image-item');
        const imageId = $(button).data('image-id');
        
        if (confirm('Are you sure you want to remove this image?')) {
            imageItem.fadeOut(300, () => {
                imageItem.remove();
            });
            
            // Add hidden input to track removed images
            $('<input>').attr({
                type: 'hidden',
                name: 'removed_images[]',
                value: imageId
            }).appendTo('#editProductForm');
        }
    }
    
    // Show validation errors
    showValidationErrors(errors, prefix = '') {
        Object.keys(errors).forEach(field => {
            const fieldId = prefix ? `${prefix}${field.charAt(0).toUpperCase()}${field.slice(1)}` : field;
            const input = $(`#${fieldId}, [name="${field}"]`);
            
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').text(errors[field][0]);
        });
    }
    
    // Show toast notification
    showToast(message, type = 'info') {
        // Remove existing toasts
        $('.toast-notification').remove();
        
        const toastClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const toastHtml = `
            <div class="toast-notification alert ${toastClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex align-items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                  type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} me-2"></i>
                    <div>${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(toastHtml);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            $('.toast-notification').alert('close');
        }, 5000);
    }
}

// Replace the last few lines of seller-products.js
document.addEventListener('DOMContentLoaded', () => {
    new SellerProducts();
});