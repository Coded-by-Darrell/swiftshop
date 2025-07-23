// Enhanced Checkout JavaScript Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize checkout page
    updateCartCount();
    initializeAddressManagement();
    initializeShippingOptions();
    
    // Auto-fill form if using saved address
    const defaultAddress = document.querySelector('.address-option.selected');
    if (defaultAddress) {
        fillAddressFromElement(defaultAddress);
    }
});

/**
 * Enhanced Address Management
 */
function initializeAddressManagement() {
    // Add event listeners for form validation
    const form = document.getElementById('new-address-form');
    if (form) {
        const inputs = form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', clearFieldError);
        });
    }
}

function initializeShippingOptions() {
    // Add click listeners to shipping options
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.addEventListener('click', function() {
            const method = this.querySelector('input[type="radio"]').value;
            updateShippingMethod(method);
        });
    });
}

/**
 * Enhanced Address Selection
 */
function selectAddress(addressId) {
    // Remove selected class from all addresses
    document.querySelectorAll('.address-option').forEach(option => {
        option.classList.remove('selected');
        option.querySelector('input[type="radio"]').checked = false;
    });
    
    // Add selected class to clicked address
    const selectedAddress = document.querySelector(`input[value="${addressId}"]`).closest('.address-option');
    selectedAddress.classList.add('selected');
    selectedAddress.querySelector('input[type="radio"]').checked = true;
    
    // Hide new address form
    hideNewAddressForm();
    
    // Fill form with selected address data
    fillAddressFromElement(selectedAddress);
    
    // Show success feedback
    showNotification('success', 'Address selected successfully');
}

function toggleNewAddress() {
    const newAddressForm = document.getElementById('new-address-form');
    const isVisible = newAddressForm.style.display !== 'none';
    
    if (isVisible) {
        hideNewAddressForm();
    } else {
        showNewAddressForm();
    }
}

function showNewAddressForm() {
    const newAddressForm = document.getElementById('new-address-form');
    newAddressForm.style.display = 'block';
    
    // Deselect all saved addresses
    document.querySelectorAll('.address-option').forEach(option => {
        option.classList.remove('selected');
        option.querySelector('input[type="radio"]').checked = false;
    });
    
    // Clear and focus first field
    clearAddressForm();
    document.getElementById('full_name').focus();
    
    // Update toggle button text
    const toggleBtn = document.querySelector('[onclick="toggleNewAddress()"]');
    toggleBtn.innerHTML = '<i class="fas fa-minus me-2"></i>Cancel New Address';
}

function hideNewAddressForm() {
    const newAddressForm = document.getElementById('new-address-form');
    newAddressForm.style.display = 'none';
    
    // Update toggle button text
    const toggleBtn = document.querySelector('[onclick="toggleNewAddress()"]');
    toggleBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add New Address';
}

function fillAddressFromElement(addressElement) {
    const addressDetails = addressElement.querySelector('.address-details').textContent.trim();
    const lines = addressDetails.split('\n').map(line => line.trim()).filter(line => line);
    
    if (lines.length >= 3) {
        const fullName = lines[0];
        const streetAddress = lines[1];
        const cityPostal = lines[2].split(', ');
        
        // Fill form fields
        setFieldValue('full_name', fullName);
        setFieldValue('street_address', streetAddress);
        
        if (cityPostal.length >= 2) {
            setFieldValue('city', cityPostal[0].trim());
            setFieldValue('postal_code', cityPostal[1].trim());
        }
        
        // Get phone from data attribute if available
        const phoneData = addressElement.dataset.phone;
        if (phoneData) {
            setFieldValue('phone_number', phoneData);
        }
    }
}

function setFieldValue(fieldId, value) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.value = value;
        field.classList.remove('is-invalid');
    }
}

/**
 * Enhanced Shipping Management
 */
async function updateShippingMethod(method) {
    try {
        // Update UI immediately
        selectShippingOption(method);
        
        // Send request to update shipping calculation
        const response = await fetch('/test-checkout/update-shipping', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                shipping_method: method
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            updateOrderSummary(result.cart_totals);
            showNotification('success', 'Shipping method updated');
        } else {
            showNotification('error', result.message || 'Failed to update shipping method');
        }
        
    } catch (error) {
        console.error('Shipping update error:', error);
        showNotification('error', 'Failed to update shipping method');
    }
}

function selectShippingOption(method) {
    // Remove selected class from all shipping options
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.classList.remove('selected');
        option.querySelector('input[type="radio"]').checked = false;
    });
    
    // Add selected class to clicked option
    const selectedOption = document.querySelector(`input[value="${method}"]`).closest('.shipping-option');
    selectedOption.classList.add('selected');
    selectedOption.querySelector('input[type="radio"]').checked = true;
}

