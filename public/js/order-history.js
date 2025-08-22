/**
 * Order History JavaScript
 * Handles search, filtering, and order actions
 */

class OrderHistory {
    constructor() {
        this.currentStatus = 'all';
        this.searchTimeout = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.setInitialState();
    }

    bindEvents() {
        // Search functionality
        this.bindSearchEvents();
        
        // Status filter tabs
        this.bindStatusFilterEvents();
        
        // Order action buttons
        this.bindOrderActionEvents();
        
        // Modal events
        this.bindModalEvents();
    }

    bindSearchEvents() {
        const searchInput = document.getElementById('orderSearchInput');
        
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                // Clear previous timeout
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }
                
                // Set new timeout for debounced search
                this.searchTimeout = setTimeout(() => {
                    this.performSearch(e.target.value);
                }, 500);
            });

            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (this.searchTimeout) {
                        clearTimeout(this.searchTimeout);
                    }
                    this.performSearch(e.target.value);
                }
            });
        }
    }

    bindStatusFilterEvents() {
        const statusPills = document.querySelectorAll('.status-pill');
        
        statusPills.forEach(pill => {
            pill.addEventListener('click', (e) => {
                e.preventDefault();
                const status = e.currentTarget.dataset.status;
                this.filterByStatus(status);
            });
        });
    }

    bindOrderActionEvents() {
        // View Details buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.view-details-btn')) {
                const orderId = e.target.closest('.view-details-btn').dataset.orderId;
                this.viewOrderDetails(orderId);
            }
        });

        // Cancel Order buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.cancel-order-btn')) {
                const orderId = e.target.closest('.cancel-order-btn').dataset.orderId;
                this.showCancelOrderModal(orderId);
            }
        });

        // Mark as Received buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.mark-received-btn')) {
                const orderId = e.target.closest('.mark-received-btn').dataset.orderId;
                this.showMarkReceivedModal(orderId);
            }
        });

        // Leave Review buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.leave-review-btn')) {
                const orderId = e.target.closest('.leave-review-btn').dataset.orderId;
                this.showReviewNotification(orderId);
            }
        });
    }

    // Add this new method after the existing methods:
    showReviewNotification(orderId) {
        // Show a simple notification that review feature is coming soon
        this.showSuccessMessage('Review feature coming soon! Thank you for your order.');
    }

    bindModalEvents() {
        // Cancel Order Modal
        const confirmCancelBtn = document.getElementById('confirmCancelOrder');
        if (confirmCancelBtn) {
            confirmCancelBtn.addEventListener('click', () => {
                this.confirmCancelOrder();
            });
        }

        // Mark as Received Modal
        const confirmReceivedBtn = document.getElementById('confirmMarkReceived');
        if (confirmReceivedBtn) {
            confirmReceivedBtn.addEventListener('click', () => {
                this.confirmMarkReceived();
            });
        }
    }

    setInitialState() {
        // Get current status from URL or default to 'all'
        const urlParams = new URLSearchParams(window.location.search);
        this.currentStatus = urlParams.get('status') || 'all';
        
        // Set active status pill
        this.updateActiveStatusPill(this.currentStatus);
    }

    performSearch(searchTerm) {
        this.showLoading();
        
        const params = new URLSearchParams({
            search: searchTerm,
            status: this.currentStatus
        });

        // Update URL without page reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);

        // Perform AJAX search
        fetch(`/test-account/order-history/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateOrdersList(data.html);
                this.updatePagination(data.pagination);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            this.showError('Failed to search orders. Please try again.');
        })
        .finally(() => {
            this.hideLoading();
        });
    }

    filterByStatus(status) {
        this.currentStatus = status;
        this.showLoading();
        
        const searchTerm = document.getElementById('orderSearchInput')?.value || '';
        
        const params = new URLSearchParams({
            status: status,
            search: searchTerm
        });

        // Update URL
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);

        // Update active pill
        this.updateActiveStatusPill(status);

        // Perform AJAX filter
        fetch(`/test-account/order-history?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (response.headers.get('content-type')?.includes('application/json')) {
                return response.json();
            } else {
                // If not JSON, it's probably the full HTML page, reload
                window.location.href = newUrl;
                return;
            }
        })
        .then(data => {
            if (data && data.success) {
                this.updateOrdersList(data.html);
                this.updatePagination(data.pagination);
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            // Fallback to page reload
            window.location.href = newUrl;
        })
        .finally(() => {
            this.hideLoading();
        });
    }

    updateActiveStatusPill(status) {
        // Remove active class from all pills
        document.querySelectorAll('.status-pill').forEach(pill => {
            pill.classList.remove('active');
        });
        
        // Add active class to selected pill
        const activePill = document.querySelector(`[data-status="${status}"]`);
        if (activePill) {
            activePill.classList.add('active');
        }
    }

    updateOrdersList(html) {
        const container = document.getElementById('ordersContainer');
        if (container) {
            container.innerHTML = html;
            container.classList.add('fade-in');
            
            // Remove fade-in class after animation
            setTimeout(() => {
                container.classList.remove('fade-in');
            }, 300);
        }
    }

    updatePagination(html) {
        const container = document.getElementById('paginationContainer');
        if (container) {
            container.innerHTML = html;
        }
    }

    viewOrderDetails(orderId) {
        // Redirect to order details page
        window.location.href = `/test-account/order-history/${orderId}`;
    }

    showCancelOrderModal(orderId) {
        this.selectedOrderId = orderId;
        const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        modal.show();
    }

    showMarkReceivedModal(orderId) {
        this.selectedOrderId = orderId;
        const modal = new bootstrap.Modal(document.getElementById('markReceivedModal'));
        modal.show();
    }

    confirmCancelOrder() {
        if (!this.selectedOrderId) return;

        const confirmBtn = document.getElementById('confirmCancelOrder');
        const originalText = confirmBtn.innerHTML;
        
        // Show loading state
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Cancelling...';
        confirmBtn.disabled = true;

        fetch(`/test-account/order-history/${this.selectedOrderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
                modal.hide();
                
                // Show success message
                this.showSuccessMessage(data.message);
                
                // Refresh the orders list
                this.refreshCurrentView();
            } else {
                throw new Error(data.message || 'Failed to cancel order');
            }
        })
        .catch(error => {
            console.error('Cancel order error:', error);
            this.showError('Failed to cancel order. Please try again.');
        })
        .finally(() => {
            // Reset button
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
    }

    confirmMarkReceived() {
        if (!this.selectedOrderId) return;

        const confirmBtn = document.getElementById('confirmMarkReceived');
        const originalText = confirmBtn.innerHTML;
        
        // Show loading state
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Confirming...';
        confirmBtn.disabled = true;

        fetch(`/test-account/order-history/${this.selectedOrderId}/mark-received`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('markReceivedModal'));
                modal.hide();
                
                // Show success message
                this.showSuccessMessage(data.message);
                
                // Refresh the orders list
                this.refreshCurrentView();
            } else {
                throw new Error(data.message || 'Failed to mark order as received');
            }
        })
        .catch(error => {
            console.error('Mark received error:', error);
            this.showError('Failed to mark order as received. Please try again.');
        })
        .finally(() => {
            // Reset button
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
    }

    refreshCurrentView() {
        const searchTerm = document.getElementById('orderSearchInput')?.value || '';
        
        if (searchTerm) {
            this.performSearch(searchTerm);
        } else {
            this.filterByStatus(this.currentStatus);
        }
    }

    showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    }

    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    showSuccessMessage(message) {
        // Create and show success alert
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.account-main-card .card-body');
        if (container) {
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    }

    showError(message) {
        // Create and show error alert
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.account-main-card .card-body');
        if (container) {
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-dismiss after 8 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 8000);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new OrderHistory();
});