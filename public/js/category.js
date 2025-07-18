// Category Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Price suggestion buttons (removed functionality since buttons were removed)
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    
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
        const minPrice = minPriceInput.value.trim();
        const maxPrice = maxPriceInput.value.trim();
        if (minPrice && minPrice !== '') filters.min_price = minPrice;
        if (maxPrice && maxPrice !== '') filters.max_price = maxPrice;
        
        // Vendors/Brands
        const brands = [];
        document.querySelectorAll('input[name="brands[]"]:checked').forEach(input => {
            brands.push(input.value);
        });
        if (brands.length > 0) filters.brands = brands;
        
        return filters;
    }
    
    // Apply filters to URL
    function applyFiltersToURL(filters) {
        const url = new URL(window.location);
        
        // Clear existing filter parameters
        url.searchParams.delete('min_price');
        url.searchParams.delete('max_price');
        
        // Clear all brand parameters (important for checkbox persistence)
        const existingBrands = url.searchParams.getAll('brands[]');
        existingBrands.forEach(brand => {
            url.searchParams.delete('brands[]');
        });
        
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
        
        // Load vendor/brand filters
        const brands = url.searchParams.getAll('brands[]');
        
        // First, uncheck all checkboxes
        document.querySelectorAll('input[name="brands[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Then check only the ones that should be checked
        brands.forEach(brand => {
            const checkbox = document.querySelector(`input[name="brands[]"][value="${brand}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    
    // Load filters on page load
    loadFiltersFromURL();
});

// Remove wishlist and cart functions since we're using the browse.index product card style
// These functions are now handled by the product card's own buttons