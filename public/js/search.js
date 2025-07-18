document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart functionality
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Add loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-success');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-primary');
                    this.disabled = false;
                }, 2000);
            }, 1000);
        });
    });
    
    // Wishlist functionality
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const heart = this.querySelector('i');
            
            if (heart.classList.contains('far')) {
                heart.classList.remove('far');
                heart.classList.add('fas');
                this.style.background = '#dc3545';
                this.style.color = 'white';
            } else {
                heart.classList.remove('fas');
                heart.classList.add('far');
                this.style.background = 'white';
                this.style.color = '#333';
            }
        });
    });
    
    // Product card click to view details
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't redirect if clicking on buttons
            if (e.target.closest('.add-to-cart-btn, .wishlist-btn, .btn')) {
                return;
            }
            
            const productLink = this.querySelector('.product-name a');
            if (productLink) {
                window.location.href = productLink.href;
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const minPriceInput = document.getElementById('min_price');
        const maxPriceInput = document.getElementById('max_price');
        const applyFiltersBtn = document.getElementById('applyFilters');
        const clearFiltersBtn = document.getElementById('clearFilters');
        
        applyFiltersBtn.addEventListener('click', function() {
            const filters = collectFilters();
            applyFiltersToURL(filters);
        });
        
        clearFiltersBtn.addEventListener('click', function() {
            clearAllFilters();
        });
        
        function collectFilters() {
            const filters = {};
            
            // Keep the search query
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('q');
            if (searchQuery) filters.q = searchQuery;
            
            // Price range ONLY
            const minPrice = minPriceInput.value.trim();
            const maxPrice = maxPriceInput.value.trim();
            if (minPrice && minPrice !== '') filters.min_price = minPrice;
            if (maxPrice && maxPrice !== '') filters.max_price = maxPrice;
            
            return filters;
        }
        
        function applyFiltersToURL(filters) {
            const url = new URL(window.location);
            url.search = '';
            
            Object.keys(filters).forEach(key => {
                url.searchParams.set(key, filters[key]);
            });
            
            window.location.href = url.toString();
        }
        
        function clearAllFilters() {
            const url = new URL(window.location);
            const searchQuery = url.searchParams.get('q');
            
            url.search = '';
            if (searchQuery) {
                url.searchParams.set('q', searchQuery);
            }
            
            window.location.href = url.toString();
        }
        
        function loadFiltersFromURL() {
            const url = new URL(window.location);
            
            const minPrice = url.searchParams.get('min_price');
            const maxPrice = url.searchParams.get('max_price');
            if (minPrice) minPriceInput.value = minPrice;
            if (maxPrice) maxPriceInput.value = maxPrice;
        }
        
        loadFiltersFromURL();
    });
});