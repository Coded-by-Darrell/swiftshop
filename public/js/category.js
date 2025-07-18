// Category Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Price suggestion buttons
    const priceSuggestions = document.querySelectorAll('.price-suggestion');
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    
    priceSuggestions.forEach(button => {
        button.addEventListener('click', function() {
            const min = this.dataset.min;
            const max = this.dataset.max;
            
            minPriceInput.value = min;
            maxPriceInput.value = max === '999999' ? '' : max;
            
            // Remove active class from all suggestions
            priceSuggestions.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
        });
    });
    
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('productsGrid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active state
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Toggle view
            if (view === 'list') {
                productsGrid.classList.add('list-view');
            } else {
                productsGrid.classList.remove('list-view');
            }
        });
    });
    
    // Apply filters functionality
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    applyFiltersBtn.addEventListener('click', function() {
        const filters = collectFilters();
        applyFiltersToURL(filters);
    });
    
    clearFiltersBtn.addEventListener('click', function() {
        clearAllFilters();
    });
    
    // Filter collection function
    function collectFilters() {
        const filters = {};
        
        // Price range
        const minPrice = minPriceInput.value;
        const maxPrice = maxPriceInput.value;
        if (minPrice) filters.min_price = minPrice;
        if (maxPrice) filters.max_price = maxPrice;
        
        // Subcategories
        const subcategories = [];
        document.querySelectorAll('input[name="subcategories[]"]:checked').forEach(input => {
            subcategories.push(input.value);
        });
        if (subcategories.length > 0) filters.subcategories = subcategories;
        
        // Brands
        const brands = [];
        document.querySelectorAll('input[name="brands[]"]:checked').forEach(input => {
            brands.push(input.value);
        });
        if (brands.length > 0) filters.brands = brands;
        
        // Rating
        const ratings = [];
        document.querySelectorAll('input[name="rating[]"]:checked').forEach(input => {
            ratings.push(input.value);
        });
        if (ratings.length > 0) filters.rating = ratings;
        
        return filters;
    }
    
    // Apply filters to URL
    function applyFiltersToURL(filters) {
        const url = new URL(window.location);
        
        // Clear existing filter parameters
        url.searchParams.delete('min_price');
        url.searchParams.delete('max_price');
        url.searchParams.delete('subcategories');
        url.searchParams.delete('brands');
        url.searchParams.delete('rating');
        
        // Add new filter parameters
        Object.keys(filters).forEach(key => {
            if (Array.isArray(filters[key])) {
                filters[key].forEach(value => {
                    url.searchParams.append(key + '[]', value);
                });
            } else {
                url.searchParams.set(key, filters[key]);
            }
        });
        
        // Reload page with new filters
        window.location.href = url.toString();
    }
    
    // Clear all filters
    function clearAllFilters() {
        // Clear price inputs
        minPriceInput.value = '';
        maxPriceInput.value = '';
        
        // Clear all checkboxes
        document.querySelectorAll('.filter-options input[type="checkbox"]').forEach(input => {
            input.checked = false;
        });
        
        // Remove active class from price suggestions
        priceSuggestions.forEach(btn => btn.classList.remove('active'));
        
        // Reload page without filters
        const url = new URL(window.location);
        url.search = '';
        window.location.href = url.toString();
    }
    
    // Load filters from URL on page load
    function loadFiltersFromURL() {
        const url = new URL(window.location);
        
        // Load price filters
        const minPrice = url.searchParams.get('min_price');
        const maxPrice = url.searchParams.get('max_price');
        if (minPrice) minPriceInput.value = minPrice;
        if (maxPrice) maxPriceInput.value = maxPrice;
        
        // Load subcategory filters
        const subcategories = url.searchParams.getAll('subcategories[]');
        subcategories.forEach(subcategory => {
            const checkbox = document.querySelector(`input[name="subcategories[]"][value="${subcategory}"]`);
            if (checkbox) checkbox.checked = true;
        });
        
        // Load brand filters
        const brands = url.searchParams.getAll('brands[]');
        brands.forEach(brand => {
            const checkbox = document.querySelector(`input[name="brands[]"][value="${brand}"]`);
            if (checkbox) checkbox.checked = true;
        });
        
        // Load rating filters
        const ratings = url.searchParams.getAll('rating[]');
        ratings.forEach(rating => {
            const checkbox = document.querySelector(`input[name="rating[]"][value="${rating}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
    
    // Load filters on page load
    loadFiltersFromURL();
});

// Wishlist functionality
function toggleWishlist(productId) {
    const button = event.target.closest('.wishlist-btn');
    const icon = button.querySelector('i');
    
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        button.classList.add('active');
        
        // Here you would make an AJAX call to add to wishlist
        console.log('Added product', productId, 'to wishlist');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        button.classList.remove('active');
        
        // Here you would make an AJAX call to remove from wishlist
        console.log('Removed product', productId, 'from wishlist');
    }
}

// Add to cart functionality
function addToCart(productId) {
    const button = event.target;
    const originalText = button.textContent;
    
    // Disable button and show loading state
    button.disabled = true;
    button.textContent = 'Adding...';
    
    // Here you would make an AJAX call to add to cart
    setTimeout(() => {
        button.textContent = 'Added!';
        button.classList.add('btn-success');
        button.classList.remove('btn-primary');
        
        setTimeout(() => {
            button.disabled = false;
            button.textContent = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-primary');
        }, 1500);
        
        console.log('Added product', productId, 'to cart');
    }, 800);
}