function updateOrderSummary(totals) {
    // Update shipping fee
    document.querySelector('.shipping-fee').textContent = `₱${totals.shipping.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
    
    // Update total
    document.querySelector('.total-amount').textContent = `₱${totals.total.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
    
    // Update place order button
    document.querySelector('.place-order-btn').innerHTML = `
        <i class="fas fa-lock me-2"></i>
        Place Order - ₱${totals.total.toLocaleString('en-PH', { minimumFractionDigits: 2 })}
    `;
}

/**
 * Save New Address Function
 */
async function saveNewAddress() {
    if (!validateNewAddressForm()) {
        return false;
    }
    
    const formData = new FormData();
    formData.append('label', document.getElementById('address_label').value || 'New Address');
    formData.append('full_name', document.getElementById('full_name').value);
    formData.append('phone_number', document.getElementById('phone_number').value);
    formData.append('street_address', document.getElementById('street_address').value);
    formData.append('city', document.getElementById('city').value);
    formData.append('postal_code', document.getElementById('postal_code').value);
    formData.append('is_default', document.getElementById('save_address').checked);
    
    try {
        const response = await fetch('/test-checkout/save-address', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('success', result.message);
            // Optionally reload addresses or add to UI
            return true;
        } else {
            showNotification('error', result.message || 'Failed to save address');
            return false;
        }
        
    } catch (error) {
        console.error('Save address error:', error);
        showNotification('error', 'Failed to save address');
        return false;
    }
}

/**
 * Enhanced Form Validation
 */
function validateField(event) {
    const field = event.target;
    const value = field.value.trim();
    
    clearFieldError(event);
    
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'This field is required');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    // Phone validation (basic)
    if (field.name === 'phone_number' && value) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        if (!phoneRegex.test(value)) {
            showFieldError(field, 'Please enter a valid phone number');
            return false;
        }
    }
    
    return true;
}

function clearFieldError(event) {
    const field = event.target;
    field.classList.remove('is-invalid');
    
    const errorElement = field.parentNode.querySelector('.invalid-feedback');
    if (errorElement) {
        errorElement.remove();
    }
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    // Remove existing error
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorElement = document.createElement('div');
    errorElement.className = 'invalid-feedback';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
}

function validateNewAddressForm() {
    const requiredFields = ['full_name', 'phone_number', 'contact_email', 'street_address', 'city', 'postal_code'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !validateField({ target: field })) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Enhanced Place Order with Address Saving
 */
async function placeOrder() {
    // Validate form
    if (!validateCheckoutForm()) {
        return;
    }
    
    // Save new address if form is visible and user wants to save
    const newAddressVisible = document.getElementById('new-address-form').style.display !== 'none';
    const shouldSaveAddress = document.getElementById('save_address')?.checked;
    
    if (newAddressVisible && shouldSaveAddress) {
        const addressSaved = await saveNewAddress();
        if (!addressSaved) {
            return; // Don't proceed if address saving failed
        }
    }
    
    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();
    
    // Disable place order button
    const placeOrderBtn = document.querySelector('.place-order-btn');
    placeOrderBtn.disabled = true;
    
    try {
        // Prepare form data
        const formData = new FormData(document.getElementById('checkout-form'));
        
        // Send request
        const response = await fetch('/test-checkout/place-order', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message briefly
            showNotification('success', 'Order placed successfully! Redirecting...');
            
            // Redirect to confirmation page after brief delay
            setTimeout(() => {
                window.location.href = result.redirect_url;
            }, 1500);
        } else {
            loadingModal.hide();
            placeOrderBtn.disabled = false;
            showNotification('error', result.message || 'An error occurred while placing your order');
        }
        
    } catch (error) {
        console.error('Checkout error:', error);
        loadingModal.hide();
        placeOrderBtn.disabled = false;
        showNotification('error', 'An error occurred while placing your order. Please try again.');
    }
}

/**
 * Missing functions to complete checkout.js functionality
 * Add these functions to your existing public/js/checkout.js file
 */

/**
 * Shipping Option Selection
 */
function selectShipping(method) {
    // Remove selected class from all shipping options
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.classList.remove('selected');
        option.querySelector('input[type="radio"]').checked = false;
    });
    
    // Add selected class to clicked option
    const selectedOption = document.querySelector(`input[value="${method}"]`).closest('.shipping-option');
    selectedOption.classList.add('selected');
    selectedOption.querySelector('input[type="radio"]').checked = true;
    
    // Update shipping calculation
    updateShippingMethod(method);
}

/**
 * Validate Checkout Form
 */
