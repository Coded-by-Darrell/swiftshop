/**
 * SwiftShop Cart Functionality
 * Handles all cart-related JavaScript operations
 */

document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    /**
     * Initialize Buy Now buttons
     */
    function initBuyNowButtons() {
        console.log('Initializing Buy Now buttons...');
        
        document.querySelectorAll('.btn-buy-now').forEach(button => {
            console.log('Found Buy Now button:', button);
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Buy Now button clicked!');
                
                const productId = this.dataset.productId || 
                                 this.closest('.product-card').dataset.productId ||
                                 this.closest('[data-product-id]').dataset.productId;
                
                console.log('Product ID:', productId);
                
                if (productId) {
                    buyNow(productId, 1);
                } else {
                    console.error('Product ID not found for Buy Now');
                }
            });
        });
    }
    
    /**
     * Buy Now function - creates quick checkout session
     */
    function buyNow(productId, quantity = 1) {
        console.log('Buy Now called with:', productId, quantity);
        
        showLoadingState('Processing...');
        
        fetch('/test-checkout/buy-now', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => {
            console.log('Response received:', response);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            hideLoadingState();
            
            if (data.success) {
                showNotification(data.message, 'success');
                
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoadingState();
            console.error('Buy Now Error:', error);
            showNotification('Failed to process Buy Now request', 'error');
        });
    }
    
    /**
     * Initialize cart checkout buttons
     */
    function initCartCheckoutButtons() {
        console.log('Initializing cart checkout buttons...');
        
        // Individual vendor checkout buttons
        document.querySelectorAll('.vendor-checkout-btn').forEach(button => {
            console.log('Found vendor checkout button:', button);
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const vendorId = this.dataset.vendorId;
                console.log('Vendor checkout clicked for vendor:', vendorId);
                
                if (vendorId) {
                    checkoutVendor(vendorId);
                } else {
                    console.error('Vendor ID not found');
                }
            });
        });
        
        // Checkout all selected items button
        document.querySelectorAll('.checkout-all-btn').forEach(button => {
            console.log('Found checkout all button:', button);
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                console.log('Checkout all clicked');
                checkoutAll();
            });
        });
    }
    
    /**
     * Checkout specific vendor items
     */
    function checkoutVendor(vendorId) {
        console.log('Checking out vendor:', vendorId);
        
        showLoadingState('Preparing checkout...');
        
        const requestData = {
            vendor_id: vendorId
        };
        
        fetch('/test-checkout/checkout-vendor', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            console.log('Vendor checkout response received:', response);
            return response.json();
        })
        .then(data => {
            console.log('Vendor checkout response data:', data);
            hideLoadingState();
            
            if (data.success) {
                showNotification(data.message, 'success');
                
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoadingState();
            console.error('Vendor checkout error:', error);
            showNotification('Failed to process checkout', 'error');
        });
    }
    
    /**
     * Checkout all selected items
     */
    function checkoutAll() {
        console.log('Checking out all items');
        
        showLoadingState('Preparing checkout...');
        
        const requestData = {};
        
        fetch('/test-checkout/checkout-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            console.log('Checkout all response received:', response);
            return response.json();
        })
        .then(data => {
            console.log('Checkout all response data:', data);
            hideLoadingState();
            
            if (data.success) {
                showNotification(data.message, 'success');
                
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoadingState();
            console.error('Checkout all error:', error);
            showNotification('Failed to process checkout', 'error');
        });
    }
    
    /**
     * Initialize quantity control buttons
     */
    function initCartQuantityControls() {
        // Quantity increase buttons
        document.querySelectorAll('.quantity-increase').forEach(button => {
            button.addEventListener('click', function() {
                const cartKey = this.dataset.cartKey;
                const input = document.querySelector(`input[data-cart-key="${cartKey}"]`);
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.max);
                
                if (currentValue < maxValue) {
                    updateQuantity(cartKey, currentValue + 1);
                }
            });
        });
        
        // Quantity decrease buttons
        document.querySelectorAll('.quantity-decrease').forEach(button => {
            button.addEventListener('click', function() {
                const cartKey = this.dataset.cartKey;
                const input = document.querySelector(`input[data-cart-key="${cartKey}"]`);
                const currentValue = parseInt(input.value);
                
                if (currentValue > 1) {
                    updateQuantity(cartKey, currentValue - 1);
                }
            });
        });
        
        // Direct input change
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartKey = this.dataset.cartKey;
                const newValue = parseInt(this.value);
                const maxValue = parseInt(this.max);
                
                if (newValue >= 1 && newValue <= maxValue) {
                    updateQuantity(cartKey, newValue);
                } else {
                    this.value = Math.min(Math.max(1, newValue), maxValue);
                }
            });
        });
    }
    
    /**
     * Initialize remove item buttons
     */
    function initRemoveItemButtons() {
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cartKey = this.dataset.cartKey;
                removeItem(cartKey);
            });
        });
    }
    
    /**
     * Initialize suggested product buttons
     */
    function initSuggestedProductButtons() {
        // Add suggested products to cart
        document.querySelectorAll('.btn-add-suggested').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                addToCart(productId, 1);
            });
        });
        
        // Buy now buttons for suggested products
        document.querySelectorAll('.btn-buy-suggested').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                buyNow(productId, 1);
            });
        });
    }
    
    /**
     * Initialize Add to Cart buttons (for product cards)
     */
    function initAddToCartButtons() {
        document.querySelectorAll('.btn-cart-icon, .add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.dataset.productId || 
                                 this.closest('.product-card').dataset.productId ||
                                 this.closest('[data-product-id]').dataset.productId;
                
                if (productId) {
                    addToCart(productId, 1);
                } else {
                    console.error('Product ID not found');
                }
            });
        });
    }
    
    /**
     * Update item quantity in cart
     */
    function updateQuantity(cartKey, quantity) {
        showLoadingState();
        
        fetch('/test-cart/update', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                cart_key: cartKey,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoadingState();
            
            if (data.success) {
                const input = document.querySelector(`input[data-cart-key="${cartKey}"]`);
                if (input) {
                    input.value = quantity;
                }
                
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoadingState();
            console.error('Error:', error);
            showNotification('Failed to update cart', 'error');
        });
    }
    
    /**
     * Remove item from cart
     */
    function removeItem(cartKey) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            showLoadingState();
            
            fetch('/test-cart/remove', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    cart_key: cartKey
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoadingState();
                
                if (data.success) {
                    const cartItem = document.querySelector(`[data-cart-key="${cartKey}"]`);
                    if (cartItem) {
                        cartItem.remove();
                    }
                    
                    updateCartCount();
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                hideLoadingState();
                console.error('Error:', error);
                showNotification('Failed to remove item', 'error');
            });
        }
    }
    
    /**
     * Add product to cart
     */
    function addToCart(productId, quantity = 1) {
        showLoadingState();
        
        fetch('/test-cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoadingState();
            
            if (data.success) {
                showNotification(data.message, 'success');
                updateCartCount();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoadingState();
            console.error('Error:', error);
            showNotification('Failed to add item to cart', 'error');
        });
    }
    
    /**
     * Update cart count in navbar
     */
    function updateCartCount() {
        fetch('/test-cart/count')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.querySelector('.cart-badge');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count;
                    
                    if (data.cart_count > 0) {
                        cartBadge.style.display = 'flex';
                    } else {
                        cartBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
    }
    
    /**
     * Show loading state
     */
    function showLoadingState(message = 'Loading...') {
        const existingLoader = document.querySelector('.cart-loader');
        if (!existingLoader) {
            const loader = document.createElement('div');
            loader.className = 'cart-loader';
            loader.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${message}`;
            loader.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--primary-blue);
                color: white;
                padding: 10px 15px;
                border-radius: 6px;
                z-index: 9999;
                font-size: 14px;
            `;
            document.body.appendChild(loader);
        }
    }
    
    /**
     * Hide loading state
     */
    function hideLoadingState() {
        const loader = document.querySelector('.cart-loader');
        if (loader) {
            loader.remove();
        }
    }
    
    /**
     * Show notification message
     */
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification-${type}`;
        notification.textContent = message;
        
        const bgColor = type === 'success' ? '#28a745' : 
                       type === 'error' ? '#dc3545' : 
                       '#0C5CE6';
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 12px 18px;
            border-radius: 6px;
            z-index: 9999;
            font-size: 14px;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
    
    // Initialize all cart functionality after functions are defined
    initCartQuantityControls();
    initRemoveItemButtons();
    initSuggestedProductButtons();
    initAddToCartButtons();
    initBuyNowButtons();
    initCartCheckoutButtons(); // THIS WAS MISSING!
    
    // Initialize cart count on page load
    updateCartCount();
    
    // DEBUG: Add this temporarily
    console.log('=== CART DEBUG INFO ===');
    console.log('Vendor checkout buttons found:', document.querySelectorAll('.vendor-checkout-btn').length);
    console.log('Checkout all buttons found:', document.querySelectorAll('.checkout-all-btn').length);
    
    document.querySelectorAll('.vendor-checkout-btn').forEach((btn, index) => {
        console.log(`Vendor button ${index}:`, btn, 'Vendor ID:', btn.dataset.vendorId);
    });
    
    document.querySelectorAll('.checkout-all-btn').forEach((btn, index) => {
        console.log(`Checkout all button ${index}:`, btn);
    });
});