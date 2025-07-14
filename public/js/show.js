/**
 * Product Detail Page JavaScript
 * Handles interactive features for the product detail page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all interactive features
    initImageGallery();
    initColorSelection();
    initSizeSelection();
    initQuantityControls();
    initWishlistToggle();
    initActionButtons();
});

/**
 * Image Gallery Functionality
 * Handles thumbnail clicking and main image switching
 */
function initImageGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    const mainImage = document.querySelector('.main-product-image');
    
    if (!thumbnails.length || !mainImage) return;
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Update main image source
            mainImage.src = this.src;
            mainImage.alt = this.alt;
            
            // Add loading effect
            mainImage.style.opacity = '0.7';
            setTimeout(() => {
                mainImage.style.opacity = '1';
            }, 150);
        });
    });
}

/**
 * Color Selection Functionality
 * Handles color option selection and updates
 */
function initColorSelection() {
    const colorOptions = document.querySelectorAll('.color-option');
    const selectedColorSpan = document.querySelector('.selected-color');
    
    if (!colorOptions.length || !selectedColorSpan) return;
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all color options
            colorOptions.forEach(o => o.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Update selected color text
            const colorName = this.getAttribute('data-color');
            selectedColorSpan.textContent = colorName;
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Trigger custom event for color change
            const event = new CustomEvent('colorChanged', {
                detail: { color: colorName }
            });
            document.dispatchEvent(event);
        });
    });
}

/**
 * Size Selection Functionality
 * Handles size option selection and updates
 */
function initSizeSelection() {
    const sizeOptions = document.querySelectorAll('.size-option');
    const selectedSizeSpan = document.querySelector('.selected-size');
    
    if (!sizeOptions.length || !selectedSizeSpan) return;
    
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all size options
            sizeOptions.forEach(o => o.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Update selected size text
            const sizeName = this.getAttribute('data-size');
            selectedSizeSpan.textContent = sizeName;
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Trigger custom event for size change
            const event = new CustomEvent('sizeChanged', {
                detail: { size: sizeName }
            });
            document.dispatchEvent(event);
        });
    });
}

/**
 * Quantity Controls Functionality
 * Handles quantity increase/decrease and input validation
 */
function initQuantityControls() {
    const quantityInput = document.querySelector('.quantity-input');
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    
    if (!quantityInput || !decreaseBtn || !increaseBtn) return;
    
    // Decrease quantity
    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value) || 1;
        const minValue = parseInt(quantityInput.getAttribute('min')) || 1;
        
        if (currentValue > minValue) {
            quantityInput.value = currentValue - 1;
            updateQuantityDisplay();
            
            // Trigger custom event for quantity change
            triggerQuantityChangeEvent(currentValue - 1);
        }
        
        // Add visual feedback
        addButtonFeedback(this);
    });
    
    // Increase quantity
    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value) || 1;
        const maxValue = parseInt(quantityInput.getAttribute('max')) || 99;
        
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
            updateQuantityDisplay();
            
            // Trigger custom event for quantity change
            triggerQuantityChangeEvent(currentValue + 1);
        }
        
        // Add visual feedback
        addButtonFeedback(this);
    });
    
    // Handle direct input changes
    quantityInput.addEventListener('input', function() {
        let value = parseInt(this.value) || 1;
        const minValue = parseInt(this.getAttribute('min')) || 1;
        const maxValue = parseInt(this.getAttribute('max')) || 99;
        
        // Validate input
        if (value < minValue) {
            this.value = minValue;
            value = minValue;
        } else if (value > maxValue) {
            this.value = maxValue;
            value = maxValue;
        }
        
        updateQuantityDisplay();
        triggerQuantityChangeEvent(value);
    });
    
    // Handle blur event for final validation
    quantityInput.addEventListener('blur', function() {
        if (!this.value || parseInt(this.value) < 1) {
            this.value = 1;
            updateQuantityDisplay();
            triggerQuantityChangeEvent(1);
        }
    });
}

/**
 * Update quantity display and related elements
 */
