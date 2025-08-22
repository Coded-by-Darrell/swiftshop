/**
 * Seller Orders Management JavaScript
 * Handles order search, filtering, and status updates
 */

class SellerOrders {
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
        $('#orderSearch').on('input', this.debounce((e) => {
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
                this.loadOrders(this.currentFilter, this.currentSearch, page);
            }
        });
        
        // Status update buttons
        $(document).on('click', '.status-btn', (e) => {
            this.handleStatusUpdate(e.currentTarget);
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
        this.loadOrders(this.currentFilter, this.currentSearch, 1);
    }
    
    // Handle filter button clicks
    handleFilterClick(button) {
        // Update active filter button
        $('.filter-pill').removeClass('active');
        $(button).addClass('active');
        
        // Get filter value and load orders
        this.currentFilter = $(button).data('filter');
        this.currentPage = 1;
        this.loadOrders(this.currentFilter, this.currentSearch, 1);
    }
    
    // Load orders with AJAX
    loadOrders(filter, search, page) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();
        
        $.ajax({
            url: '/test-seller/orders',
            method: 'GET',
            data: {
                status: filter,
                search: search,
                page: page
            },
            success: (response) => {
                if (response.success) {
                    $('#ordersListContainer').html(response.html);
                    $('#ordersPaginationContainer').html(response.pagination);
                    this.currentPage = page;
                    
                    // Update URL without page reload
                    const url = new URL(window.location);
                    if (filter !== 'all') {
                        url.searchParams.set('status', filter);
                    } else {
                        url.searchParams.delete('status');
                    }
                    
                    if (search) {
                        url.searchParams.set('search', search);
                    } else {
                        url.searchParams.delete('search');
                    }
                    
                    if (page > 1) {
                        url.searchParams.set('page', page);
                    } else {
                        url.searchParams.delete('page');
                    }
                    
                    window.history.replaceState({}, '', url);
                }
            },
            error: (xhr) => {
                this.showToast('Error loading orders', 'error');
                console.error('Error loading orders:', xhr);
            },
            complete: () => {
                this.isLoading = false;
                this.hideLoading();
            }
        });
    }
    
    // Handle status update button clicks
    handleStatusUpdate(button) {
        const itemId = $(button).data('item-id');
        const action = $(button).data('action');
        
        // Show confirmation dialog
        const actionText = action === 'processing' ? 'process this order' : 'mark this order as shipped';
        if (!confirm(`Are you sure you want to ${actionText}?`)) {
            return;
        }
        
        // Disable button and show loading
        const originalText = $(button).html();
        $(button).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
        
        $.ajax({
            url: `/test-seller/orders/update-status/${itemId}`,
            method: 'POST',
            data: {
                status: action
            },
            success: (response) => {
                if (response.success) {
                    this.showToast(response.message, 'success');
                    
                    // Update the status badge and button
                    this.updateOrderItemUI($(button), response.new_status);
                } else {
                    this.showToast(response.message, 'error');
                    $(button).prop('disabled', false).html(originalText);
                }
            },
            error: (xhr) => {
                this.showToast('Error updating order status', 'error');
                $(button).prop('disabled', false).html(originalText);
                console.error('Error updating status:', xhr);
            }
        });
    }
    
    // Update order item UI after status change
    updateOrderItemUI(button, newStatus) {
        const itemContainer = $(button).closest('.order-item');
        const statusBadge = itemContainer.find('.status-badge');
        const actionsContainer = $(button).closest('.item-status-and-actions');
        
        // Update status badge
        statusBadge.removeClass('status-pending status-processing status-shipped status-delivered status-cancelled')
                  .addClass(`status-${newStatus}`)
                  .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
        
        // Update action button based on new status
        if (newStatus === 'processing') {
            actionsContainer.find('.status-btn').remove();
            actionsContainer.append(`
                <button class="btn btn-sm btn-info status-btn" 
                        data-item-id="${$(button).data('item-id')}" 
                        data-action="shipped">
                    <i class="fas fa-shipping-fast me-1"></i>Mark as Shipped
                </button>
            `);
        } else if (newStatus === 'shipped') {
            actionsContainer.find('.status-btn').remove();
            actionsContainer.append(`
                <div class="text-muted small">
                    <i class="fas fa-clock me-1"></i>Waiting for customer confirmation
                </div>
            `);
        }
    }
    
    // Show loading state
    showLoading() {
        $('#ordersListContainer').addClass('loading');
    }
    
    // Hide loading state
    hideLoading() {
        $('#ordersListContainer').removeClass('loading');
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

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    new SellerOrders();
});