function validateCheckoutForm() {
    let isValid = true;
    
    // Check if address is selected or new address form is filled
    const selectedAddress = document.querySelector('.address-option.selected input[type="radio"]:checked');
    const newAddressVisible = document.getElementById('new-address-form').style.display !== 'none';
    
    if (!selectedAddress && !newAddressVisible) {
        showNotification('error', 'Please select or add a delivery address');
        return false;
    }
    
    // If new address form is visible, validate it
    if (newAddressVisible) {
        const requiredFields = ['full_name', 'phone_number', 'contact_email', 'street_address', 'city', 'postal_code'];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Validate email format
        const emailField = document.getElementById('contact_email');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                showFieldError(emailField, 'Please enter a valid email address');
                isValid = false;
            }
        }
    }
    
    // Check shipping method selection
    const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
    if (!selectedShipping) {
        showNotification('error', 'Please select a shipping method');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Clear Address Form
 */
function clearAddressForm() {
    const formFields = ['address_label', 'full_name', 'phone_number', 'contact_email', 'street_address', 'city', 'postal_code'];
    
    formFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.value = '';
            field.classList.remove('is-invalid');
        }
    });
    
    // Uncheck save address checkbox
    const saveCheckbox = document.getElementById('save_address');
    if (saveCheckbox) {
        saveCheckbox.checked = false;
    }
    
    // Remove all error messages
    document.querySelectorAll('.invalid-feedback').forEach(error => {
        error.remove();
    });
}

/**
 * Enhanced Notification System
 */
function showNotification(type, message) {
    // Remove existing notifications
    document.querySelectorAll('.checkout-notification').forEach(notification => {
        notification.remove();
    });
    
    const notification = document.createElement('div');
    notification.className = `checkout-notification alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

/**
 * Update Cart Count in Navbar
 */
async function updateCartCount() {
    try {
        const response = await fetch('/test-cart/count');
        const data = await response.json();
        
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = data.cart_count;
            
            if (data.cart_count > 0) {
                cartBadge.style.display = 'flex';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

/**
 * Auto-fill form based on selected address
 */
function fillFormFromSelectedAddress() {
    const selectedAddress = document.querySelector('.address-option.selected');
    if (selectedAddress) {
        const addressData = {
            full_name: selectedAddress.dataset.fullName || '',
            phone_number: selectedAddress.dataset.phone || '',
            street_address: selectedAddress.dataset.street || '',
            city: selectedAddress.dataset.city || '',
            postal_code: selectedAddress.dataset.postal || ''
        };
        
        Object.entries(addressData).forEach(([fieldName, value]) => {
            const field = document.getElementById(fieldName);
            if (field && value) {
                field.value = value;
            }
        });
    }
}

/**
 * Format currency display
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2
    }).format(amount);
}

/**
 * Initialize checkout validation on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation to form fields
    const requiredFields = document.querySelectorAll('input[required], textarea[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                showFieldError(this, 'This field is required');
            } else {
                clearFieldError({ target: this });
            }
        });
    });
    
    // Auto-fill contact email based on saved address
    fillFormFromSelectedAddress();
    
    console.log('SwiftShop Checkout System Initialized Successfully! 🚀');
});

// Missing functions - ADD TO END OF checkout.js

function selectShipping(method) {
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.classList.remove('selected');
        option.querySelector('input[type="radio"]').checked = false;
    });
    
    const selectedOption = document.querySelector(`input[value="${method}"]`).closest('.shipping-option');
    selectedOption.classList.add('selected');
    selectedOption.querySelector('input[type="radio"]').checked = true;
    
    updateShippingMethod(method);
}

function validateCheckoutForm() {
    let isValid = true;
    
    // Check address selection
    const selectedAddress = document.querySelector('.address-option.selected input[type="radio"]:checked');
    const newAddressVisible = document.getElementById('new-address-form').style.display !== 'none';
    
    if (!selectedAddress && !newAddressVisible) {
        showNotification('error', 'Please select or add a delivery address');
        return false;
    }
    
    // Validate new address form if visible
    if (newAddressVisible) {
        const requiredFields = ['full_name', 'phone_number', 'contact_email', 'street_address', 'city', 'postal_code'];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            }
        });
    }
    
    // Check shipping method
    const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
    if (!selectedShipping) {
        showNotification('error', 'Please select a shipping method');
        isValid = false;
    }
    
    return isValid;
}

function clearAddressForm() {
    const formFields = ['address_label', 'full_name', 'phone_number', 'contact_email', 'street_address', 'city', 'postal_code'];
    
    formFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.value = '';
            field.classList.remove('is-invalid');
        }
    });
    
    document.getElementById('save_address').checked = false;
    document.querySelectorAll('.invalid-feedback').forEach(error => error.remove());
}

// Keep existing functions: validateCheckoutForm, clearAddressForm, showNotification, updateCartCount