function updateQuantityDisplay() {
    const quantityInput = document.querySelector('.quantity-input');
    const quantity = parseInt(quantityInput.value) || 1;
    
    // Update button states
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const minValue = parseInt(quantityInput.getAttribute('min')) || 1;
    const maxValue = parseInt(quantityInput.getAttribute('max')) || 99;
    
    // Disable/enable buttons based on quantity limits
    if (decreaseBtn) {
        decreaseBtn.disabled = quantity <= minValue;
        decreaseBtn.style.opacity = quantity <= minValue ? '0.5' : '1';
    }
    
    if (increaseBtn) {
        increaseBtn.disabled = quantity >= maxValue;
        increaseBtn.style.opacity = quantity >= maxValue ? '0.5' : '1';
    }
}

/**
 * Trigger quantity change event
 */
function triggerQuantityChangeEvent(quantity) {
    const event = new CustomEvent('quantityChanged', {
        detail: { quantity: quantity }
    });
    document.dispatchEvent(event);
}

/**
 * Add visual feedback to buttons
 */
function addButtonFeedback(button) {
    button.style.transform = 'scale(0.9)';
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 100);
}

/**
 * Wishlist Toggle Functionality
 * Handles wishlist add/remove with visual feedback
 */
function initWishlistToggle() {
    const wishlistBtn = document.querySelector('.wishlist-btn');
    
    if (!wishlistBtn) return;
    
    wishlistBtn.addEventListener('click', function() {
        const icon = this.querySelector('i');
        const isActive = this.classList.contains('active');
        
        if (!isActive) {
            // Add to wishlist
            icon.classList.remove('far');
            icon.classList.add('fas');
            this.classList.add('active');
            
            // Show feedback message (you can customize this)
            showNotification('Added to wishlist!', 'success');
        } else {
            // Remove from wishlist
            icon.classList.remove('fas');
            icon.classList.add('far');
            this.classList.remove('active');
            
            // Show feedback message
            showNotification('Removed from wishlist!', 'info');
        }
        
        // Add visual feedback
        this.style.transform = 'scale(1.1)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 200);
        
        // Trigger custom event for wishlist change
        const event = new CustomEvent('wishlistToggled', {
            detail: { 
                isActive: !isActive,
                productId: getProductId() // You'll need to implement this function
            }
        });
        document.dispatchEvent(event);
    });
}

/**
 * Action Buttons Functionality
 * Handles Add to Cart and Buy Now button clicks
 */
function initActionButtons() {
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    const buyNowBtn = document.querySelector('.buy-now-btn');
    
    // Add to Cart functionality
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productData = getProductData();
            
            // Add visual feedback
            this.style.transform = 'scale(0.98)';
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                this.innerHTML = 'Add to Cart';
                showNotification('Added to cart!', 'success');
            }, 1000);
            
            // Trigger custom event for add to cart
            const event = new CustomEvent('addToCart', {
                detail: productData
            });
            document.dispatchEvent(event);
        });
    }
    
    // Buy Now functionality
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            const productData = getProductData();
            
            // Add visual feedback
            this.style.transform = 'scale(0.98)';
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                this.innerHTML = 'Buy Now';
                // Redirect to checkout or show checkout modal
                // window.location.href = '/checkout';
            }, 1000);
            
            // Trigger custom event for buy now
            const event = new CustomEvent('buyNow', {
                detail: productData
            });
            document.dispatchEvent(event);
        });
    }
}

/**
 * Get current product data from the page
 * This function collects all the current selections
 */
function getProductData() {
    const selectedColor = document.querySelector('.selected-color')?.textContent || '';
    const selectedSize = document.querySelector('.selected-size')?.textContent || '';
    const quantity = parseInt(document.querySelector('.quantity-input')?.value) || 1;
    const productId = getProductId();
    
    return {
        productId: productId,
        color: selectedColor,
        size: selectedSize,
        quantity: quantity,
        timestamp: new Date().toISOString()
    };
}

/**
 * Get product ID from the page
 * You might need to adjust this based on how you pass the product ID
 */
function getProductId() {
    // You can get this from a data attribute, URL, or hidden input
    // Example: return document.querySelector('[data-product-id]')?.dataset.productId;
    return window.location.pathname.split('/').pop(); // Gets ID from URL
}

/**
 * Show notification messages
 * Simple notification system - you can enhance this or integrate with your existing system
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Initialize quantity display on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    updateQuantityDisplay();
});