/**
 * Seller Dashboard JavaScript
 * Handles store status toggle and other seller dashboard interactions
 */

class SellerDashboard {
    constructor() {
        this.initializeEventListeners();
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Initialize all event listeners
     */
    initializeEventListeners() {
        // Store status toggle
        const storeToggle = document.getElementById('storeStatusToggle');
        if (storeToggle) {
            storeToggle.addEventListener('change', (e) => this.handleStoreToggle(e));
        }
    }

    /**
     * Handle store status toggle
     */
    async handleStoreToggle(event) {
        const toggle = event.target;
        const isActive = toggle.checked;
        
        // Show loading state
        toggle.disabled = true;
        this.showLoadingState();

        try {
            const response = await fetch('/test-seller/toggle-store-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    store_active: isActive
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateStoreStatusUI(data.store_active);
                this.showSuccessMessage(data.message);
            } else {
                // Revert toggle state
                toggle.checked = !isActive;
                this.showErrorMessage(data.message || 'Failed to update store status');
            }
        } catch (error) {
            console.error('Error toggling store status:', error);
            // Revert toggle state
            toggle.checked = !isActive;
            this.showErrorMessage('Network error. Please try again.');
        } finally {
            toggle.disabled = false;
            this.hideLoadingState();
        }
    }

    /**
     * Update store status UI elements
     */
    updateStoreStatusUI(isActive) {
        // Update status value text
        const statusValue = document.querySelector('.status-value');
        if (statusValue) {
            statusValue.textContent = isActive ? 'Active' : 'Inactive';
            statusValue.className = `status-value ${isActive ? 'status-active' : 'status-inactive'}`;
        }

        // Update toggle label
        const toggleLabel = document.querySelector('.toggle-label');
        if (toggleLabel) {
            toggleLabel.textContent = isActive ? 'Store is Online' : 'Store is Offline';
        }

        // Update status description
        const statusDescription = document.querySelector('.status-description');
        if (statusDescription) {
            const icon = isActive ? 'fas fa-check-circle' : 'fas fa-pause-circle';
            const textClass = isActive ? 'text-success' : 'text-warning';
            const message = isActive 
                ? 'Customers can view and purchase your products'
                : 'Your products are temporarily hidden from customers';

            statusDescription.innerHTML = `
                <p class="${textClass} mb-0">
                    <i class="${icon} me-1"></i>
                    ${message}
                </p>
            `;
        }
    }

    /**
     * Show loading state
     */
    showLoadingState() {
        // You can add a loading spinner or disable the toggle here
        const toggleLabel = document.querySelector('.toggle-label');
        if (toggleLabel) {
            toggleLabel.style.opacity = '0.6';
        }
    }

    /**
     * Hide loading state
     */
    hideLoadingState() {
        const toggleLabel = document.querySelector('.toggle-label');
        if (toggleLabel) {
            toggleLabel.style.opacity = '1';
        }
    }

    /**
     * Show success message
     */
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }

    /**
     * Show error message
     */
    showErrorMessage(message) {
        this.showMessage(message, 'error');
    }

    /**
     * Show toast message
     */
    showMessage(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-message toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
            color: ${type === 'success' ? '#155724' : '#721c24'};
            border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
            border-radius: 8px;
            padding: 12px 20px;
            z-index: 1050;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-family: var(--font-family);
            font-size: 0.9rem;
            animation: slideInRight 0.3s ease;
        `;

        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        // Add CSS animation
        if (!document.querySelector('#toast-styles')) {
            const style = document.createElement('style');
            style.id = 'toast-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(toast);

        // Auto remove after 4 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 4000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new SellerDashboard();
});